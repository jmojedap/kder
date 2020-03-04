<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Students extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('User_model');
        $this->load->model('Student_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($user_id = NULL)
    {
        if ( is_null($user_id) ) {
            redirect("users/explore/");
        } else {
            redirect("users/info/{$user_id}");
        }
    }
    
//EXPLORE
//---------------------------------------------------------------------------------------------------

    function explore()
    {        
        //Datos básicos de la exploración
            $data = $this->Student_model->explore_data(1);
        
        //Opciones de filtros de búsqueda
            $data['options_role'] = $this->Item_model->options('category_id = 58', 'Todos');
            
        //Arrays con valores para contenido en lista
            $data['arr_roles'] = $this->Item_model->arr_cod('category_id = 58');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Listado de usuarios filtrados por un criterio de búsqueda, páginado
     */
    function get($num_page = 1)
    {
        $data = $this->Student_model->get($num_page);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// IMPORTACIÓN DE USUARIOS
//-----------------------------------------------------------------------------

    /**
     * Mostrar formulario de importación de usuarios
     * con archivo Excel. El resultado del formulario se envía a 
     * 'users/import_e'
     */
    function import()
    {
        $data = $this->User_model->import_config('students');

        $data['url_file'] = URL_RESOURCES . 'import_templates/' . $data['template_file_name'];
        

        $data['head_title'] = 'Estudiantes';
        $data['nav_2'] = 'students/explore/menu_v';
        $data['view_a'] = 'common/import_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    //Ejecuta la importación de usuarios con archivo Excel
    function import_e()
    {
        //Proceso
        $this->load->library('excel');            
        $imported_data = $this->excel->arr_sheet_default($this->input->post('sheet_name'));
        
        if ( $imported_data['status'] == 1 )
        {
            $data = $this->Student_model->import($imported_data['arr_sheet']);
        }

        //Cargue de variables
            $data['status'] = $imported_data['status'];
            $data['message'] = $imported_data['message'];
            $data['arr_sheet'] = $imported_data['arr_sheet'];
            $data['sheet_name'] = $this->input->post('sheet_name');
            $data['back_destination'] = "users/explore/";
            $data['cf_open'] = 'users/profile/';    //Para abrir el detalle del registro importado
        
        //Cargar vista
            $data['head_title'] = 'Estudiantes';
            $data['head_subtitle'] = 'Resultado importación';
            $data['view_a'] = 'common/import_result_v';
            $data['nav_2'] = 'users/explore/menu_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }
    
// FAMILIARES DEL ESTUDIANTE
//-----------------------------------------------------------------------------

    /**
     * Familiares del estudiantes
     * 2019-11-26
     */
    function relatives($user_id)
    {
        $data = $this->User_model->basic($user_id);
        
        $data['relatives'] = $this->Student_model->relatives($user_id);

        $data['subtitle_head'] = 'Familiares';
        $data['view_a'] = 'students/relatives/relatives_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    function get_relatives($user_id)
    {
        $relatives = $this->Student_model->relatives($user_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($relatives->result()));
    }

    /**
     * Agrega un registro a la tabla user, y lo asigna como familiar de {user_id}
     * 2019-11-26
     */
    function insert_relative($user_id, $relation_type)
    {
        $data = array('status' => 0);

        $data_insert = $this->Student_model->insert();
        if ( $data_insert['saved_id'] > 0 )
        {
            $data = $this->Student_model->add_relative($user_id, $data_insert['saved_id'], $relation_type);
            $data['relative_id'] = $data_insert['saved_id'];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

}