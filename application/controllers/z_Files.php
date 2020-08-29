<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Files extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('File_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }

//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /** Exploración de Posts */
    function explore()
    {        
        //Datos básicos de la exploración
            $data = $this->File_model->explore_data(1);
        
        //Opciones de filtros de búsqueda
            $data['options_type'] = $this->Item_model->options('category_id = 33', 'Todos');
            
        //Arrays con valores para contenido en lista
            $data['arr_types'] = $this->Item_model->arr_cod('category_id = 33');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Listado de Posts, filtrados por búsqueda, JSON
     */
    function get($num_page = 1)
    {
        $data = $this->File_model->get($num_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Eliminar un conjunto de posts seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['quan_deleted'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $data['quan_deleted'] += $this->File_model->delete($row_id);
        }

        //Establecer resultado
        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// CRUD
//-----------------------------------------------------------------------------

    function info($file_id)
    {
        $data = $this->File_model->basic($file_id);
        $data['view_a'] = 'files/info_v';
        $data['nav_2'] = 'files/menu_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Formulario para la edición del registro de un archivo, tabla file
     * 2020-03-17
     */
    function edit($file_id)
    {
        $data = $this->File_model->basic($file_id);

        $data['view_a'] = 'files/edit_v';
        $data['nav_2'] = 'files/menu_v';
        $data['subtitle_head'] = 'Editar';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Actualizar un registro en la tabla file
     * 2020-03-12
     */
    function update($file_id)
    {
        $data = $this->File_model->update($file_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// VISTAS GESTIÓN DE ARCHIVOS
//-----------------------------------------------------------------------------

    /**
     * Formulario para cargar un archivo al servidor de la aplicación.
     */
    function add()
    {
        $data['head_title'] = 'Archivos';
        $data['view_a'] = 'files/add_v';
        $data['nav_2'] = 'files/explore/menu_v';
        $data['head_subtitle'] = 'Cargar';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Formulario para recorte de archivo de imagen.
     */
    function cropping($file_id)
    {
        $data = $this->File_model->basic($file_id);

        $data['back_destination'] = "files/info/{$file_id}";

        $data['view_a'] = 'files/cropping_v';
        $data['nav_2'] = 'files/menu_v';
        $data['head_subtitle'] = 'Recortar';
        $this->App_model->view(TPL_ADMIN, $data);
    }
    
// API
//-----------------------------------------------------------------------------

    /**
     * AJAX JSON
     * 
     * Carga un archivo en la ruta "content/uploads/{year}/}{month}/"
     * Crea registro de ese arhivo en la tabla file
     */
    function upload()
    {
        $data = $this->File_model->upload();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX
     * Recorta una imagen según unos parámetros geométricos enviados por POST
     * 2019-05-21
     */
    function crop($file_id)
    {
        //Valor inicial por defecto
        $data = array('status' => 0, 'message' => 'No tiene permiso para modificar esta imagen');
        
        $editable = $this->File_model->editable($file_id);
        if ( $editable ) { $data = $this->File_model->crop($file_id);}
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
}
