<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('general_helper');
	$this->load->database();
        $this->load->library('form_validation');
        $this->load->dbutil();
    }

    function index()
    {
        $this->template->set_template('login');
        $this->template->write('title', 'Authentification', TRUE);
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $error = 'Utilisateur ou Mot de Passe incorrect.';

        if('POST' == $this->input->server('REQUEST_METHOD') && (!empty($username)) && (!empty($password))) {
                $this->db->where("Nom_Utilisateur", $username);
                $user = $this->db->get("utilisateurs")->row();

                if($user && pwd_verify($password, $user->Mot_De_Passe)) {

                            $this->db->where("Id_Role", $user->Id_Role);
                            $role = $this->db->get("roles")->row();

                            $can_list = explode(",",$role->Peut_Lister);
                            $can_read = explode(",",$role->Peut_Lire);
                            $can_add = explode(",",$role->Peut_Ajouter);
                            $can_edit = explode(",",$role->Peut_Modifier);
                            $can_delete = explode(",",$role->Peut_Supprimer);

                            array_walk($can_list,"canonize_class");
                            array_walk($can_read,"canonize_class");
                            array_walk($can_add,"canonize_class");
                            array_walk($can_edit,"canonize_class");
                            array_walk($can_delete,"canonize_class");

                            $user_data = [
                                'user_id' => $user->Id_Utilisateur,
                                'username' => $user->Nom_Utilisateur,
                                'password' => $user->Mot_De_Passe,
                                'first_name' => $user->Prenom,
                                'last_name' => $user->Nom,
                                'email' => $user->Email,
                                'login_ip' => $_SERVER["REMOTE_ADDR"],
                                'logged_in' => TRUE,
                                'can_list' => $can_list,
                                'can_read' => $can_read,
                                'can_add' => $can_add,
                                'can_edit' => $can_edit,
                                'can_delete' => $can_delete
                            ];

                            $this->session->set_userdata($user_data);
                            $this->db->set('last_login_ip', '"' . $_SERVER["REMOTE_ADDR"] . '"', FALSE);
                            $this->db->where('Id_Utilisateur', $user->Id_Utilisateur);
                            $this->db->update('utilisateurs');

                            redirect('client');
                } else {
                    $this->template->write('error', $error, TRUE);
                }
        }
        /*
        $dbs = $this->dbutil->list_databases();
        array_shift($dbs); // getting rid of information_schema
        $data['dbs'] = $dbs;
        $this->load->vars($data);
        */
        $this->template->render();
    }

    /*
     * User logout
     */
    public function logout()
    {
        $this->session->unset_userdata('user_logged_in');
        $this->session->sess_destroy();
        redirect('login');
    }
}
