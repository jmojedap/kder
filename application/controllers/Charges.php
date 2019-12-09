<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Charges extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Charge_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($charge_id)
    {
        redirect("charges/info/{$charge_id}");
    }
    
//EXPLORE
//---------------------------------------------------------------------------------------------------
        
    function explore()
    {        
        //Datos básicos de la exploración
            $data = $this->Charge_model->explore_data(1);
        
        //Opciones de filtros de búsqueda
            $data['options_generation'] = $this->App_model->options_generation();
            
        //Arrays con valores para contenido en lista
            //$data['arr_levels'] = $this->Item_model->arr_cod('category_id = 172');
            
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
            $data = $this->Charge_model->explore_table_data($num_page);
        
        //Arrays con valores para contenido en lista
            $data['arr_levels'] = $this->Item_model->arr_cod('category_id = 3');
        
        //Preparar respuesta
            $data['html'] = $this->load->view('charges/explore/table_v', $data, TRUE);
        
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
            $qty_deleted += $this->Charge_model->delete($row_id);
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
     * Formulario para la creación de un nuevo cobro
     * 
     * @param type $tipo_rol
     */
    function add()
    {
        //Variables generales
            $data['head_title'] = 'Cobros';
            $data['head_subtitle'] = 'Nuevo';
            $data['nav_2'] = 'charges/explore/menu_v';
            $data['view_a'] = 'charges/add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Toma datos de POST e inserta un registro en la tabla charge. 
     * 2019-10-29
     */ 
    function insert()
    {
        $data = $this->Charge_model->insert();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * Información general del cobro
     */
    function info($charge_id)
    {        
        //Datos básicos
        $data = $this->Charge_model->basic($charge_id);

        $data['row_teacher'] = $this->Db_model->row_id('user', $data['row']->teacher_id);
        
        //Variables específicas
        $data['view_a'] = 'charges/info_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }
    
// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de un cobro.
     * 2016-11-05
     */
    function edit($charge_id)
    {
        //Datos básicos
            $data = $this->Charge_model->basic($charge_id);
        
        //Variables cargue vista
            $data['nav_2'] = 'charges/menu_v';
            $data['view_a'] = 'charges/edit_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }    

    /**
     * POST JSON
     * 
     * @param type $charge_id
     */
    function update($charge_id)
    {
        $data = $this->Charge_model->update($charge_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }



}