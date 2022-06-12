<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Utilisateurs extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('general_helper');
        $this->load->library('grocery_CRUD');
        $this->load->library('Grocery_CRUD_MultiSearch');
        $this->load->database();
        $this->template->write_view('sidenavs', 'template/default_sidenavs', true);
        $this->template->write_view('navs', 'template/default_topnavs.php', true);
    }

    function index()
    {
        $this->template->write('title', 'Utilisateurs', TRUE);
        $this->template->write('header', 'Utilisateurs');

        $crud = new Grocery_CRUD_MultiSearch();
        $crud->set_table('utilisateurs');
        $crud->set_subject('Utilisateur');

        $columns = ['Nom_Utilisateur', 'Id_Role', 'Prenom', 'Nom', 'Email', 'Telephone_1'];
        $fields = ['Nom_Utilisateur', 'Mot_De_Passe', 'Id_Role', 'Prenom', 'Nom', 'Email', 'Telephone_1', 'Telephone_2', 'Adresse', 'Ville', 'Code_Postal'];

        if ($crud->getState() != 'edit' && $crud->getState() != 'update') {
            if (!can_list(get_class($this))) $crud->unset_list();
            if (!can_read(get_class($this))) $crud->unset_read();
            if (!can_add(get_class($this))) $crud->unset_add();
            if (!can_edit(get_class($this))) $crud->unset_edit();
            if (!can_delete(get_class($this))) $crud->unset_delete();
        }

        switch ($crud->getState()) {
            case 'read':
                $fields = ['Nom_Utilisateur', 'Id_Role', 'Prenom', 'Nom', 'Email', 'Telephone_1', 'Telephone_2', 'Adresse', 'Ville', 'Code_Postal', 'last_login', 'last_login_ip'];
                break;
            case 'edit':
                $fields = ['Nom_Utilisateur', 'Mot_De_Passe', 'Id_Role', 'Prenom', 'Nom', 'Email', 'Telephone_1', 'Telephone_2', 'Adresse', 'Ville', 'Code_Postal'];
                $crud->required_fields('Nom_Utilisateur', 'Prenom', 'Nom', 'Telephone_1');
                $this->session->set_flashdata('edit_user_id', $crud->getStateInfo()->primary_key);

                if ($this->session->userdata()['user_id'] == $crud->getStateInfo()->primary_key && !can_edit(get_class($this))) {
                    unset($fields[array_search('Id_Role', $fields)]);
                    $crud->unset_back_to_list();
                } else {
                    if (!can_list(get_class($this))) $crud->unset_list();
                    if (!can_read(get_class($this))) $crud->unset_read();
                    if (!can_add(get_class($this))) $crud->unset_add();
                    if (!can_edit(get_class($this))) $crud->unset_edit();
                    if (!can_delete(get_class($this))) $crud->unset_delete();
                }
            case 'update':
                $this->session->set_flashdata('edit_user_id', $crud->getStateInfo()->primary_key);
                break;
            case 'add':
                $crud->required_fields('Nom_Utilisateur', 'Mot_De_Passe', 'Prenom', 'Nom', 'Telephone_1');
                break;
        }

        $crud->display_as('Id_Role', 'Role');
        $crud->display_as('Nom_Utilisateur', 'Nom Utilisateur');
        $crud->display_as('Mot_De_Passe', 'Mot De Passe');
        $crud->display_as('Prenom', 'Prenom');
        $crud->display_as('Nom', 'Nom');
        $crud->display_as('Email', 'Email');
        $crud->display_as('Telephone_1', 'Telephone 1');
        $crud->display_as('Telephone_2', 'Telephone 2');
        $crud->display_as('Adresse', 'Adresse');
        $crud->display_as('Ville', 'Ville');
        $crud->display_as('Code_Postal', 'Code Postal');
        $crud->display_as('last_login', 'Dernière Connexion');
        $crud->display_as('last_login_ip', 'Connexion Depuis');

        $crud->set_relation('Id_Role', 'roles', 'Nom_Role');

        $crud->columns($columns);
        $crud->fields($fields);

        $crud->callback_field('Mot_De_Passe', array($this, 'set_password_input_to_empty'));
        $crud->callback_before_insert(array($this, 'encrypt_password_before_insert_callback'));
        $crud->callback_before_update(array($this, 'encrypt_password_callback'));

        // load helper for validate all integer fields
        $this->load->helper('validation_helper');
        integer_validation($crud, $this->db->field_data($crud->basic_db_table), $crud->required_fields);

        $this->template->write_view('content', 'example', $crud->render());
        $this->template->render();
    }

    function encrypt_password_before_insert_callback($post_array)
    {
        $post_array['Mot_De_Passe'] = pwd_hash(trim(strip_tags($post_array['Mot_De_Passe'])), 'md5', 2);

        return $post_array;
    }

    function encrypt_password_callback($post_array)
    {
        $this->load->helper('general_helper');

        //Encrypt password only if is not empty. Else don't change the password to an empty field
        $this->db->where('Id_Utilisateur', $this->session->flashdata('edit_user_id'));
        $utilisateur = $this->db->get('utilisateurs')->row();

        if ($utilisateur) {
            if (!empty($post_array['Mot_De_Passe']) && pwd_hash(strip_tags($post_array['Mot_De_Passe']), 'md5', 2) != $utilisateur->Mot_De_Passe) {
                $post_array['Mot_De_Passe'] = pwd_hash(strip_tags($post_array['Mot_De_Passe']), 'md5', 2);
            } else {
                $post_array['Mot_De_Passe'] = $utilisateur->Mot_De_Passe;
            }
        }

        return $post_array;
    }

    function set_password_input_to_empty($value = '', $primary_key = null)
    {
        return '<input id="field-Mot_De_Passe" class="form-control" name="Mot_De_Passe" type="password" value="" style="width: 100%;">';
    }
}
