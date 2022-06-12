<?php defined('BASEPATH') OR exit('No direct script access allowed');

class SuiviPaiement extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('general_helper');
		$this->load->database();

		$this->template->write_view('sidenavs', 'template/default_sidenavs', true);
		$this->template->write_view('navs', 'template/default_topnavs.php', true);
	}

	function index()
	{
		$this->template->write('title', 'Suivi de paiement', true);
		$this->template->write('header', 'Suivi de paiement');


		$data['clients'] = $this->db
			->query("SELECT c.`Id_Client`, c.`Nom`, c.`Prenom` FROM `client` c")
			->result_array();

		$this->template->write_view('content', 'tes/suivi_paiement', $data, true);

		$javascript = <<<EOF
$(".chosen-select").chosen();

$('#client-select').on('change', function () {
    $.ajax({
        beforeSend: function () {
            //before send request, hide the dropdown and display loading
            $(".ventes").append('<p class="loading">Chargement...</p>');
            $("#vente-select").html('<option value="0" selected disabled>Tous les ventes du client</option>');
        },
        url: "get_ventes?id_client=" + $(this).val(),
        success: function (response) {
            // update ventes
            JSON.parse(response).forEach(function (element) {
                $("#vente-select").append('<option value="' + element.Id_Vente + '">' + element.Num_Vente + '</option>');
            });
            
            $("#vente-select").trigger("chosen:updated");
            $(".ventes p.loading").remove();
        }
    });
});

$('#vente-select').on('change', function () {
    $("#echeances-table").html("");

    $.ajax({
        beforeSend: function () {
            //before send request, hide the dropdown and display loading
            $(".echeances").append('<p class="loading">Chargement...</p>');
        },
        url: "get_suivi?id_vente=" + $(this).val(),
        success: function (response) {
            $(".echeances p.loading").remove();
            $("#generate_report").removeAttr("disabled");

            // show the table
            var tr = $('<tr></tr>');
            tr.append('<th style="text-align:center;">Mois<br><br>Annee</th>');
            for (var i = 1; i < 13; i++) {
                tr.append('<th style="text-align:center;">' + i + '</th>');
            }
            $("#echeances-table").append(tr);

            var echeances = JSON.parse(response).echeances;

            for (var year in echeances) {
                tr = $('<tr></tr>');
                tr.append('<td>' + year + '</td>');

                for (var element in echeances[year]) {
                    var items = echeances[year][element]
                    var td = '<td>';

                    for (var item in items) {
                    	if (items.length > 1 && items[item].montant == "--") {
                    		continue;
                    	}

                        var style = "background-color:" + items[item].color;
                        
                        if (items[item].color != "") {
                            style = style + ";color: white;";
                        }

                        td += '<div style="' + style + '">' + items[item].montant + '</div>';
                    }
                    
                    td += '</td>';
                    tr.append(td);
                }
                $("#echeances-table").append(tr);
            }
        }
    });
});
EOF;

		$this->template->write('javascript', $javascript);
		$this->template->render();
	}

	function get_ventes()
	{
		$id_client = $this->input->get('id_client');

		$ventes = $this->db
			->query("SELECT v.`Id_Vente`, v.`Num_Vente` FROM `vente` v WHERE v.Id_Client=" . $id_client)
			->result_array();

		echo json_encode($ventes);
		return;
	}

	function suivi($vente_id)
	{
		$echeances = $this->db
			->query("
SELECT *, MONTH(Date_echeance) AS `month`, YEAR(Date_echeance) AS `year`
FROM `echeance` e
WHERE e.Id_Vente=" . $vente_id
			)
			->result_array();

		$output['echeances'] = [];

		foreach ($echeances as $echeance) {
			if (!isset($output['echeances'][$echeance['year']])) {
				for ($i = 1; $i <= 12; $i++) {
					$output['echeances'][$echeance['year']][$i][] = [
						'montant' => '--',
						'color'   => '',
					];
				}
			}

			$output['echeances'][$echeance['year']][$echeance['month']][] = [
				'montant' => $echeance['Montant'],
				'color'   => strtotime($echeance['Date_echeance']) > (new DateTime())->getTimestamp() ? '#AAAAAA' : ($echeance['Payée'] == 'Oui' ? '#00FF00' : '#FF0000'),
			];
		}

		$output['years'] = array_keys($output['echeances']);

		return $output;
	}

	function get_suivi()
	{
		$id_vente = $this->input->get('id_vente');

		$output = $this->suivi($id_vente);

		echo json_encode($output);
		return;
	}

	function generate_report()
	{
		$id_vente = $this->input->post('id_vente');

		$data['company_name'] = $this->db->query("SELECT value from settings where settings.key = 'app_nom'")->result()[0]->value;
		$data['title']        = "Suivi de paiement";
		$data['result']       = $this->suivi($id_vente);

		$filename = "SUIVI_PAIEMENT_" . (new DateTime())->format("dmY_Hi") . '_' . (new DateTime())->getTimestamp() . '.pdf';
		$pdf_view = $this->load->view('pdf/suivi_paiement', $data, true);

		// load the library Html2pdf
		$this->load->library('Html2pdf');
		//Set folder to save PDF to
		$this->html2pdf->folder('./assets/pdfs/');
		//Set the paper defaults
		$this->html2pdf->paper('a4', 'portrait');
		//Set the filename to save/download as
		$this->html2pdf->filename($filename);
		//Load html view
		$this->html2pdf->html($pdf_view);
		$this->html2pdf->isHtml5ParserEnabled = true;
		//Download the file
		$this->html2pdf->create('download');

		die('Generation Finished.');
	}
}
