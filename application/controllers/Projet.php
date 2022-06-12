<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Projet extends CI_Controller {
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
        $this->template->write('title', 'Projet', TRUE);
        $this->template->write('header', 'Projet');

        $crud = new Grocery_CRUD_MultiSearch();
        $crud->set_table('projet');
        $crud->set_subject('Projet');

        $columns = ['Nom_Projet','Nbr_Bien','Date_lancement'];
        $fields = ['Nom_Projet','Nbr_Bien','Adresse','Date_lancement'];

        if ('read' == $crud->getState()) {
            $columns = ['Nom_Projet','Nbr_Bien','Adresse','Date_lancement'];
        } elseif ('edit' == $crud->getState() || 'update' == $crud->getState()) {
            $fields = ['Nom_Projet','Nbr_Bien','Adresse','Date_lancement'];
        }
        
        $crud->display_as('Id_Projet','Id Projet');
			$crud->display_as('Nom_Projet','Nom du projet');
			$crud->display_as('Nbr_Bien','Nombre de biens');
			$crud->display_as('Adresse','Adresse');
			$crud->display_as('Date_lancement','Date de lancement');
        
        

        $crud->columns($columns);
        $crud->fields($fields);

	if(!can_list(get_class($this))) $crud->unset_list();
        if(!can_read(get_class($this))) $crud->unset_read();
        if(!can_add(get_class($this)))  $crud->unset_add();
        if(!can_edit(get_class($this))) $crud->unset_edit();
        if(!can_delete(get_class($this))) $crud->unset_delete();

        $crud->required_fields('Nom_Projet','Nbr_Bien','Adresse','Date_lancement');
        $this->template->write_view('content', 'example', $crud->render());
        $this->template->render();
    }

}
