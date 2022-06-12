<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('general_helper');
        $this->load->database();
        $this->load->library('grocery_CRUD');
        $this->load->library('Grocery_CRUD_MultiSearch');
        $this->template->write_view('sidenavs', 'template/default_sidenavs', true);
        $this->template->write_view('navs', 'template/default_topnavs.php', true);
    }

    function index() {
        $this->template->write('title', 'Roles', TRUE);
        $this->template->write('header', 'Roles');

        $crud = new Grocery_CRUD_MultiSearch();
        $crud->set_table('roles');
        $crud->set_subject('Role');

        $columns = ['Nom_Role','Description_Role'];
        $fields = ['Nom_Role','Description_Role','Peut_Lister','Peut_Lire','Peut_Ajouter','Peut_Modifier','Peut_Supprimer'];

        if ('read' == $crud->getState()) {
            $columns = ['Nom_Role','Description_Role','Peut_Lister','Peut_Lire','Peut_Ajouter','Peut_Modifier','Peut_Supprimer'];
        } elseif ('edit' == $crud->getState() || 'update' == $crud->getState()) {
            $fields = ['Nom_Role','Description_Role','Peut_Lister','Peut_Lire','Peut_Ajouter','Peut_Modifier','Peut_Supprimer'];
        }


	$crud->display_as('Nom_Role','Nom Role');
	$crud->display_as('Description_Role','Description Role');
	$crud->display_as('Peut_Lister','Peut Lister');
        $crud->display_as('Peut_Lire','Peut Lire');
        $crud->display_as('Peut_Ajouter','Peut Ajouter');
	$crud->display_as('Peut_Modifier','Peut Modifier');
	$crud->display_as('Peut_Supprimer','Peut Supprimer');


        $crud->columns($columns);
        $crud->fields($fields);

        if(!can_list(get_class($this))) $crud->unset_list();
        if(!can_read(get_class($this))) $crud->unset_read();
        if(!can_add(get_class($this)))  $crud->unset_add();
        if(!can_edit(get_class($this))) $crud->unset_edit();
        if(!can_delete(get_class($this))) $crud->unset_delete();

        $crud->required_fields('Nom_Role');
        // load helper for validate all integer fields
        $this->load->helper('validation_helper');
        integer_validation($crud, $this->db->field_data($crud->basic_db_table) , $crud->required_fields );

        $this->template->write_view('content', 'example', $crud->render());
        $this->template->render();
    }

}
