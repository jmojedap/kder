<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Product_model');
        $this->load->model('Post_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }

    function pricing()
    {
        $data['products'] = $this->Product_model->products();

        $data['head_title'] = 'Precios';
        $data['view_a'] = 'products/pricing_v';
        //$data['view_a'] = 'app/message_v';
        $this->App_model->view(TPL_FRONT, $data);
    }    

    function pricing_new()
    {
        $data['status'] = 1;
        $data['message'] = 'Probando JSON';

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }    
}