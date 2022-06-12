<?php defined('BASEPATH') OR exit('No direct script access allowed');

class SituationFinanciere extends CI_Controller
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
		$this->template->write('title', 'Situation Financière', true);
		$this->template->write('header', 'Situation Financière');


		$data['clients'] = $this->db
			->query("SELECT c.`Id_Client`, c.`Nom`, c.`Prenom` FROM `client` c")
			->result_array();

		$data['biens'] = [];

		$this->template->write_view('content', 'tes/situation_financiere', $data, true);

		$javascript = <<<EOF
$(".chosen-select").chosen();

$('#client-select').on('change', function () {
	var clien_id = $("#client-select").val();

    $.ajax({
        beforeSend: function () {
            // before send request, hide the dropdown and display loading
            $(".biens").append('<p class="loading">Chargement...</p>');
            $("#bien-select").html('<option value="0" selected disabled>Tous les biens</option>');
        },
        url: "get_biens?id_client=" + clien_id,
        success: function (response) {
            response = JSON.parse(response);
            response.forEach(function (bien, index) {
            	$("#bien-select").append('<option value="' + bien.Id_Bien + '">' + bien.Nom_Projet + ' - ' + bien.Num_Bien + '</option>');
            });
            
            $("#bien-select").trigger("chosen:updated");
            $(".biens p.loading").remove();
        }
    });

	filling_table();
});

$('#bien-select').on('change', function () {
	filling_table();
});

function filling_table() {
    var clien_id = $("#client-select").val();
    var bien_id = $("#bien-select").val();
    
	$("#situation-table").html('');
	$("#echeances-table tbody").html('');
	$("#situation").hide();

    if (clien_id && clien_id.length > 0 && bien_id && bien_id.length > 0) {
        $.ajax({
            beforeSend: function () {
                // before send request, hide the dropdown and display loading
                $(".biens").append('<p class="loading">Chargement...</p>');
            },
            url: "get_situation?id_client=" + clien_id + "&id_bien=" + bien_id,
            success: function (response) {
                $(".biens p.loading").remove();
                $("#generate_report").removeAttr("disabled");

                // show the table
                response = JSON.parse(response);
                var situation = response.situation,
                	echeances = response.echeances;

				if (situation) {
	                var tbody = $('<tbody></tbody>');
	                var tr = $('<tr></tr>');
	                tr.append('<td><b>Num d\'Ordre : </b> ' + situation.Num_Ordre + '</td>');
	                tr.append('<td><b>Adresse : </b>' + situation.Adresse + '</td>');
	                tbody.append(tr);
	                tr = $('<tr></tr>');
	                tr.append('<td><b>Projet : </b>' + situation.Projet + '</td>');
	                tr.append('<td><b>Type : </b>' + situation.Type + '</td>');
	                tbody.append(tr);
	                tr = $('<tr></tr>');
	                tr.append('<td><b>Num Bien : </b>' + situation.Num_Bien + '</td>');
	                tr.append('<td><b>Num Titre Fonctier : </b>' + situation.Num_Titre_Foncier + '</td>');
	                tbody.append(tr);
	                tr = $('<tr></tr>');
	                tr.append('<td><b>Prix : </b>' + situation.Prix + '</td>');
	                tr.append('<td><b>Avance : </b>' + situation.Avance + '<span style="margin-left:10px;"><b> Reste : </b>' + situation.Reste + 
	                '</span></td>');
	                tbody.append(tr);
	                
	                $("#situation-table").html(tbody);
				}

                
                if (echeances) {
	                echeances.forEach(function (echeance, index) {
	                    tr = $('<tr></tr>');
	                    tr.append('<td>' + echeance.Date_echeance + '</td>');
	                    tr.append('<td>' + echeance.Montant + '</td>');
	                    tr.append('<td>' + echeance.Partie_Versante + '</td>');
	                    tr.append('<td>' + echeance.Payée + '</td>');
	
	                    $("#echeances-table tbody").append(tr);
	                });
	                $("#situation").show();
                }
                
                $("#generate_report").removeAttr("disabled");
            }
        });
    }
}
EOF;
		$this->template->write('javascript', $javascript);
		$this->template->render();
	}

	function get_biens()
	{
		$id_client = $this->input->get('id_client');
		$biens     = $this->db
			->query("
SELECT projet.Nom_Projet, biens.Num AS Num_Bien, vente.Id_Bien
FROM `vente`
LEFT JOIN biens ON biens.Id_Bien = vente.Id_Bien
LEFT JOIN blocs ON blocs.Id_Bloc = biens.Id_Block
LEFT JOIN projet ON projet.Id_Projet = blocs.Id_Projet
WHERE vente.Id_Client=" . $id_client . " ;"
			)
			->result_array();

		echo json_encode($biens);
		return;
	}

	function situation($id_client, $id_bien)
	{
		$bien = $this->db
			->query("
SELECT projet.Nom_Projet, projet.Adresse, biens.Num_Ordre, biens.Num AS Num_Bien, biens.Type, biens.Num_Titre_Foncier, biens.Prix_Bien, vente.Num_Vente, vente.Avance_Prix, vente.Id_Bien, vente.Id_Client, vente.Id_Vente
FROM `vente`
LEFT JOIN biens ON biens.Id_Bien = vente.Id_Bien
LEFT JOIN blocs ON blocs.Id_Bloc = biens.Id_Block
LEFT JOIN projet ON projet.Id_Projet = blocs.Id_Projet
WHERE vente.Id_Client=" . $id_client . " AND vente.Id_Bien=" . $id_bien . ";"
			)
			->row_array();

		$output = [];
		if ($bien) {
			$echeances = $this->db
				->query("
SELECT echeance.Montant, COALESCE(echeance.Partie_Versante, '') AS Partie_Versante, echeance.Date_echeance, echeance.Payée
FROM `echeance`
WHERE echeance.Id_Vente=" . $bien['Id_Vente'] . ";"
				)
				->result_array();

			$reste = $bien['Prix_Bien'] - $bien['Avance_Prix'];

			foreach ($echeances as $echeance) {
				if ($echeance['Payée'] == 'Oui') {
					$reste -= $echeance['Montant'];
				}
			}

			$output = [
				'situation' => [
					'Num_Ordre'         => $bien['Num_Ordre'],
					'Adresse'           => $bien['Adresse'],
					'Projet'            => $bien['Nom_Projet'],
					'Type'              => $bien['Type'],
					'Num_Bien'          => $bien['Num_Bien'],
					'Num_Titre_Foncier' => $bien['Num_Titre_Foncier'],
					'Prix'              => $bien['Prix_Bien'],
					'Avance'            => $bien['Avance_Prix'],
					'Reste'             => $reste,
				],
				'echeances' => $echeances,
			];
		}

		return $output;
	}

	function get_situation()
	{
		$id_client = $this->input->get('id_client');
		$id_bien   = $this->input->get('id_bien');

		echo json_encode($this->situation($id_client, $id_bien));
		return;
	}

	function generate_report()
	{
		$id_client = $this->input->post('id_client');
		$id_bien   = $this->input->post('id_bien');

		$data = $this->situation($id_client, $id_bien);

		$filename = "SITUATION_FINANCIERE_" . (new DateTime())->format("dmY_Hi") . '_' . (new DateTime())->getTimestamp() . '.pdf';
		$pdf_view = $this->load->view('pdf/situation_financiere', $data, true);

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
