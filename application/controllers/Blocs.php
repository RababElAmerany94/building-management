<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Blocs extends CI_Controller {
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
        $this->template->write('title', 'Blocs', TRUE);
        $this->template->write('header', 'Blocs');

        $crud = new Grocery_CRUD_MultiSearch();
        $crud->set_table('blocs');
        $crud->set_subject('Bloc');

        $columns = ['Id_Projet','Nom_Bloc'];
        $fields = ['Id_Projet','Nom_Bloc'];

        if ('read' == $crud->getState()) {
            $columns = ['Id_Projet','Nom_Bloc'];
        } elseif ('edit' == $crud->getState() || 'update' == $crud->getState()) {
            $fields = ['Id_Projet','Nom_Bloc'];
        }

        $crud->display_as('Id_Bloc','Id Bloc');
	$crud->display_as('Id_Projet','Projet');
	$crud->display_as('Nom_Bloc','Nom Bloc');

        $crud->set_relation('Id_Projet','projet','{Nom_Projet}');

        $crud->columns($columns);
        $crud->fields($fields);

	if(!can_list(get_class($this))) $crud->unset_list();
        if(!can_read(get_class($this))) $crud->unset_read();
        if(!can_add(get_class($this)))  $crud->unset_add();
        if(!can_edit(get_class($this))) $crud->unset_edit();
        if(!can_delete(get_class($this))) $crud->unset_delete();

        $crud->required_fields('Id_Projet','Nom_Bloc');
        $this->template->write_view('content', 'example', $crud->render());
        $this->template->render();
    }

}
