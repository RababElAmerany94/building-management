<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Paiement extends CI_Controller
{
	CONST PERIODICITE = [
		'Mensuelle'     => 1,
		'Bimensuelle'   => 2,
		'Trimestrielle' => 3,
		'Semestrielle'  => 6,
	];

	function __construct()
	{
		parent::__construct();
		$this->load->helper('general_helper');
		$this->load->database();

		$this->load->library('grocery_CRUD');
		$this->load->library('Grocery_CRUD_MultiSearch');
		// load form_validation library
		$this->load->library('form_validation');

		$this->template->write_view('sidenavs', 'template/default_sidenavs', true);
		$this->template->write_view('navs', 'template/default_topnavs.php', true);
	}

	function index()
	{
		$this->template->write('title', 'Plan de Paiement', true);
		$this->template->write('header', 'Plan de Paiement');

		$crud = new Grocery_CRUD_MultiSearch();
		$crud->set_table('paiement');
		$crud->set_subject('Plan de Paiement');

		$columns = ['Id_Client', 'Date_paiement', 'Methode', 'Periodicite', 'Montant_Echeance'];
		$fields  = ['Id_Client', 'Id_Vente', 'Date_paiement', 'Methode', 'Periodicite', 'Montant_Echeance'];

		if ('read' == $crud->getState()) {
			$columns = ['Id_Client', 'Id_Vente', 'Date_paiement', 'Methode', 'Periodicite', 'Montant_Echeance'];
		} elseif ('edit' == $crud->getState() || 'update' == $crud->getState()) {
			$fields = ['Id_Client', 'Id_Vente', 'Date_paiement', 'Methode', 'Periodicite', 'Montant_Echeance'];
		}

		$crud->display_as('Id_Vente', 'Vente');
		$crud->display_as('Id_paiement', 'Paiement');
		$crud->display_as('Id_Client', 'Client');
		$crud->display_as('Date_paiement', 'Date de 1ere Echeance');
		$crud->display_as('Methode', 'Méthode');
		$crud->display_as('Periodicite', 'Périodicité');
		$crud->display_as('Montant_Echeance', 'Montant Echéance');

		$crud->set_relation('Id_Vente', 'vente', 'Date Achat {Date_Achat} - Num {Id_Vente}');
		$crud->set_relation('Id_Client', 'client', '{Prenom} {Nom}');

		$crud->set_rules('Id_Vente', 'Vente', 'callback_check_id_bien[' . $this->input->post('Methode') . ']');
		$crud->set_rules('Periodicite', 'Periodicite', 'callback_check_periodicite[' . $this->input->post('Methode') . ']');
		$crud->set_rules('Montant_Echeance', 'Montant Echeance', 'callback_check_montant_echeance[' . $this->input->post('Methode') . ']');

		$crud->field_type('Methode', 'enum', ['Manuelle', 'Automatique']);
		$crud->field_type('Periodicite', 'enum', array_keys(self::PERIODICITE));

		$crud->columns($columns);
		$crud->fields($fields);

		if (!can_list(get_class($this))) $crud->unset_list();
		if (!can_read(get_class($this))) $crud->unset_read();
		if (!can_add(get_class($this))) $crud->unset_add();
		if (!can_edit(get_class($this))) $crud->unset_edit();
		if (!can_delete(get_class($this))) $crud->unset_delete();

		$crud->callback_after_insert([$this, 'create_echeance_after_insert']);
		$crud->callback_after_update([$this, 'create_echeance_after_update']);

		$crud->required_fields('Id_Client', 'Date_paiement', 'Methode');

		$javascript = <<<EOF
$("#field-Id_Vente")
    .find('option')
    .remove();

$("#field-Id_Client").on('change', function() {
	$.ajax({
	    beforeSend: function () {
	        // before send request, hide the dropdown and display loading
	        $("#field-Id_Vente")
	            .find('option')
	            .remove();
	        $("#Id_Vente_input_box").append('<p class="loading">Chargement...</p>');
	    },
	    url: "/paiement/get_ventes?client_id=" + $("#field-Id_Client").val(),
	    success: function (response) {
	        $("#Id_Vente_input_box .loading").remove();
			$("#field-Id_Vente").append('<option value="0" selected disabled>Sélectionner Vente</option>');
			JSON.parse(response).forEach(function(item, index) {
		        $("#field-Id_Vente").append('<option value="' + item.Id_Vente + '">Date Achat ' + item.Date_Achat + ' - Num ' + item.Id_Vente + '</option>');
			});
			$("#field-Id_Vente").trigger("chosen:updated");
	    }
	});
});
EOF;
		$this->template->write('javascript', $javascript);
		$this->template->write_view('content', 'example', $crud->render());
		$this->template->render();
	}

	function get_ventes()
	{
		$client_id = $this->input->get('client_id');
		$ventes    = $this->db
			->query("
SELECT vente.Id_Vente, vente.Date_Achat
FROM vente
WHERE vente.Id_Client = $client_id
AND NOT EXISTS (
    SELECT 1
    FROM   paiement
    WHERE  paiement.Id_Vente = vente.Id_Vente
);
			")
			->result_array();

		echo json_encode($ventes);
		return;
	}

	function check_id_bien($bien, $methode)
	{
		if ($methode == 'Automatique' && empty($bien)) {
			$this->form_validation->set_message('check_id_bien', "Le champ Vente est obligatoire");

			return false;
		}

		return true;
	}

	function check_periodicite($periodicite, $methode)
	{
		if ($methode == 'Automatique' && empty($periodicite)) {
			$this->form_validation->set_message('check_periodicite', "Le champ Periodicite est obligatoire");

			return false;
		}

		return true;
	}

	function check_montant_echeance($montant_echeance, $methode)
	{
		if ($methode == 'Automatique' && empty($montant_echeance)) {
			$this->form_validation->set_message('check_montant_echeance', "Le champ Montant Echeance est obligatoire");

			return false;
		}

		if ($methode == 'Automatique' && empty($montant_echeance)) {
			$this->form_validation->set_message('check_montant_echeance', "Le champ Montant Echeance est obligatoire");

			return false;
		}

		return true;
	}

	function create_echeance_after_insert($post_array, $primary_key)
	{
		if ($post_array['Methode'] == 'Automatique') {

			$bien = $this->db
				->query("
SELECT vente.`Id_Vente`, vente.`Id_Bien`, biens.`Prix_Bien` FROM biens
LEFT JOIN vente on vente.Id_Bien = biens.Id_Bien
AND vente.Id_Vente = " . $post_array['Id_Vente']
				)
				->row_array();

			$echeance_count = ceil($bien['Prix_Bien'] / $post_array['Montant_Echeance']);
			$nextEecheance = date_create_from_format('d/m/Y', $post_array['Date_paiement']);

			for ($i = 0; $i < $echeance_count; $i++) {
				if (0 < $i) {
					$nextEecheance = $nextEecheance->modify('+ ' . self::PERIODICITE[$post_array['Periodicite']] . ' months');
				}

				$echeance_insert = [
					"Id_Vente"      => $post_array['Id_Vente'],
					"Montant"       => $post_array['Montant_Echeance'],
					"Date_echeance" => $nextEecheance->format('Y-m-d h:i'), // Period: 1, 2, 3 ou 6 Months
					"Payée"         => 'Non',
				];

				$this->db->insert('echeance', $echeance_insert);
			}
		}

		return true;
	}


	function create_echeance_after_update($post_array, $primary_key)
	{
		return true;
	}

}
