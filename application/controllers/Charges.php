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
            //$data['arr_levels'] = $this->Item_model->arr_cod('category_id = 3');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    function get($num_page = 1)
    {
        $data = $this->Charge_model->get($num_page);

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
     * Información general del cobro
     */
    function info($charge_id)
    {        
        //Datos básicos
        $data = $this->Charge_model->basic($charge_id);
        
        //Variables específicas
        $data['view_a'] = 'charges/info_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Formulario para la creación de un nuevo cobro
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
     * Guardar un cobro, nuevo o existente
     * 2019-12-10
     */
    function save($charge_id = 0)
    {
        $data = $this->Charge_model->save($charge_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
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

// GRUPOS
//-----------------------------------------------------------------------------

    function groups($charge_id)
    {
        $data = $this->Charge_model->basic($charge_id);
        $data['view_a'] = 'charges/groups/groups_v';
        $data['nav_2'] = 'charges/menu_v';
        $data['subtitle_head'] = 'Grupos';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Todos los grupos de una institución y generación, correspondiente a un cobro
     * Si el grupo está asociado al cobro, charte_id será > 0.
     * 2019-12-10
     */
    function get_groups($charge_id)
    {
        $groups = $this->Charge_model->groups($charge_id);
        $data['list'] = $groups->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Agrega a un grupo de estudiantes a un cobro
     * 2019-12-10
     */
    function set_group($charge_id, $group_id)
    {
        $data = $this->Charge_model->set_group($charge_id, $group_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Le quita un cobro a un grupo de estudiantes
     * 2019-12-12
     */
    function unset_group($charge_id, $meta_id)
    {
        $data = $this->Charge_model->unset_group($charge_id, $meta_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function get_students_group($charge_id, $group_id)
    {
        /*$data = $this->Charge_model->basic($charge_id);
        $data['students'] = $this->Charge_model->students_group($charge_id, $group_id);
        $data_json['html'] = $this->load->view('charges/groups_students_v', $data, TRUE);*/

        $students = $this->Charge_model->students_group($charge_id, $group_id);
        $data_json['students'] = $students->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data_json));
    }
}