<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('User_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($user_id)
    {
        redirect("users/info/{$user_id}");
    }
    
//EXPLORE
//---------------------------------------------------------------------------------------------------

    function explore()
    {        
        //Datos básicos de la exploración
            $data = $this->User_model->explore_data(1);
        
        //Opciones de filtros de búsqueda
            $data['options_role'] = $this->Item_model->options('category_id = 58', 'Todos');
            
        //Arrays con valores para contenido en lista
            $data['arr_roles'] = $this->Item_model->arr_cod('category_id = 58');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Listado de usuarios filtrados por un criterio de búsqueda, páginado
     */
    function get($num_page = 1)
    {
        $data = $this->User_model->get($num_page);

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
            $qty_deleted += $this->User_model->delete($row_id);
        }
        
        $result['status'] = 1;
        $result['message'] = 'Cantidad eliminados : ' . $qty_deleted;
        $result['qty_deleted'] = $qty_deleted;
        
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }
    
    /**
     * Exporta el result de la búsqueda a un file de Excel
     */
    function export()
    {
        //Cargando
            $this->load->model('Search_model');
            $this->load->library('excel');
        
        //Datos de consulta, construyendo array de búsqueda
            $filters = $this->Search_model->filters();
            $results_total = $this->User_model->search($filters); //Para calcular el total de results
        
        //Preparar datos
            $data['sheet_name'] = 'Users';
            $data['query'] = $results_total;
            
        //Preparar file
            $object_file = $this->excel->file_query($data);
        
        $data['object_file'] = $object_file;
        $data['filename'] = date('Ymd_His'). '_users'; //save our workbook as this file name
        
        $this->load->view('common/download_excel_v', $data);
    }
    
    
// CRUD
//-----------------------------------------------------------------------------

    /**
     * Formulario para la creación de un nuevo usuario
     * 
     * @param type $tipo_rol
     */
    function add($role_type = 'institutional')
    {
        //Variables específicas
            $data['role_type'] = $role_type;

        //Variables generales
            $data['head_title'] = 'Usuarios';
            $data['head_subtitle'] = 'Nuevo';
            $data['nav_2'] = 'users/explore/menu_v';
            $data['nav_3'] = 'users/add/menu_v';
            $data['view_a'] = 'users/add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * POST JSON
     * Toma datos de POST e inserta un registro en la tabla user. Devuelve
     * result del proceso en JSON
     * 2019-11-07
     * 
     */ 
    function insert()
    {
        $res_validation = $this->User_model->validate();
        
        if ( $res_validation['status'] )
        {
            $data = $this->User_model->insert();
        } else {
            $data = $res_validation;
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * Información general del usuario
     */
    function profile($user_id)
    {        
        //Datos básicos
        $data = $this->User_model->basic($user_id);

        $data['view_a'] = 'users/profile/profile_v';
        if ( $data['row']->role >= 10  ) { $data['view_a'] = 'users/profile/institutional_v'; }
        if ( $data['row']->role == 21  ) { $data['view_a'] = 'users/profile/relative_v'; }
        if ( $data['row']->role == 23  ) { $data['view_a'] = 'users/profile/student_v'; }
        
        //Variables específicas
        
        $this->App_model->view(TPL_ADMIN, $data);
    }
    
// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de un user. Los datos que se
     * editan dependen de la $section elegida.
     */
    function edit($user_id, $section = 'basic')
    {
        //Datos básicos
        $data = $this->User_model->basic($user_id);
        
        $view_a = "users/edit/{$section}_v";
        if ( $section == 'cropping' )
        {
            $view_a = 'files/cropping_v';
            $data['image_id'] = $data['row']->image_id;
            $data['url_image'] = URL_UPLOADS . $data['row']->url_image;
            $data['back_destination'] = "users/edit/{$user_id}/image";
        }
        
        //Array data espefícicas
            //$data['valores_form'] = $this->Pcrn->valores_form($data['row'], 'user');
            //$data['nav_2'] = 'users/menus/user_v';
            $data['nav_3'] = 'users/edit/menu_v';
            $data['view_a'] = $view_a;
        
        $this->App_model->view(TPL_ADMIN, $data);
    }
    
    /**
     * AJAX JSON
     * Se validan los datos de un user add o existente ($user_id),
     * los datos deben cumplir varios criterios
     * 
     * @param type $user_id
     */
    function validate($user_id = NULL)
    {
        $data = $this->User_model->validate($user_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * POST JSON
     * 
     * @param type $user_id
     */
    function update($user_id)
    {
        $arr_row = $this->input->post();
        if ( strlen($arr_row['email']) == 0 ) { unset($arr_row['email']); }  //Si viene vacío evitar que sea nulo

        $data = $this->User_model->update($user_id, $arr_row);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Actualiza el campo user.activation_key, para activar o restaurar la contraseña de un usuario
     * 2019-11-18
     */
    function set_activation_key($user_id)
    {
        $this->load->model('Account_model');
        $activation_key = $this->Account_model->activation_key($user_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($activation_key));
    }
    
// IMAGEN DE PERFIL DE USUARIO
//-----------------------------------------------------------------------------
    /**
     * Carga file de image y se la asigna a un user.
     * @param type $user_id
     */
    function set_image($user_id)
    {
        //Cargue
        $this->load->model('File_model');
        
        $data_upload = $this->File_model->upload();
        
        $data = $data_upload;
        if ( $data_upload['status'] )
        {
            $this->User_model->remove_image($user_id);                                  //Quitar image actual, si tiene una
            $data = $this->User_model->set_image($user_id, $data_upload['row']->id);    //Asignar imagen nueva
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * POST REDIRECT
     * 
     * Proviene de la herramienta de recorte users/edit/$user_id/crop, 
     * utiliza los datos del form para hacer el recorte de la image.
     * Actualiza las miniaturas
     * 
     * @param type $user_id
     * @param type $file_id
     */
    function crop_image_e($user_id, $file_id)
    {
        $this->load->model('File_model');
        $this->File_model->crop($file_id);
        redirect("users/edit/{$user_id}/image");
    }
    
    /**
     * AJAX
     * Desasigna y elimina la image asociada a un user, si la tiene.
     * 
     * @param type $user_id
     */
    function remove_image($user_id)
    {
        $data = $this->User_model->remove_image($user_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// IMPORTACIÓN DE USUARIOS
//-----------------------------------------------------------------------------

    /**
     * Mostrar formulario de importación de usuarios
     * con archivo Excel. El resultado del formulario se envía a 
     * 'users/import_e'
     */
    function import($type = 'students')
    {
        $data = $this->User_model->import_config($type);

        $data['url_file'] = URL_RESOURCES . 'import_templates/' . $data['template_file_name'];
        

        $data['head_title'] = 'Usuarios';
        $data['nav_2'] = 'users/explore/menu_v';
        $data['view_a'] = 'common/import_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    //Ejecuta la importación de usuarios con archivo Excel
    function import_e()
    {
        //Proceso
        $this->load->library('excel');            
        $imported_data = $this->excel->arr_sheet_default($this->input->post('sheet_name'));
        
        if ( $imported_data['status'] == 1 )
        {
            $data = $this->User_model->import($imported_data['arr_sheet']);
        }

        //Cargue de variables
            $data['status'] = $imported_data['status'];
            $data['message'] = $imported_data['message'];
            $data['arr_sheet'] = $imported_data['arr_sheet'];
            $data['sheet_name'] = $this->input->post('sheet_name');
            $data['back_destination'] = "users/explore/";
            $data['cf_open'] = 'users/info/';
        
        //Cargar vista
            $data['head_title'] = 'Usuarios';
            $data['head_subtitle'] = 'Resultado importación';
            $data['view_a'] = 'common/import_result_v';
            $data['nav_2'] = 'users/explore/menu_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }
    
//---------------------------------------------------------------------------------------------------
    
    /**
     * AJAX
     * Devuelve un valor de username sugerido disponible, dados los nombres y last_name
     */
    function username()
    {
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        $username = $this->User_model->generate_username($first_name, $last_name);
        
        $this->output->set_content_type('application/json')->set_output($username);
    }

// ANOTACIONES
//-----------------------------------------------------------------------------

    function notes($user_id)
    {
        $data = $this->User_model->basic($user_id);

        $data['options_type'] = $this->Item_model->options('category_id = 191');

        $data['arr_types'] = $this->Item_model->arr_item('category_id = 191', 'cod_num');

        $data['view_a'] = 'users/notes/notes_v';
        $data['subtitle_head'] = 'Anotaciones';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    function get_notes($user_id)
    {
        $this->load->model('Note_model');
        $data = $this->User_model->notes($user_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function save_note($user_id, $note_id = 0)
    {
        $data = $this->User_model->save_note($user_id, $note_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

}