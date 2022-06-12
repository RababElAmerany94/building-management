<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dashboard Controller
 */
class Dashboard extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->helper('general_helper');

        if (!is_user_logged_in()) {
            redirect('login');
        }

        $this->load->helper('url');
        $this->load->library('grocery_CRUD');
        $this->load->database();
        
        $this->template->write_view('sidenavs', 'template/default_sidenavs', true);
        $this->template->write_view('navs', 'template/default_topnavs.php', true);
    }

    function index()
    {
        $this->template->write('title', 'Tableau de Bord', TRUE);
        $this->template->write('header', 'Tableau de Bord');
        $this->template->write('style', "");
        $this->template->write_view('content', 'tes/dashboard', true);
        $this->template->render();
    }

}
