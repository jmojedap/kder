<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Primera función de la aplicación
     */
    function index()
    {
        if ( $this->session->userdata('logged') )
        {
            $this->logged();
        } else {
            redirect('accounts/login');
        }    
    }

    /**
     * Destinos a los que se redirige después de validar el login de usuario
     * según el rol de usuario (índice del array)
     */
    function logged()
    {
        $destination = 'accounts/login';
        if ( $this->session->userdata('logged') )
        {
            $arr_destination = array(
                0 => 'users/explore/',  //Desarrollador
                1 => 'users/explore/',  //Administrador
                11 => 'accounts/profile/', //Model
                21 => 'accounts/profile/'  //Cliente
            );
                
            $destination = $arr_destination[$this->session->userdata('role')];
        }
        
        redirect($destination);
    }

    function denied()
    {
        $data['head_title'] = 'Acceso No Permitido';
        $data['view_a'] = 'app/denied_v';

        $this->load->view('templates/bootstrap/start_v', $data);
    }
    
//GENERAL AJAX SERVICES
//---------------------------------------------------------------------------------------------------
    
    /**
     * AJAX - POST
     * Return String, with unique slut
     */
    function unique_slug()
    {
        $text = $this->input->post('text');
        $table = $this->input->post('table');
        $field = $this->input->post('field');
        
        $unique_slug = $this->Db_model->unique_slug($text, $table, $field);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output($unique_slug);
    }    

    function test()
    {
        $this->load->view('app/revslider_v');
    }

    function maps()
    {
        $this->load->view('app/map_v');
    }
}