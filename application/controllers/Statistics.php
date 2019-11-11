<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Statistics extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Statistic_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }

    function girls()
    {
        $data['head_title'] = 'Girls';
        $data['head_subtitle'] = 'Visitas por perfil';
        $data['girls'] = $this->Statistic_model->girls();
        $data['view_a'] = 'statistics/girls/girls_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    function albums()
    {
        $this->load->model('File_model');

        $data['head_title'] = 'Álbums';
        $data['head_subtitle'] = 'Visitas por álbum';
        $data['albums'] = $this->Statistic_model->albums();
        $data['view_a'] = 'statistics/albums/albums_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }
}