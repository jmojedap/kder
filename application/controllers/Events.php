<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Event_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($user_id)
    {
        redirect('events/explore');
    }
    
//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Exploración y búsqueda de usuarios
     */
    function explore($num_page = 1)
    {
        //Datos básicos de la exploración
            $data = $this->Event_model->explore_data($num_page);
        
        //Opciones de filtros de búsqueda
            $data['options_type'] = $this->Item_model->options('category_id = 13', 'Todos');
            
        //Arrays con valores para contenido en la tabla
            $data['arr_types'] = $this->Item_model->arr_cod('category_id = 13');
        
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX
     * Devuelve JSON, que incluye string HTML de la tabla de exploración para la
     * página $num_page, y los filtros enviados por post
     * 
     * @param type $num_page
     */
    function explore_table($num_page = 1)
    {
        //Datos básicos de la exploración
            $data = $this->Event_model->explore_table_data($num_page);
        
        //Arrays con valores para contenido en lista
            $data['arr_types'] = $this->Item_model->arr_cod('category_id = 13');
        
        //Preparar respuesta
            $data['html'] = $this->load->view('events/explore/table_v', $data, TRUE);
        
        //Salida
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
    
    /**
     * Eliminar un grupo de users selected
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        
        foreach ( $selected as $element_id ) 
        {
            $this->Event_model->delete($element_id);
        }
        
        $result['message'] = 'Cantidad seleccionados : ' . count($selected);
        $result['status'] = 1;
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($result));
    }
}