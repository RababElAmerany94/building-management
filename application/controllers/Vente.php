<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Vente extends CI_Controller
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
		$this->template->write('title', 'Vente', TRUE);
		$this->template->write('header', 'Vente');
		
		$crud = new Grocery_CRUD_MultiSearch();
		$crud->set_table('vente');
		$crud->set_subject('Vente');

		$columns = ['Id_Bien', 'Id_Client', 'Date_Achat', 'Statut'];
		$fields = ['Id_Projet', 'Id_Bloc', 'Id_Bien', 'Id_Client', 'Avance_Prix', 'Date_Achat', 'Statut', 'Num_Vente'];
		
		if ('read' == $crud->getState()) {
			$columns = ['Id_Bien', 'Id_Client', 'Avance_Prix', 'Date_Achat', 'Statut', 'Id_Client_prev', 'Id_Client_next'];
		} elseif ('edit' == $crud->getState() || 'update' == $crud->getState()) {
			$fields = ['Avance_Prix', 'Date_Achat', 'Statut', 'Id_Client_prev', 'Id_Client_next', 'Num_Vente'];
		}
		
		if ('insert' == $crud->getState() || 'update' == $crud->getState()) {
			unset($fields[0]);
			unset($fields[1]);
		}
		
		$crud->display_as('Id_Vente', 'Id Vente');
		$crud->display_as('Id_Projet', 'Projet');
		$crud->display_as('Id_Bloc', 'Bloc');
		$crud->display_as('Id_Bien', 'Bien');
		$crud->display_as('Id_Client', 'Client');
		$crud->display_as('Avance_Prix', "Montant d'avance");
		$crud->display_as('Date_Achat', "Date d'Achat");
		$crud->display_as('Statut', 'Statut');
		$crud->display_as('Id_Client_prev', 'Client précédent');
		$crud->display_as('Id_Client_next', 'Client suivant');
		$crud->display_as('Num_Vente', 'Num. de Vente');
		
		$crud->set_relation('Id_Client', 'client', '{Prenom} {Nom} - {CIN}');
		$crud->set_relation('Id_Client_prev', 'client', '{Prenom} {Nom}');
		$crud->set_relation('Id_Client_next', 'client', '{Prenom} {Nom}');
		
		if ('list' == $crud->getState() || 'success' == $crud->getState()) {
			$crud->callback_column('Id_Bien', [$this, '_callback_bien_value']);
		} else {
			$crud->set_relation('Id_Bien', 'biens', 'Etage {Etage} - Num {Num}');
			$crud->callback_read_field('Id_Projet', [$this, '_callback_projet_value']);
			$crud->callback_read_field('Id_Bloc', [$this, '_callback_bloc_value']);
		}
		
		$crud->columns($columns);
		$crud->fields($fields);
		
		if (!can_list(get_class($this))) $crud->unset_list();
		if (!can_read(get_class($this))) $crud->unset_read();
		if (!can_add(get_class($this))) $crud->unset_add();
		if (!can_edit(get_class($this))) $crud->unset_edit();
		if (!can_delete(get_class($this))) $crud->unset_delete();
		
		$crud->required_fields('Id_Bien', 'Id_Client', 'Avance_Prix', 'Date_Achat');
		
		$projects = [];
		$query = $this
			->db
			->select('Id_Projet, Nom_Projet')
			->get('projet');
		
		foreach ($query->result_array() as $row) {
			$projects[$row['Id_Projet']] = $row['Nom_Projet'];
		}
		
		$crud->field_type('Id_Projet', 'dropdown', $projects);
		$crud->field_type('Id_Bloc', 'dropdown', []);
		
		$crud->callback_before_insert([$this, 'remove_extra_fields_insert_callback']);
		$crud->callback_before_update([$this, 'remove_extra_fields_update_callback']);
		
		//load custom javascript
		$this->template->write('javascript', $this->custom_javascript());
		$this->template->write_view('content', 'example', $crud->render());
		$this->template->render();
	}
	
	public function _callback_bien_value($value, $row)
	{
		//Projet {Projet} - Bloc {Bloc} - Bien {Num} - Etage {Etage}
		$bien = $this->db
			->query("
SELECT projet.Nom_Projet, blocs.Nom_Bloc, biens.Num, biens.Etage
FROM biens
JOIN blocs on blocs.Id_Bloc = biens.Id_Block
JOIN projet on projet.Id_Projet = blocs.Id_Projet
AND biens.Id_Bien = {$value}"
			)
			->row_array();
		
		return "Projet {$bien['Nom_Projet']} - Bloc {$bien['Nom_Bloc']} - Bien {$bien['Num']} - Etage {$bien['Etage']}";
	}
	
	public function _callback_projet_value($value, $primary_key)
	{
		$bien = $this->db
			->query("
SELECT projet.Nom_Projet
FROM vente
JOIN biens on biens.Id_Bien = vente.Id_Bien
JOIN blocs on blocs.Id_Bloc = biens.Id_Block
JOIN projet on projet.Id_Projet = blocs.Id_Projet
AND vente.Id_Vente = {$primary_key}"
			)
			->row_array();
		
		return "Projet {$bien['Nom_Projet']}";
	}
	
	public function _callback_bloc_value($value, $primary_key)
	{
		$bien = $this->db
			->query("
SELECT blocs.Nom_Bloc
FROM vente
JOIN biens on biens.Id_Bien = vente.Id_Bien
JOIN blocs on blocs.Id_Bloc = biens.Id_Block
JOIN projet on projet.Id_Projet = blocs.Id_Projet
AND vente.Id_Vente = {$primary_key}"
			)
			->row_array();
		
		return "Bloc {$bien['Nom_Bloc']}";
	}
	
	function remove_extra_fields_callback($post_array, $primary_key)
	{
		unset($post_array['Id_Projet']);
		unset($post_array['Id_Bloc']);
		
		return $post_array;
	}
	
	function remove_extra_fields_insert_callback($post_array)
	{
		$this->remove_extra_fields_callback($post_array, null);
	}
	
	function remove_extra_fields_update_callback($post_array, $primary_key)
	{
		$this->remove_extra_fields_callback($post_array, $primary_key);
	}
	
	public function get_biens()
	{
		$id_bloc = $this->input->get('id_bloc', TRUE);
		$query = $this
			->db
			->select('Id_Bien, Num, Etage')
			->where('Id_Block', $id_bloc)
			->get('biens');
		
		header('Content-Type: application/json');
		echo json_encode($query->result(), true);
	}
	
	public function get_blocs()
	{
		$id_projet = $this->input->get('id_projet', TRUE);
		$query = $this
			->db
			->select('Id_Bloc, Nom_Bloc')
			->where('Id_Projet', $id_projet)
			->get('blocs');
		
		header('Content-Type: application/json');
		echo json_encode($query->result(), true);
	}
	
	public function get_bloc_blocs_project()
	{
		$id_bien = $this->input->get('id_bien', TRUE);
		$bien = $this
			->db
			->select('Id_Block')
			->where('Id_Bien', $id_bien)
			->get('biens')
			->row();
		$bloc = $this
			->db
			->select('Id_Projet')
			->where('Id_Bloc', $bien->Id_Block)
			->get('blocs')
			->row();
		$blocs = $this
			->db
			->select('Id_Bloc, Nom_Bloc')
			->where('Id_Projet', $bloc->Id_Projet)
			->get('blocs')
			->result();
		
		header('Content-Type: application/json');
		echo json_encode(
			[
				'Id_Bloc'   => $bien->Id_Block,
				'Id_Projet' => $bloc->Id_Projet,
				'blocs'     => $blocs,
			],
			true
		);
	}
	
	public function custom_javascript()
	{
		$project_url = $this->config->base_url();
		
		return '
            $(document).ready(function() {
                //determine if the field_id_bien is selected
                var selected_bien = null;
                //determine if the field_id_bloc is selected
                var selected_bloc = null;
                
                //first check if Nature code is selected
                if($("#field-Id_Bien").val()) {
                    selected_bien = $("#field-Id_Bien").val();

                    $("#field-Id_Bien").val(selected_bien);
                    $("#field-Id_Bien").trigger("chosen:updated");

                    fetch_bloc_blocs_project(); // call to fetch_bloc_blocs_project method
                } else {
                    //if blocs is not selected, clear the dropdown
                     clear_bien_dropdown();
                     clear_bloc_dropdown();
                }

				// make ajax request when user select another nature
                $("#field-Id_Projet").change(function() {
                	selected_bloc = null;
                    fetch_blocs();
                });

                // make ajax request when user select another nature
                $("#field-Id_Bloc").change(function() {
	                selected_bien = null;
                    fetch_biens();
                });
                
                //function to clear bloc dropdown
                function clear_bien_dropdown() {
                    $("#field-Id_Bien").find("option:not(:first)").remove();
                    $("#field-Id_Bien").append("<option value=0>--</option>");
                    $("#field-Id_Bien").val(0).trigger("chosen:updated");
                }

                //function to clear bloc dropdown
                function clear_bloc_dropdown() {
                    $("#field-Id_Bloc").find("option:not(:first)").remove();
                    $("#field-Id_Bloc").append("<option value=0>--</option>");
                    $("#field-Id_Bloc").val(0).trigger("chosen:updated");
                }

                //function to fetch bloc
                function fetch_bloc_blocs_project() {
                	$.ajax({
                        beforeSend : function() {
                            //before send request, hide the dropdown and display loading
                            $("#Id_Bloc_input_box").after(`<p class="loading">chargement...</p>`);
                            $("#Id_Bloc_input_box .chosen-container").hide();
                            
                            $("#Id_Projet_input_box").after(`<p class="loading">chargement...</p>`);
                            $("#Id_Projet_input_box .chosen-container").hide();
                        },
                        url :"' . $project_url . 'vente/get_bloc_blocs_project?id_bien=" + $("#field-Id_Bien").val(),
                        success :function(response) {
                        	$("#field-Id_Bloc").find("option:not(:first)").remove();
                        	
                        	var blocs = response.blocs;
                            
                            for(var i = 0; i < blocs.length; i++) {
                                $("#field-Id_Bloc").append("<option value=" + blocs[i].Id_Bloc + ">"+  blocs[i].Nom_Bloc  + "</option>");
                            }
                            
                        	// set the bloc
                            $("#field-Id_Bloc").val(response.Id_Bloc);
                            
                        	// set the project
                            $("#field-Id_Projet").val(response.Id_Projet);

                            // update Jqeury Chosen dropdown
                            $("#field-Id_Bloc").trigger("chosen:updated");
                            $("#field-Id_Projet").trigger("chosen:updated");
                            
                            //set dropdown as default
                            $("#Id_Bloc_field_box .loading").remove()
                            $("#Id_Bloc_input_box .chosen-container").show();
                            
                            $("#Id_Projet_field_box .loading").remove()
                            $("#Id_Projet_input_box .chosen-container").show();
                        }
                    });
                }
                
                //function to fetch blocs
                function fetch_blocs() {
                    $.ajax({
                        beforeSend : function() {
                            //before send request, hide the dropdown and display loading
                            $("#Id_Bloc_input_box").after(`<p class="loading">chargement...</p>`);
                            $("#Id_Bloc_input_box .chosen-container").hide();
                        },
                        url :"' . $project_url . 'vente/get_blocs?id_projet=" + $("#field-Id_Projet").val(),
                        success :function(response) {
                            $("#field-Id_Bloc").find("option:not(:first)").remove();
                            
                            for(var i = 0; i < response.length; i++) {
                                $("#field-Id_Bloc").append("<option value=" + response[i].Id_Bloc + ">"+  response[i].Nom_Bloc  + "</option>");
                            }
                            
                            //if response is empty  clear dropdown
                            if(response.length < 1) {
                                clear_bloc_dropdown();
                            }
                            
                            //update Jqeury Chosen dropdown
                            $("#field-Id_Bloc").trigger("chosen:updated");
                            
                            //set dropdown as default
                            $("#Id_Bloc_field_box .loading").remove()
                            $("#Id_Bloc_input_box .chosen-container").show();
                        }
                    });
                }
                
                //function to fetch blocs
                function fetch_biens() {
                    $.ajax({
                        beforeSend : function() {
                            //before send request, hide the dropdown and display loading
                            $("#Id_Bien_input_box").after(`<p class="loading">chargement...</p>`);
                            $("#Id_Bien_input_box .chosen-container").hide();
                        },
                        
                        url :"' . $project_url . 'vente/get_biens?id_bloc=" + $("#field-Id_Bloc").val(),
                        
                        success :function(response) {
                            $("#field-Id_Bien").find("option:not(:first)").remove();
                            
                            for(var i = 0; i< response.length; i++) {
                                $("#field-Id_Bien").append("<option value=" + response[i].Id_Bien + ">Etage " + response[i].Etage + " - Num " + response[i].Num  + "</option>");
                            }

                            //if response is empty  clear dropdown
                            if(response.length < 1) {
                                clear_bien_dropdown();
                            }
                            
                            //update Jqeury Chosen dropdown
                            $("#field-Id_Bien").trigger("chosen:updated");
                            
                            //set dropdown as default
                            $("#Id_Bien_field_box .loading").remove();
                            $("#Id_Bien_input_box .chosen-container").show();
                        }
                    });
                }
            });
        ';
	}
}
