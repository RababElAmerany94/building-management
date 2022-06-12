<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Echeance extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('general_helper');
		$this->load->database();
		$this->load->library('grocery_CRUD');
		$this->load->library('Grocery_CRUD_MultiSearch');
		$this->template->write_view('sidenavs', 'template/default_sidenavs', true);
		$this->template->write_view('navs', 'template/default_topnavs.php', true);
	}

	function index()
	{
		$this->template->write('title', 'Echeance', TRUE);
		$this->template->write('header', 'Echeance');

		$crud = new Grocery_CRUD_MultiSearch();
		$crud->set_table('echeance');
		$crud->set_subject('Echeance');

		$columns = ['Id_Client','Id_Vente', 'Montant', 'Partie_Versante', 'Payée', 'Num_Operation', 'id_Compte', 'type'];
		$fields = ['Id_Client', 'Id_Vente', 'Montant', 'Partie_Versante', 'Payée','Date_echeance', 'Num_Operation', 'id_Compte', 'type','date_Operation'];

		if ('read' == $crud->getState()) {
			$columns = ['Id_Echeance', 'Id_Client', 'Id_Vente', 'Montant', 'Partie_Versante', 'Payée','Date_echeance','date_Operation'];
		} elseif ('edit' == $crud->getState() || 'update' == $crud->getState()) {
			$fields = ['Id_Client', 'Id_Vente', 'Montant', 'Partie_Versante', 'Payée','Date_echeance','date_Operation'];
		}

		$crud->display_as('Id_Echeance', 'Id Echeance');
		$crud->display_as('Id_Client', 'Client');
		$crud->display_as('Id_Vente', 'Vente');
		$crud->display_as('Num_Operation', "Numéro d'operation");
		$crud->display_as('id_Compte', 'Compte');
		$crud->display_as('type', 'Type Operation');
		$crud->display_as('date_Operation', "Date d'Operation");
		$crud->display_as('Montant', 'Montant');
		$crud->display_as('Date_echeance', 'Date Echeance');
		$crud->display_as('Partie_Versante', 'Partie Versante');
		$crud->display_as('Payée', 'Payée');

		$crud->set_relation('Id_Vente', 'vente', 'Num_Vente');
		$crud->set_relation('id_Compte', 'comptes', '{Banque} - {RIB}');

		$projects = [];
		$query = $this
			->db
			->select('Id_Client, Nom, Prenom')//Id_Client
			->get('client');

		foreach ($query->result_array() as $row) {
			$projects[$row['Id_Client']] = $row['Prenom'] . " " . $row['Nom'];
		}

		$crud->field_type('Id_Client', 'dropdown', $projects);

		$crud->columns($columns);
		$crud->fields($fields);

		if (!can_list(get_class($this))) $crud->unset_list();
		if (!can_read(get_class($this))) $crud->unset_read();
		if (!can_add(get_class($this))) $crud->unset_add();
		if (!can_edit(get_class($this))) $crud->unset_edit();
		if (!can_delete(get_class($this))) $crud->unset_delete();

		$crud->required_fields('Id_Vente', 'Num_Operation', 'id_Compte', 'type');

		$crud->callback_column('Id_Client', [$this, 'callback_client_name']);
		$crud->callback_insert(array($this,'unset_client_callback'));
		$crud->callback_update(array($this,'unset_client_callback'));

		$this->template->write('javascript', $this->custom_javascript());
		$this->template->write_view('content', 'example', $crud->render());
		$this->template->render();
	}

	public function get_ventes()
	{
		$id_client = $this->input->get('id_client', TRUE);
		$query = $this
			->db
			->select('Id_Vente, Num_Vente, Date_Achat, Avance_Prix')
			->where('Id_Client', $id_client)
			->get('vente');

		header('Content-Type: application/json');
		echo json_encode($query->result(), true);
	}
	
	public function get_client()
	{
		$Id_Vente = $this->input->get('id_vente', TRUE);
		$query = $this
			->db
			->select('Id_Client')
			->where('Id_Vente', $Id_Vente)
			->get('vente');

		header('Content-Type: application/json');
		echo json_encode($query->row(), true);
	}

	public function unset_client_callback($post_array, $pk = null)
	{
	/*unset($post_array['Id_Client']);
	unset($post_array[0]);
	if(!isset($pk))
		return $this->db->insert('echeance',$post_array);
	else
		return $this->db->update('echeance',$post_array,array('Id_Echeance' => $pk));*/
		unset($post_array['Id_Client']);
		unset($post_array[0]);
		$var = $post_array['Date_echeance'];
		$date = str_replace('/', '-', $var);
		$date1 = date('Y-m-d H:i:s', strtotime($date));

	if(!isset($pk)){
		$data = array(
                        'Id_Vente' => $post_array['Id_Vente'],
                        'Montant' => $post_array['Montant'],
                        'Partie_Versante' => $post_array['Partie_Versante'],
                        'Payée' => $post_array['Payée'],
                        'Date_echeance' => $date1            
					);	
		return $this->db->insert('echeance',$data);
		}
	else{
			$data = array(
                        'Id_Vente' => $post_array['Id_Vente'],
                        'Montant' => $post_array['Montant'],
                        'Partie_Versante' => $post_array['Partie_Versante'],
                        'Payée' => $post_array['Payée'],
                        'Date_echeance' => $date1             
					);		
		return $this->db->update('echeance',$data,array('Id_Echeance' => $pk));
		}
	}

	public function callback_client_name($value, $row)
	{
	   $Id_Client = $this
                        ->db
                        ->select('Id_Client')
                        ->where('Id_Vente', $row->Id_Vente)
                        ->get('vente')
                        ->row()
                        ->Id_Client;

           return $this
                        ->db
                        ->select("CONCAT(Prenom,' ',Nom) AS full_name")
                        ->where('Id_Client', $Id_Client)
                        ->get('client')
                        ->row()
                        ->full_name;
	}

	public function custom_javascript()
	{
	$project_url = $this->config->base_url();

		return '
            $(document).ready(function() {
                //determine if the field_id_code is selected
                var selected_code = null;

                //first check if Nature code is selected
                if($("#field-Id_Vente").val()) {
                    selected_code = $("#field-Id_Vente").val();
                    // fetch_ventes(); // call to fetch_ventes method

                    $("#field-Id_Vente").val(selected_code);
                    $("#field-Id_Vente").trigger("chosen:updated");
                    fetch_client(); // call to fetch_client method
                } else {
                    //if ventes is not selected, clear the dropdown
                     clear_vente_dropdown();
                }

                 // make ajax request when user select another nature
                $("#field-Id_Client").change(function() {
                    selected_code = null;
                    fetch_ventes();
                });
                
                //function to clear vente dropdown
                function clear_vente_dropdown() {
                    $("#field-Id_Vente").find("option:not(:first)").remove();
                    $("#field-Id_Vente").append("<option value=0>--</option>");
                    $("#field-Id_Vente").val(0).trigger("chosen:updated");
                }

                //function to fetch client
                function fetch_client() {
                    $.ajax({
                        beforeSend : function() {
                            //before send request, hide the dropdown and display loading
                            $("#Id_Client_input_box").after(`<p class="loading">Chargement...</p>`);
                            $("#Id_Client_input_box .chosen-container").hide();
                        },
                        url :"' . $project_url . '/echeance/get_client?id_vente=" + $("#field-Id_Vente").val(),
                        success :function(response) {
                            // set the client
                            $("#field-Id_Client").val(response.Id_Client);

                            // update Jqeury Chosen dropdown
                            $("#field-Id_Client").trigger("chosen:updated");
                            
                            //set dropdown as default
                            $("#Id_Client_field_box .loading").remove()
                            $("#Id_Client_input_box .chosen-container").show();
                        }
                    });
                }

                //function to fetch vente
                function fetch_ventes() {
                    $.ajax({
                        beforeSend : function() {
                            //before send request, hide the dropdown and display loading
                            $("#Id_Vente_input_box").after(`<p class="loading">Chargement...</p>`);
                            $("#Id_Vente_input_box .chosen-container").hide();
                        },
                        url :"' . $project_url . '/echeance/get_ventes?id_client=" + $("#field-Id_Client").val(),
                        success :function(response) {
                            $("#field-Id_Vente").find("option:not(:first)").remove();
                            
                            for(var i = 0; i< response.length; i++) {
                                $("#field-Id_Vente").append("<option value=" + response[i].Id_Vente + ">Num. "+  response[i].Num_Vente +" le  "+ response[i].Date_Achat +". Avance: "+ response[i].Avance_Prix  + "Dhs</option>");
                            }
                            
                            //if response is empty  clear dropdown
                            if(response.length < 1) {
                                clear_vente_dropdown();
                            }
                            
                            //update Jqeury Chosen dropdown
                            $("#field-Id_Vente").trigger("chosen:updated");
                            
                            //set dropdown as default
                            $("#Id_Vente_field_box .loading").remove()
                            $("#Id_Vente_input_box .chosen-container").show();
                        }
                    });
                }

            });
        ';
	}
}
