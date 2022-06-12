<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Promotion extends CI_Controller
{
    function __construct()
    {
        set_time_limit(0);
        parent::__construct();
        
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('grocery_CRUD');
        
        $this->template->write_view('sidenavs', 'template/default_sidenavs', true);
        $this->template->write_view('navs', 'template/default_topnavs.php', true);
    }
    
    function index(){
         $this->template->write('title', 'Coupon_campaigns', TRUE);
        $this->template->write('header', 'Coupon_campaigns');

        $crud = new grocery_CRUD();
        $crud->set_table('coupon_campaigns');
        $crud->set_subject('Coupon_campaigns');

        $columns = ['campaign_id','name','start_date','end_date','Campaign_code'];
        $fields = ['campaign_id','name','start_date','end_date','Campaign_code'];

        if ('read' == $crud->getState()) {
            $columns = ['campaign_id','name','start_date','end_date','Campaign_code'];
        } elseif ('edit' == $crud->getState() || 'update' == $crud->getState()) {
            $fields = ['campaign_id','name','start_date','end_date','Campaign_code'];
        }

        $crud->display_as('campaign_id','Id Campaign');
        $crud->display_as('name','Nom');
        $crud->display_as('start_date','Date début');
        $crud->display_as('end_date','Date fin');
        $crud->display_as('Campaign_code','Compaign Code');

        $crud->columns($columns);
        $crud->fields($fields);

        $crud->callback_add_field('Campaign_code',array($this,'only_alphabet'));

   
        //$crud->required_fields('Id_Projet','Nom_Bloc');
        $this->template->write_view('content', 'example', $crud->render());
        $this->template->render();
    }

    function only_alphabet()
{
    return '<input type="text" name="Campaign_code" pattern="[a-zA-Z]{1,}">';
}
}
