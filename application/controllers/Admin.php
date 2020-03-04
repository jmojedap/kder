<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        
        $this->load->model('Admin_model');
        
        //Para formato de horas
        date_default_timezone_set("America/Bogota");

    }
        
    function index()
    {
        redirect('admin/acl');
    }
        
// SIS OPTION 2019-06-15
//---------------------------------------------------------------------------------------------------

    /**
     * Listas de documentos, creación, edición y eliminación de opciones
     */
    function options()
    {
        $data['head_title'] = 'Opciones del sistema';
        $data['nav_2'] = 'system/admin/menu_v';        
        $data['view_a'] = 'system/admin/options_v';        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX - JSON
     * Listado de las opciones de documentos (post.type_id = 7022)
     */
    function get_options()
    {
        $data['options'] = $this->db->get('sis_option')->result();

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX - JSON
     * Inserta o actualiza una opcione de documentos (post.type_id = 7022)
     */
    function save_option($option_id = 0)
    {
        $option_id = $this->Admin_model->save_option($option_id);

        $data = array('status' => 0, 'message' => 'La opción no fue guardada');
        if ( ! is_null($option_id) ) { $data = array('status' => 1, 'message' => 'Opción guardada: ' . $option_id); }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Elimina una opcione de documentos, registro de la tabla post
     */
    function delete_option($option_id)
    {
        $data = $this->Admin_model->delete_option($option_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * ml > master login
     * Función para el login de administradores ingresando con otro user
     * 
     * @param type $user_id
     */
    function ml($user_id)
    {
        $this->load->model('Account_model');
        $username = $this->Db_model->field_id('user', $user_id, 'username');
        if ( $this->session->userdata('rol_id') <= 1 ) { $this->Account_model->create_session($username, FALSE); }
        
        redirect('app/logged');
    }
    
    
// SIS_ACL - Access Control List, permisos por rol de user
//-----------------------------------------------------------------------------
    
    /**
     * VISTA
     * Listado y formulario para gestión de ACL
     */
    function acl($subdomain = 'admin', $controller = 'users')
    {
        //Variables
        $data['subdomain'] = $subdomain;
        $data['controller'] = $controller;
        $data['controllers'] = $this->Admin_model->controllers();
        $data['options_type_id'] = $this->Item_model->options('category_id = 5', 'Tipo de recurso');

        //Solicitar vista
        $data['head_title'] = 'ACL Permisos de acceso';
        $data['nav_2'] = 'system/admin/menu_v';        
        $data['view_a'] = 'system/admin/acl/acl_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }
    
    /**
     * JSON
     * Lista de procesos en la tabla sis_acl, por controller
     */
    function acl_list($subdomain = 'admin', $controller = 'users')
    {
        $this->db->select('id, controller, function_name, cf, description, roles, CONCAT("0", (type_id)) AS type_id');
        $this->db->where('subdomain', $subdomain);
        $this->db->where('controller', $controller);
        $this->db->order_by('function_name', 'ASC');
        $lista = $this->db->get('sis_acl');
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($lista->result()));
    }
    
    /**
     * AJAX JSON
     * Recibe datos de formulario en admin/acl_list, guarda datos en la tabla
     */
    function acl_save($row_id = 0)
    {
        //Construir registro
            $arr_row = $this->input->post();   //Tomado de formulario
            $arr_row['function_title'] = $arr_row['controller'] . ' ' . $arr_row['function_name'];
            $arr_row['cf'] = $arr_row['controller'] . '/' . $arr_row['function_name'];
            
        //Guardar
            $data = $this->Admin_model->acl_save($arr_row, $row_id);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX
     * Eliminar un registro, devuelve la cantidad de registros eliminados
     */
    function acl_delete($row_id, $controller)
    {
        $data = $this->Admin_model->acl_delete($row_id, $controller);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// Pruebas y desarrollo
//-----------------------------------------------------------------------------

    /**
     * JSON Datos de sesión del usuario
     */
    function session()
    {
        $data = $this->session->userdata();;
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function phpinfo()
    {
        phpinfo();
    }

    /**
     * Reestablecer sistema para pruebas
     * 2019-07-19
     */
    function reset()
    {
        $this->db->query('DELETE FROM orders WHERE id > 1;');
        $this->db->query('DELETE FROM order_product WHERE id > 1;');

        echo 'LISTO';
    }

    function test()
    {
        $data['head_title'] = 'Test';
        $data['view_a'] = 'app/test_v';
        $data['cant_resultados'] = '50';

        $this->db->select('*');
        $data['pictures'] = $this->db->get('pictures');

        $this->App_model->view('templates/remark/main_v', $data);
    }

    function test_ajax()
    {
        $data['view_a'] = '<h1>Hola desde ajax</h1>';
        $data['status'] = 1;

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
}