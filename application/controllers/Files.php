<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Files extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('File_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }

//EXPLORE
//---------------------------------------------------------------------------------------------------
                
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

    function get($num_page = 1)
    {
        $data = $this->File_model->get($num_page);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Eliminar un conjunto de archivos seleccionados seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $data_file = $this->File_model->delete($row_id);
            $data['qty_deleted'] += $data_file['status'];
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Elimina un registro de la tabla file, y los archivos asociados en el servidor
     * 2020-07-24
     */
    function delete($file_id)
    {
        $data = $this->File_model->delete($file_id);
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
     * AJAX JSON
     * Carga un archivo en la ruta "content/uploads/{year}/}{month}/"
     * Crea registro de ese arhivo en la tabla file
     */
    function upload()
    {
        $data = $this->File_model->upload();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function edit($file_id)
    {
        $data = $this->File_model->basic($file_id);
        
        //Variables
            $data['destino_form'] = "files/editar_e/{$file_id}";
            $data['att_img'] = $this->File_model->att_img($file_id, '500px_');
        
        //Variables generales
            $data['file_id'] = $file_id;
            $data['head_subtitle'] = $data['row']->file_name;
            $data['nav_2'] = 'files/menu_v';
            $data['view_a'] = 'files/edit_v';
            
        //Variables generales
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    function update($file_id)
    {
        $arr_row = $this->input->post();
        $data = $this->File_model->update($file_id, $arr_row);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// RECORTE DE IMAGEN
//-----------------------------------------------------------------------------

    /**
     * Formulario para recorte de archivo de imagen.
     */
    function cropping($file_id)
    {
        $data = $this->File_model->basic($file_id);

        $data['back_destination'] = "files/edit/{$file_id}";

        $data['view_a'] = 'files/cropping_v';
        $data['nav_2'] = 'files/menu_v';
        $data['head_subtitle'] = 'Recortar';
        $this->App_model->view(TPL_ADMIN, $data);
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

// CAMBIAR ARCHIVO
//-----------------------------------------------------------------------------

    function change($file_id)
    {
        $data = $this->File_model->basic($file_id);
        
        //Variables
            $data['destino_form'] = "files/cambiar_e/{$file_id}";
            $data['att_img'] = $this->File_model->att_img($file_id, '500px_');
        
        //Variables generales
            $data['file_id'] = $file_id;
            $data['subtitulo_pagina'] = 'Cambiar archivo';
            $data['nav_2'] = 'files/menu_v';
            $data['view_a'] = 'files/change_v';
            //$data['vista_b'] = 'files/cambiar_v';       
            
        //Variables generales
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Cambia un archivo, conservando su registro y sus asignaciones en la DB.
     * 2019-09-19
     */
    function change_e($file_id)
    {
        $row_ant = $this->Db_model->row_id('file', $file_id);   //Registro antes del cambio

        $data = $this->File_model->upload($file_id);
        
        if ( $data['status'] )
        {
            //Eliminar archivo anterior
                $this->File_model->unlink($row_ant->folder, $row_ant->file_name);
            
            //Actualizar archivo, datos del nuevo archivo
                $data['row'] = $this->File_model->change($file_id, $data['upload_data']);
                $this->File_model->create_thumbnails($file_id);     //Crear miniaturas de la nueve imagen
                $this->File_model->mod_original($data['row']->folder, $data['row']->file_name);          //Mofificar imagen nueva después de crear miniaturas
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// PROCESOS MASIVOS
//-----------------------------------------------------------------------------

    function update_url()
    {
        $data = $this->File_model->update_url();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
}
