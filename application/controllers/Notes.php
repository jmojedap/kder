<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notes extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Note_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($note_id)
    {
        redirect("notes/info/{$note_id}");
    }
    
//EXPLORE
//---------------------------------------------------------------------------------------------------
        
    function explore()
    {        
        //Datos básicos de la exploración
            $data = $this->Note_model->explore_data(1);
        
        //Opciones de filtros de búsqueda
            $data['options_type'] = $this->Item_model->options('category_id = 191', 'Todos');
            $data['options_status'] = $this->Item_model->options('category_id = 192', 'Todos');
            
        //Arrays con valores para contenido en lista
            $data['arr_types'] = $this->Item_model->arr_cod('category_id = 191');
            $data['arr_status'] = $this->Item_model->arr_cod('category_id = 192');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    function get($num_page = 1)
    {
        $data = $this->Note_model->get($num_page);

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
            $qty_deleted += $this->Note_model->delete($row_id);
        }
        
        $result['status'] = 1;
        $result['message'] = 'Cantidad eliminados : ' . $qty_deleted;
        $result['qty_deleted'] = $qty_deleted;
        
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }
    
    
// CRUD
//-----------------------------------------------------------------------------

    /**
     * POST JSON
     * Toma datos de POST e inserta un registro en la tabla post, tipo 1010. Devuelve
     * result del proceso en JSON
     * 2020-02-25
     */ 
    function save($note_id = 0)
    {
        $data = $this->Note_model->save($note_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * Información general del usuario
     */
    function info($note_id)
    {        
        //Datos básicos
        $data = $this->Note_model->basic($note_id);

        $data['view_a'] = 'notes/info_v';
        
        //Variables específicas
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Eliminar una anotación
     * 2020-02-26 
     */
    function delete($note_id)
    {
        $data['status'] = 0;
        $qty_deleted = $this->Note_model->delete($note_id);

        if ( $qty_deleted > 0 ) { $data['status'] = 1; }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de un user. Los datos que se
     * editan dependen de la $section elegida.
     */
    function edit($note_id)
    {
        //Datos básicos
        $data = $this->Note_model->basic($note_id);
        
        
        $data['view_a'] = 'notes/edit_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }
}