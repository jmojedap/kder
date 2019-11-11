<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Institutions extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Institution_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($institution_id)
    {
        redirect("institutions/info/{$institution_id}");
    }
    
//EXPLORE
//---------------------------------------------------------------------------------------------------
        
    function explore()
    {        
        //Datos básicos de la exploración
            $data = $this->Institution_model->explore_data(1);
        
        //Opciones de filtros de búsqueda
            $data['options_place'] = $this->App_model->options_place('type_id = 4', 'cr', 'Todas');
            
        //Arrays con valores para contenido en lista
            //$data['arr_roles'] = $this->Item_model->arr_cod('category_id = 58');
            
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
            $data = $this->Institution_model->explore_table_data($num_page);
        
        //Arrays con valores para contenido en lista
            //$data['arr_roles'] = $this->Item_model->arr_cod('category_id = 58');
        
        //Preparar respuesta
            $data['html'] = $this->load->view('institutions/explore/table_v', $data, TRUE);
        
        //Salida
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
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
            $qty_deleted += $this->Institution_model->delete($row_id);
        }
        
        $result['status'] = 1;
        $result['message'] = 'Cantidad eliminados : ' . $qty_deleted;
        $result['qty_deleted'] = $qty_deleted;
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($result));
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
            $results_total = $this->Institution_model->search($filters); //Para calcular el total de results
        
        //Preparar datos
            $datos['nombre_hoja'] = 'Institutions';
            $datos['query'] = $results_total;
            
        //Preparar file
            $objeto_file = $this->excel->file_query($datos);
        
        $data['objeto_file'] = $objeto_file;
        $data['nombre_file'] = date('Ymd_His'). '_users'; //save our workbook as this file name
        
        $this->load->view('common/download_excel_v', $data);
    }
    
    
// CRUD
//-----------------------------------------------------------------------------

    /**
     * Formulario para la creación de una nueva institución
     * 
     * @param type $tipo_rol
     */
    function add()
    {
        //Variables generales
            $data['head_title'] = 'Instituciones';
            $data['head_subtitle'] = 'Nueva';
            $data['nav_2'] = 'institutions/explore/menu_v';
            $data['view_a'] = 'institutions/add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Toma datos de POST e inserta un registro en la tabla institution. 
     * 2019-10-29
     */ 
    function insert()
    {
        $this->load->model('Institution_model');
        $res_validation = $this->Institution_model->validate_form();
        
        if ( $res_validation['status'] )
        {
            $data = $this->Institution_model->insert();
            $this->Institution_model->set_main($data['institution_id']);    //Establecer institución al usuario creador
        } else {
            $data = $res_validation;
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * Información general del usuario
     */
    function info($institution_id)
    {        
        //Datos básicos
        $data = $this->Institution_model->basic($institution_id);
        
        //Variables específicas
        $data['view_a'] = 'institutions/info_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }
    
// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de un user. Los datos que se
     * editan dependen de la $section elegida.
     * 2016-10-30
     */
    function edit($institution_id, $section = 'basic')
    {
        //Datos básicos
        $this->load->model('File_model');
        $data = $this->Institution_model->basic($institution_id);
        
        //Establecer vista según $section de edición
            $view_a = "institutions/edit/{$section}_v";
        
        //Imagen de la institución
            if ( $section == 'image' ) { $view_a = 'institutions/image/image_v'; }

        //Recorte de imagen
            if ( $section == 'cropping' )
            {
                $view_a = 'files/cropping_v';
                $data['image_id'] = $data['row']->image_id;
                $data['src_image'] = URL_UPLOADS . $data['row']->image_folder . $data['row']->image_file;
                $data['back_destination'] = "institutions/edit/{$institution_id}/image";
            }
        
        //Variables específicas
            $data['link_cropping'] = "institutions/edit/{$institution_id}/cropping";
        
        //Variables cargue vista
            $data['nav_2'] = 'institutions/menu_v';
            $data['nav_3'] = 'institutions/edit/menu_v';
            $data['view_a'] = $view_a;
        
        $this->App_model->view(TPL_ADMIN, $data);
    }
    
    /**
     * AJAX JSON
     * Se validan los datos de una institución nueva o existente ($institution_id),
     * los datos deben cumplir varios criterios
     * 2019-10-30
     */
    function validate_form($institution_id = NULL)
    {
        $data = $this->Institution_model->validate_form($institution_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * POST JSON
     * 
     * @param type $institution_id
     */
    function update($institution_id)
    {
        $data = $this->Institution_model->update($institution_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
// IMAGEN DE PERFIL DE LA INSTITUCIÓN
//-----------------------------------------------------------------------------
    /**
     * AJAX JSON
     * Carga file de image y se la asigna a una institución.
     * @param type $institution_id
     */
    function set_image($institution_id)
    {
        //Cargue
        $this->load->model('File_model');
        
        $data_upload = $this->File_model->upload();
        
        $data = array('status' => 0, 'message' => 'La imagen no fue asignada');
        if ( $data_upload['status'] )
        {
            $this->Institution_model->remove_image($institution_id);                                //Quitar image actual, si tiene una
            $data = $this->Institution_model->set_image($institution_id, $data_upload['row']->id);   //Asignar imagen nueva
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * POST REDIRECT
     * Proviene de la herramienta de recorte institutions/edit/$institution_id/crop, 
     * utiliza los datos del form para hacer el recorte de la image.
     * Actualiza las miniaturas
     * 
     * @param type $institution_id
     * @param type $file_id
     */
    function crop_image_e($institution_id, $file_id)
    {
        $this->load->model('File_model');
        $this->File_model->crop($file_id);
        redirect("institutions/edit/{$institution_id}/image");
    }
    
    /**
     * AJAX
     * Desasigna y elimina la image asociada a una institución, si la tiene.
     * 
     * @param type $institution_id
     */
    function remove_image($institution_id)
    {
        $data = $this->Institution_model->remove_image($institution_id);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// GESTIÓN DE PROPIETARIOS
//-----------------------------------------------------------------------------

    /**
     * Listado de instituciones creadas por un usuario propietario, si no tiene instituciones creadas
     * muestra el formulario para la creación de una institución.
     * 2019-10-31
     */
    function my_institutions()
    {
        $this->db->where('creator_id', $this->session->userdata('user_id'));
        $data['institutions'] = $this->db->get('institution');

        $data['view_a'] = 'institutions/add_v';
        $data['head_subtitle'] = 'Crea tu institución';
        if ( $data['institutions']->num_rows() > 0 )
        {
            $data['view_a'] = 'institutions/my_institutions_v';
            $data['head_subtitle'] = '';
        }

        $data['head_title'] = 'Institución';

        $this->App_model->view(TPL_ADMIN, $data);
    }

}