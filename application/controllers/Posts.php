<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Post_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($post_id)
    {
        redirect("posts/info/{$post_id}");
    }
    
//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------
    
    function explore()
    {
        //$this->output->enable_profiler(TRUE);

        //Datos básicos de la exploración
            $data = $this->Post_model->explore_data(1);
        
        //Opciones de filtros de búsqueda
            $data['options_type'] = $this->Item_model->options('category_id = 33', 'Todos');
            
        //Arrays con valores para contenido en lista
            $data['arr_types'] = $this->Item_model->arr_cod('category_id = 33');
            
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
            $data = $this->Post_model->explore_table_data($num_page);
        
        //Arrays con valores para contenido en lista
            $data['arr_types'] = $this->Item_model->arr_cod('category_id = 33');
        
        //Preparar respuesta
            $data['html'] = $this->load->view('posts/explore/table_v', $data, TRUE);
        
        //Salida
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
    
    /**
     * JSON Listado de posts, para una página determinada, según criterios
     * de búsqueda definidos por GET en la URL.
     * 
     * @param type $num_page
     */
    function get($num_page = 1)
    {
        $data = $this->Post_model->data_list($num_page);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
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
            $filters = $this->Search_model->busqueda_array();
            $results_total = $this->Post_model->search($filters); //Para calcular el total de results
        
        //Preparar datos
            $datos['nombre_hoja'] = 'Posts';
            $datos['query'] = $results_total;
            
        //Preparar file
            $objeto_file = $this->excel->file_query($datos);
        
        $data['objeto_file'] = $objeto_file;
        $data['nombre_file'] = date('Ymd_His'). '_posts'; //save our workbook as this file name
        
        $this->load->view('common/download_excel_v', $data);
    }
    
    /**
     * AJAX JSON
     * Eliminar un conjunto de posts seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $quan_deleted = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $quan_deleted += $this->Post_model->delete($row_id);
        }
        
        $result['status'] = 1;
        $result['message'] = 'Cantidad seleccionados : ' . count($selected);
        $result['quan_deleted'] = $quan_deleted;
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($result));
    }
    
    
// CRUD
//-----------------------------------------------------------------------------

    /**
     * Formulario para la creación de un nuevo post
     * 
     */
    function add()
    {
        //Variables generales
            $data['head_title'] = 'Post';
            $data['head_subtitle'] = 'Nuevo';
            $data['nav_2'] = 'posts/explore/menu_v';
            $data['view_a'] = 'posts/add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * POST JSON
     * 
     * Toma datos de POST e inserta un registro en la tabla user. Devuelve
     * result del proceso en JSON
     * 
     */ 
    function insert()
    {
        $this->load->model('Account_model');
        $res_validation = $this->Account_model->validate_form();
        
        if ( $res_validation['status'] )
        {
            $data = $this->Post_model->insert();
        } else {
            $data = $res_validation;
        }
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
    
    /**
     * Información general del usuario
     */
    function info($post_id)
    {        
        //Datos básicos
        $data = $this->Post_model->basic_data($post_id);
        
        //Variables específicas
        $data['view_a'] = 'posts/info_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }
    
// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de un user. Los datos que se
     * editan dependen de la $section elegida.
     */
    function edit($post_id)
    {
        //Datos básicos
        $data = $this->Post_model->basic_data($post_id);

        $data['options_type'] = $this->Item_model->options('category_id = 33', 'Todos');
        
        //Array data espefícicas
            $data['nav_2'] = 'posts/menu_v';
            $data['view_a'] = 'posts/edit_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * POST JSON
     * 
     * @param type $post_id
     */
    function update($post_id)
    {
        $arr_row = $this->input->post();

        //$this->load->model('Account_model');
        $data = $this->Post_model->update($post_id, $arr_row);
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
    
// IMAGEN PRINCIPAL DEL POST
//-----------------------------------------------------------------------------

    function image($post_id)
    {
        $data = $this->Post_model->basic_data($post_id);        

        $data['view_a'] = 'posts/image/image_v';
        $data['nav_2'] = 'posts/menu_v';
        $data['subtitle_head'] = 'Imagen asociada';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    function cropping($post_id)
    {
        $data = $this->Post_model->basic_data($post_id);        

        $data['image_id'] = $data['row']->image_id;
        $data['src_image'] = $data['att_image']['src'];
        $data['back_destination'] = "posts/image/{$post_id}";

        $data['view_a'] = 'files/cropping_v';
        $data['nav_2'] = 'posts/menu_v';
        $data['subtitle_head'] = 'Imagen asociada al post';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Carga file de image y se la asigna a un post.
     * @param type $post_id
     */
    function set_image($post_id)
    {
        //Cargue
        $this->load->model('File_model');
        
        $data_upload = $this->File_model->upload();
        
        $data = $data_upload;
        if ( $data_upload['status'] )
        {
            $this->Post_model->remove_image($post_id);                              //Quitar image actual, si tiene una
            $data = $this->Post_model->set_image($post_id, $data_upload['row']->id);   //Asignar imagen nueva
        }

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    /**
     * AJAX
     * Desasigna y elimina la image asociada a un post, si la tiene.
     * 
     * @param type $post_id
     */
    function remove_image($post_id)
    {
        $data = $this->Post_model->remove_image($post_id);
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }


}