<?php

class Admin_model extends CI_Model{
    
    /* Admin hace referencia a Administración,
     * Colección de funciones especiales para utilizarse específicamente
     * con CodeIgniter en la aplicación para tareas de administración
     * 
     */
    
    function __construct(){
        parent::__construct();
        
    }

// OPCIONES DE LA APLICACION 2019-06-15
//-----------------------------------------------------------------------------

    /** Guarda registro de una opción en la tabla sis_option */
    function save_option($option_id)
    {
        $arr_row = $this->input->post();
        $option_id = $this->Db_model->save('sis_option', "id = {$option_id}", $arr_row);

        return $option_id;
    }

    /**
     * Elimina opción, de la tabla post.
     */
    function delete_option($option_id)
    {
        $data = array('status' => 0, 'message' => 'La opción no fue eliminada');

        //Tabla post
            $this->db->where('id', $option_id);
            $this->db->delete('sis_option');

        if ( $this->db->affected_rows() > 0 ) {
            $data = array('status' => 1, 'message' => 'Opción eliminada');
        }

        return $data;
    }
    
// ACL ACCESS CONTROL LIST
//-----------------------------------------------------------------------------
    
    function controllers($condition = NULL)
    {
        $this->db->select('id, item_name AS controller, filters AS subdomain');
        $this->db->where('category_id', 32);
        
        $controllers = $this->db->get('item');
        
        return $controllers;
    }

    /**
     * Guardar un registro en la tabla sis_acl. Insertar o Editar.
     * @param type $arr_row
     * @return type
     */
    function acl_save($arr_row, $row_id)
    {
        //Insertar o Editar
            $data['row_id'] = $this->Db_model->save('sis_acl', "id = {$row_id}", $arr_row);
            
        //Resultado
            $data['status'] = 0;
            if ( $data['row_id'] > 0 ) { $data['status'] = 1; }
        
        return $data;
    }
    
    /**
     * Elimina registro de la tabla sis_acl, con un ID y Controlador determinados
     * 
     * @param type $row_id
     * @param type $controller
     * @return int
     */
    function acl_delete($row_id, $controller)
    {
        $this->db->where('id', $row_id);
        $this->db->where('controller', $controller);
        $this->db->delete('sis_acl');
        
        $data['status'] = 0;
        if ( $this->db->affected_rows() ) { $data['status'] = 1; }
        
        return $data;
    }
    
    
}