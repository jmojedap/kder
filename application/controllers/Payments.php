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
            $data['options_type'] = $this->Item_model->options('category_id = 172', 'Todos los tipos');
            
        //Arrays con valores para contenido en lista
            $data['arr_status'] = $this->Item_model->arr_cod('category_id = 174');
            
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
            $data['arr_status'] = $this->Item_model->arr_cod('category_id = 174');
        
        //Preparar respuesta
            $data['html'] = $this->load->view('payments/explore/table_v', $data, TRUE);
        
        //Salida
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function get($num_page = 1)
    {
        $data = $this->Payment_model->get($num_page);

        //Salida JSON
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
            //$qty_deleted += $this->Payment_model->delete($row_id);
            //$qty_deleted .= '---' . $row_id;
            $qty_deleted++;
        }
        
        $result['status'] = 1;
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
     * Toma datos de POST y lo guarda en la tabla payment
     * 2019-12-14
     */ 
    function save($payment_id = 0)
    {
        $data = $this->Payment_model->save($payment_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * Información general del pago
     * 2019-12-14
     */
    function info($payment_id)
    {        
        $data = $this->Payment_model->basic($payment_id);
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
     * Establecer un pago como pagado, proceso simple
     * 2019-12-12
     */
    function set_payed($payment_id, $charge_id, $payment_status = 1)
    {
        $data = $this->Payment_model->set_payed($payment_id, $charge_id, $payment_status);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}