<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Payment_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($payment_id)
    {
        redirect("payments/info/{$payment_id}");
    }
    
//EXPLORE
//---------------------------------------------------------------------------------------------------
        
    function explore()
    {        
        //Datos básicos de la exploración
            $data = $this->Payment_model->explore_data(1);
        
        //Opciones de filtros de búsqueda
            $data['options_level'] = $this->Item_model->options('category_id = 3', 'Nivel escolar');
            $data['options_teacher'] = $this->App_model->options_user("role > 10 AND role < 20 AND institution_id = {$this->session->userdata('institution_id')}");
            $data['options_generation'] = $this->App_model->options_generation();
            
        //Arrays con valores para contenido en lista
            $data['arr_levels'] = $this->Item_model->arr_cod('category_id = 3');
            
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
            $data = $this->Payment_model->explore_table_data($num_page);
        
        //Arrays con valores para contenido en lista
            $data['arr_levels'] = $this->Item_model->arr_cod('category_id = 3');
        
        //Preparar respuesta
            $data['html'] = $this->load->view('payments/explore/table_v', $data, TRUE);
        
        //Salida
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Eliminar un conjunto de posts seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $qty_deleted = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $qty_deleted += $this->Payment_model->delete($row_id);
        }
        
        $result['status'] = 1;
        $result['message'] = 'Cantidad eliminados : ' . $qty_deleted;
        $result['qty_deleted'] = $qty_deleted;
        
        //Salida
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }
    
    
// CRUD
//-----------------------------------------------------------------------------

    /**
     * Formulario para la creación de un nuevo grupo
     * 
     * @param type $tipo_rol
     */
    function add()
    {
        //Variables generales
            $data['head_title'] = 'Grupos';
            $data['head_subtitle'] = 'Nuevo';
            $data['nav_2'] = 'payments/explore/menu_v';
            $data['view_a'] = 'payments/add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Toma datos de POST e inserta un registro en la tabla payment. 
     * 2019-10-29
     */ 
    function insert()
    {
        $data = $this->Payment_model->insert();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * Información general del grupo
     */
    function info($payment_id)
    {        
        //Datos básicos
        $data = $this->Payment_model->basic($payment_id);

        $data['row_teacher'] = $this->Db_model->row_id('user', $data['row']->teacher_id);
        
        //Variables específicas
        $data['view_a'] = 'payments/info_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }
    
// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de un grupo.
     * 2016-11-05
     */
    function edit($payment_id)
    {
        //Datos básicos
            $data = $this->Payment_model->basic($payment_id);
        
        //Variables cargue vista
            $data['nav_2'] = 'payments/menu_v';
            $data['view_a'] = 'payments/edit_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }    

    /**
     * POST JSON
     * 
     * @param type $payment_id
     */
    function update($payment_id)
    {
        $data = $this->Payment_model->update($payment_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }



}