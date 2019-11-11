<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();
        
        $this->load->model('Item_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
//CRUD
//---------------------------------------------------------------------------------------------------
    
    /**
     * Vista filtra items por categoría, CRUD de items.
     * 
     * @param type $category_id
     * @param type $item_id
     */
    function manage($category_id = '058')
    {
        //Variables específicas
            $data['category_id'] = $category_id;
            $data['arr_categories'] = $this->Item_model->options('category_id = 0');
        
        //Array data generales
            $data['head_title'] = 'Ítems';
            $data['head_subtitle'] = 'parámetros del sistema';
            $data['view_a'] = 'system/items/manage_v';
            
        //Cargar vista
            $this->App_model->view('templates/remark/main_v', $data);
    }
    
    /**
     * AJAX JSON
     * Listado de ítems de una categoría específica, tabla item
     * 
     * @param type $category_id
     */
    function get_list($category_id = '058')
    {
        $items = $this->Item_model->items($category_id);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($items->result()));
    }
    
    /**
     * AJAX JSON
     * Guarda los datos enviados por post, registro en la tabla item, insertar
     * o actualizar.
     * 
     */
    function save($item_id)
    {
        $arr_row = $this->input->post();
        
        $data = $this->Item_model->save($arr_row, $item_id);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
    
    /**
     * AJAX
     * Eliminar un registro, devuelve la cantidad de registros eliminados
     */
    function delete($item_id, $category_id)
    {
        $conditions['id'] = $item_id;
        $conditions['category_id'] = $category_id;
        $this->Item_model->delete($conditions);
        
        $data['result'] = 0;
        if ( $this->db->affected_rows() ) { $data['result'] = 1; }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
    
    /**
     * AJAX Eliminar un grupo de items selected
     */
    function delete_selected()
    {
        $str_selected = $this->input->post('selected');
        
        $selected = explode('-', $str_selected);
        
        foreach ( $selected as $elemento_id ) 
        {
            $conditions['id'] = $elemento_id;
            $this->Item_model->delete($conditions);
        }
        
        echo count($selected);
    }   
}