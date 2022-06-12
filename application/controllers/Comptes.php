<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Comptes extends CI_Controller {
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
        $this->template->write('title', 'Comptes', TRUE);
        $this->template->write('header', 'Comptes');

        $crud = new Grocery_CRUD_MultiSearch();
        $crud->set_table('comptes');
        $crud->set_subject('Compte');

        $columns = ['RIB','Banque'];
        $fields = ['RIB','Banque'];

        if ('read' == $crud->getState()) {
            $columns = ['RIB','Banque'];
        } elseif ('edit' == $crud->getState() || 'update' == $crud->getState()) {
            $fields = ['RIB','Banque'];
        }
        
        $crud->display_as('Id_Compte','Id Compte');
			$crud->display_as('RIB','RIB');
			$crud->display_as('Banque','Banque');
        
        

        $crud->columns($columns);
        $crud->fields($fields);

	if(!can_list(get_class($this))) $crud->unset_list();
        if(!can_read(get_class($this))) $crud->unset_read();
        if(!can_add(get_class($this)))  $crud->unset_add();
        if(!can_edit(get_class($this))) $crud->unset_edit();
        if(!can_delete(get_class($this))) $crud->unset_delete();

        $crud->required_fields('RIB','Banque');
        $this->template->write_view('content', 'example', $crud->render());
        $this->template->render();
    }

}
