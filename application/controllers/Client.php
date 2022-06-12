<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {
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
        $this->template->write('title', 'Client', TRUE);
        $this->template->write('header', 'Client');

        $crud = new Grocery_CRUD_MultiSearch();
        $crud->set_table('client');
        $crud->set_subject('Client');

        $columns = ['Nom','Prenom','CIN','Tel1','Tel2','Type_demande'];
        $fields = ['Nom','Prenom','CIN','Adresse','Tel1','Tel2','Email','Date_Naissance','Lieu_Naissance','Source','Type_demande','desc_demande'];

        if ('read' == $crud->getState()) {
            $columns = ['Nom','Prenom','CIN','Adresse','Tel1','Tel2','Email','Date_Naissance','Lieu_Naissance','Source','Type_demande','desc_demande'];
        } elseif ('edit' == $crud->getState() || 'update' == $crud->getState()) {
            $fields = ['Nom','Prenom','CIN','Adresse','Tel1','Tel2','Email','Date_Naissance','Lieu_Naissance','Source','Type_demande','desc_demande'];
        }

        $crud->display_as('Id_Client','Id Client');
	$crud->display_as('Nom','Nom');
	$crud->display_as('Prenom','Prenom');
	$crud->display_as('CIN','Cin');
	$crud->display_as('Adresse','Adresse');
	$crud->display_as('Tel1','Telephone 1');
	$crud->display_as('Tel2','Telephone 2');
	$crud->display_as('Email','Email');
	$crud->display_as('Date_Naissance','Date de naissance');
	$crud->display_as('Lieu_Naissance','Lieu de naissance');
	$crud->display_as('Source','Source');
	$crud->display_as('Type_demande','Type de demande');
	$crud->display_as('desc_demande','Description de demande');

        $crud->columns($columns);
        $crud->fields($fields);

	if(!can_list(get_class($this))) $crud->unset_list();
        if(!can_read(get_class($this))) $crud->unset_read();
        if(!can_add(get_class($this)))  $crud->unset_add();
        if(!can_edit(get_class($this))) $crud->unset_edit();
        if(!can_delete(get_class($this))) $crud->unset_delete();

        $crud->required_fields('Nom','Prenom','CIN','Adresse','Tel1','Type_demande');
        $this->template->write_view('content', 'example', $crud->render());
        $this->template->render();
    }

}
