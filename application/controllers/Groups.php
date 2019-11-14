<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Group_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($group_id)
    {
        redirect("groups/info/{$group_id}");
    }
    
//EXPLORE
//---------------------------------------------------------------------------------------------------
        
    function explore()
    {        
        //Datos básicos de la exploración
            $data = $this->Group_model->explore_data(1);
        
        //Opciones de filtros de búsqueda
            $data['options_level'] = $this->Item_model->options('category_id = 3', 'Nivel escolar');
            $data['options_teacher'] = $this->App_model->options_user("role > 10 AND role < 20 AND institution_id = {$this->session->userdata('institution_id')}");
            $data['options_generation'] = $this->App_model->options_generation();
            
        //Arrays con valores para contenido en lista
            $data['arr_levels'] = $this->Item_model->arr_cod('category_id = 3');
            
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
            $data = $this->Group_model->explore_table_data($num_page);
        
        //Arrays con valores para contenido en lista
            $data['arr_levels'] = $this->Item_model->arr_cod('category_id = 3');
        
        //Preparar respuesta
            $data['html'] = $this->load->view('groups/explore/table_v', $data, TRUE);
        
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
            $qty_deleted += $this->Group_model->delete($row_id);
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
     * Formulario para la creación de un nuevo grupo
     * 
     * @param type $tipo_rol
     */
    function add()
    {
        //Variables generales
            $data['head_title'] = 'Grupos';
            $data['head_subtitle'] = 'Nuevo';
            $data['nav_2'] = 'groups/explore/menu_v';
            $data['view_a'] = 'groups/add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Toma datos de POST e inserta un registro en la tabla group. 
     * 2019-10-29
     */ 
    function insert()
    {
        $data = $this->Group_model->insert();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * Información general del grupo
     */
    function info($group_id)
    {        
        //Datos básicos
        $data = $this->Group_model->basic($group_id);

        $data['row_teacher'] = $this->Db_model->row_id('user', $data['row']->teacher_id);
        
        //Variables específicas
        $data['view_a'] = 'groups/info_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }
    
// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de un grupo.
     * 2016-11-05
     */
    function edit($group_id)
    {
        //Datos básicos
            $data = $this->Group_model->basic($group_id);
        
        //Variables cargue vista
            $data['nav_2'] = 'groups/menu_v';
            $data['view_a'] = 'groups/edit_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }    

    /**
     * POST JSON
     * 
     * @param type $group_id
     */
    function update($group_id)
    {
        $data = $this->Group_model->update($group_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// GESTIÓN DE ESTUDIANTES
//-----------------------------------------------------------------------------

    /**
     * VISTA
     * Listado de estudiantes que pertenecen a un grupo
     * 2019-11-14
     */
    function students($group_id)
    {
        $data = $this->Group_model->basic($group_id);

        $data['view_a'] = 'groups/students/students_v';
        $data['nav_2'] = 'groups/menu_v';
        $data['subtitle_head'] = 'Estudiantes';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Listado de estudiantes que pertenecen a un grupo
     * 2016-11-14
     */
    function get_students($group_id)
    {
        $students = $this->Group_model->students($group_id);

        $this->output->set_content_type('application/json')->set_output(json_encode($students->result()));
    }

    /**
     * Agrega un registro a la tabla user, y lo asigna al grupo_id
     * 2019-11-07
     */
    function insert_student($group_id)
    {
        $data = array('status' => 0, 'message' => 'El estudiante no fue creado');

        $this->load->model('User_model');
        $data_insert = $this->User_model->insert();
        if ( $data_insert['saved_id'] > 0 )
        {
            $data = $this->Group_model->add_student($group_id, $data_insert['saved_id']);
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Agrega estudiante existente un grupo
     * 2019-11-06
     */
    function add_student($group_id, $user_id)
    {
        $data = $this->Group_model->add_student($group_id, $user_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Quita un estudiante de un grupo. No lo elimina de la plataforma.
     * gu_id corresponde a (group_user.id)
     * 2019-11-13
     */
    function remove_student($group_id, $user_id, $gu_id)
    {
        $data = $this->Group_model->remove_student($group_id, $user_id, $gu_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Listado de usuarios candidatos para ser agregados a un grupo de estudiantes
     * 2019-11-14
     */
    function students_autocomplete($group_id)
    {
        $filters['condition'] = "id NOT IN (SELECT user_id FROM group_user WHERE group_id = {$group_id})";
        $filters['q'] = $this->input->get('term');

        $this->load->model('Search_model');
        $this->load->model('User_model');
        $elements = $this->User_model->autocomplete($filters);
        $arr_elements = $elements->result_array();
        
        $this->output->set_content_type('application/json')->set_output(json_encode($arr_elements));
    }

}