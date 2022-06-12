<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Biens extends CI_Controller
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
		$this->template->write('title', 'Biens', true);
		$this->template->write('header', 'Biens');
		
		$crud = new Grocery_CRUD_MultiSearch();
		$crud->set_table('biens');
		$crud->set_subject('Bien');
		
		// Projet | Bloc | Etage | Num Bien | Type | Prix
		
		$columns = ['Id_Projet', 'Id_Block', 'Etage', 'Num', 'Type', 'Prix_Bien'];
		$fields = ['Id_Projet', 'Id_Block', 'Num_Titre_Foncier', 'Etage', 'Num', 'Type', 'Surface', 'Prix_Bien', 'Num_Ordre'];
		
		if ('read' == $crud->getState()) {
			$columns = ['Id_Projet', 'Id_Block', 'Num_Titre_Foncier', 'Etage', 'Num', 'Type', 'Surface', 'Prix_Bien'];
		} elseif ('edit' == $crud->getState() || 'update' == $crud->getState()) {
			$fields = ['Id_Projet', 'Id_Block', 'Num_Titre_Foncier', 'Etage', 'Num', 'Type', 'Surface', 'Prix_Bien', 'Num_Ordre'];
		}
		
		$crud->display_as('Id_Projet', 'Projet');
		$crud->display_as('Id_Bien', 'Id Bien');
		$crud->display_as('Id_Block', 'Bloc');
		$crud->display_as('Num_Titre_Foncier', 'Num titre foncier');
		$crud->display_as('Type', 'Type');
		$crud->display_as('Surface', 'Surface');
		$crud->display_as('Etage', 'Etage');
		$crud->display_as('Prix_Bien', 'Prix Bien');
		
		$crud->set_relation('Id_Block', 'blocs', '{Nom_Bloc}');
		$crud->display_as('Num_Ordre', 'Num d\'Ordre');
		
		$projects = [];
		$query = $this
			->db
			->select('Id_Projet, Nom_Projet')
			->get('projet');
		
		foreach ($query->result_array() as $row) {
			$projects[$row['Id_Projet']] = $row['Nom_Projet'];
		}
		
		$crud->field_type('Id_Projet', 'dropdown', $projects);
		
		$crud->columns($columns);
		$crud->fields($fields);
		
		if (!can_list(get_class($this))) $crud->unset_list();
		if (!can_read(get_class($this))) $crud->unset_read();
		if (!can_add(get_class($this))) $crud->unset_add();
		if (!can_edit(get_class($this))) $crud->unset_edit();
		if (!can_delete(get_class($this))) $crud->unset_delete();
		
		$crud->required_fields('Id_Block', 'Num_Titre_Foncier', 'Num', 'Type', 'Surface', 'Etage');
		
		$crud->callback_column('Id_Projet', [$this, 'callback_project_name']);
		$crud->callback_insert([$this, 'unset_project_callback']);
		$crud->callback_update([$this, 'unset_project_callback']);
		
		//load custom javascript
		$this->template->write('javascript', $this->custom_javascript());
		$this->template->write_view('content', 'example', $crud->render());
		$this->template->render();
	}
	
	public function callback_project_name($value, $row)
	{
		$Id_Projet = $this
			->db
			->select('Id_Projet')
			->where('Id_Bloc', $row->Id_Block)
			->get('blocs')
			->row()
			->Id_Projet;
		
		return $this
			->db
			->select('Nom_Projet')
			->where('Id_Projet', $Id_Projet)
			->get('projet')
			->row()
			->Nom_Projet;
	}
	
	public function unset_project_callback($post_array, $pk = null)
	{
		unset($post_array['Id_Projet']);
		unset($post_array[0]);
		// Some bug right here
		if (!isset($pk)) {
			return $this->db->insert('biens', $post_array);
		} else {
			return $this->db->update('biens', $post_array, ['Id_Bien' => $pk]);
		}
	}
	
	public function get_blocks()
	{
		$id_projet = $this->input->get('id_projet', true);
		$query = $this
			->db
			->select('Id_Bloc, Nom_Bloc')
			->where('Id_Projet', $id_projet)
			->get('blocs');
		
		header('Content-Type: application/json');
		echo json_encode($query->result(), true);
	}
	
	public function get_project()
	{
		$Id_Bloc = $this->input->get('id_block', true);
		$query = $this
			->db
			->select('Id_Projet')
			->where('Id_Bloc', $Id_Bloc)
			->get('blocs');
		
		header('Content-Type: application/json');
		echo json_encode($query->row(), true);
	}
	
	public function custom_javascript()
	{
		$project_url = $this->config->base_url();
		
		return '
            $(document).ready(function() {
                //determine if the field_id_code is selected
                var selected_code = null;

                //first check if Nature code is selected
                if($("#field-Id_Block").val()) {
                    selected_code = $("#field-Id_Block").val();
                    // fetch_blocks(); // call to fetch_blocks method

                    $("#field-Id_Block").val(selected_code);
                    $("#field-Id_Block").trigger("chosen:updated");
                    fetch_project(); // call to fetch_project method
                } else {
                    //if blocks is not selected, clear the dropdown
                     clear_block_dropdown();
                }

                // make ajax request when user select another nature
                $("#field-Id_Projet").change(function() {
	                selected_code = null;
                    fetch_blocks();
                });
                
                //function to clear block dropdown
                function clear_block_dropdown() {
                    $("#field-Id_Block").find("option:not(:first)").remove();
                    $("#field-Id_Block").append("<option value=0>--</option>");
                    $("#field-Id_Block").val(0).trigger("chosen:updated");
                }

                //function to fetch project
                function fetch_project() {
                	$.ajax({
                        beforeSend : function() {
                            //before send request, hide the dropdown and display loading
                            $("#Id_Projet_input_box").after(`<p class="loading">chargement...</p>`);
                            $("#Id_Projet_input_box .chosen-container").hide();
                        },
                        url :"' . $project_url . '/biens/get_project?id_block=" + $("#field-Id_Block").val(),
                        success :function(response) {
                        	// set the project
                            $("#field-Id_Projet").val(response.Id_Projet);

                            // update Jqeury Chosen dropdown
                            $("#field-Id_Projet").trigger("chosen:updated");
                            
                            //set dropdown as default
                            $("#Id_Projet_field_box .loading").remove()
                            $("#Id_Projet_input_box .chosen-container").show();
                        }
                    });
                }
                
                //function to fetch blocks
                function fetch_blocks() {
                    $.ajax({
                        beforeSend : function() {
                            //before send request, hide the dropdown and display loading
                            $("#Id_Block_input_box").after(`<p class="loading">chargement...</p>`);
                            $("#Id_Block_input_box .chosen-container").hide();
                        },
                        url :"' . $project_url . '/biens/get_blocks?id_projet=" + $("#field-Id_Projet").val(),
                        success :function(response) {
                            $("#field-Id_Block").find("option:not(:first)").remove();
                            
                            for(var i = 0; i< response.length; i++) {
                                $("#field-Id_Block").append("<option value=" + response[i].Id_Bloc + ">"+  response[i].Nom_Bloc  + "</option>");
                            }
                            
                            //if response is empty  clear dropdown
                            if(response.length < 1) {
                                clear_block_dropdown();
                            }
                            
                            //update Jqeury Chosen dropdown
                            $("#field-Id_Block").trigger("chosen:updated");
                            
                            //set dropdown as default
                            $("#Id_Block_field_box .loading").remove()
                            $("#Id_Block_input_box .chosen-container").show();
                        }
                    });
                }
            });
        ';
	}
}
