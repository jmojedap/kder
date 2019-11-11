<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Files extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('File_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
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
        //$data['nav_2'] = '';
        $data['head_subtitle'] = 'Cargar';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Formulario para recorte de archivo de imagen.
     */
    function cropping($file_id)
    {
        $data = $this->File_model->basic($file_id);

        $data['back_destination'] = base_url("files/cropping/{$file_id}");

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
     *
     * @param type $file_id
     */
    function upload()
    {
        $data = $this->File_model->upload();

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    /**
     * AJAX
     * Recorta una imagen según unos parámetros geométricos enviados por POST
     * 2019-05-21
     * 
     * @param type $file_id
     */
    function crop($file_id)
    {
        //Valor inicial por defecto
        $data = array('status' => 0, 'message' => 'No tiene permiso para modificar esta imagen');
        
        $editable = $this->File_model->editable($file_id);
        if ( $editable ) { $data = $this->File_model->crop($file_id);}
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
    
}
