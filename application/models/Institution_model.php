<?php
class Institution_model extends CI_Model{

    function basic($institution_id)
    {
        $data['institution_id'] = $institution_id;
        $data['row'] = $this->Db_model->row_id('institution', $institution_id);
        $data['att_img'] = $this->App_model->att_img_institution($data['row']);
        $data['head_title'] = $data['row']->name;
        $data['view_a'] = 'institutions/institution_v';
        $data['nav_2'] = 'institutions/menu_v';

        return $data;
    }

// EXPLORE FUNCTIONS - institutions/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     * 
     * @return string
     */
    function explore_data($num_page)
    {
        //Data inicial, de la tabla
            $data = $this->explore_table_data($num_page);
        
        //Elemento de exploración
            $data['controller'] = 'institutions';                      //Nombre del controlador
            $data['views_folder'] = 'institutions/explore/';           //Carpeta donde están las vistas de exploración
            $data['head_title'] = 'Instituciones';
                
        //Otros
            $data['search_num_rows'] = $this->search_num_rows($data['filters']);
            $data['head_subtitle'] = $this->search_num_rows($data['filters']);
            $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'],1) / $data['per_page']);   //Cantidad de páginas

        //Vistas
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    /**
     * Array con los datos para la tabla de la vista de exploración
     * 
     * @param type $num_page
     * @return string
     */
    function explore_table_data($num_page)
    {
        //Elemento de exploración
            $data['cf'] = 'institutions/explore/';     //CF Controlador Función
            $data['adv_filters'] = array('plc');
        
        //Paginación
            $data['num_page'] = $num_page;                  //Número de la página de datos que se está consultado
            $data['per_page'] = 15;                           //Cantidad de registros por página
            $offset = ($num_page - 1) * $data['per_page'];    //Número de la página de datos que se está consultado
        
        //Búsqueda y Resultados
            $this->load->model('Search_model');
            $data['filters'] = $this->Search_model->filters();
            $data['str_filters'] = $this->Search_model->str_filters();
            $data['elements'] = $this->Institution_model->search($data['filters'], $data['per_page'], $offset);    //Resultados para página
            
        //Otros
            $data['search_num_rows'] = $this->Institution_model->search_num_rows($data['filters']);
            $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'],1) / $data['per_page']);   //Cantidad de páginas
            $data['all_selected'] = '-'. $this->pml->query_to_str($data['elements'], 'id');           //Para selección masiva de todos los elementos de la página
            
        return $data;
    }
    
    /**
     * String con condición WHERE SQL para filtrar user
     * 
     * @param type $filters
     * @return type
     */
    function search_condition($filters)
    {
        $condition = NULL;
        
        //Rol de user
        if ( $filters['role'] != '' ) { $condition .= "role = {$filters['role']} AND "; }
        
        if ( strlen($condition) > 0 )
        {
            $condition = substr($condition, 0, -5);
        }
        
        return $condition;
    }
    
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        
        $role_filter = $this->role_filter($this->session->userdata('user_id'));

        //Construir consulta
            $this->db->select('institution.id, name, email, institution.city_id, place_name, address');
            $this->db->join('place', 'institution.city_id = place.id');
        
        //Crear array con términos de búsqueda
            $words_condition = $this->Search_model->words_condition($filters['q'], array('name', 'full_name', 'email'));
            if ( $words_condition )
            {
                $this->db->where($words_condition);
            }
            
        //Orden
            if ( $filters['o'] != '' )
            {
                $order_type = $this->pml->if_strlen($filters['ot'], 'ASC');
                $this->db->order_by($filters['o'], $order_type);
            } else {
                $this->db->order_by('edited_at', 'DESC');
            }
            
        //Filtros
            $this->db->where($role_filter); //Filtro según el rol de user en sesión
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
        if ( is_null($per_page) )
        {
            $query = $this->db->get('institution'); //Resultados totales
        } else {
            $query = $this->db->get('institution', $per_page, $offset); //Resultados por página
        }
        
        return $query;
        
    }
    
    /**
     * Devuelve la cantidad de registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     * 
     * @param type $filters
     * @return type
     */
    function search_num_rows($filters)
    {
        $query = $this->search($filters); //Para calcular el total de resultados
        return $query->num_rows();
    }
    
    /**
     * Devuelve segmento SQL
     * 
     * @param type $institution_id
     * @return type 
     */
    function role_filter()
    {
        
        $role = $this->session->userdata('role');
        $condition = 'institution.id > 0';  //Valor por defecto, ningún user, se obtendrían cero instituciones.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los user
            $condition = 'institution.id > 0';
        } elseif ( $role == 11 )  {   //Propietario
            $condition = 'creator_id = ' . $this->session->userdata('user_id');
        }
        
        return $condition;
    }
    
    /**
     * Array con options para ordenar el listado de user en la vista de
     * exploración
     * 
     * @return string
     */
    function order_options()
    {
        $order_options = array(
            '' => '[ Ordenar por ]',
            'id' => 'ID Usuario',
            'name' => 'Nombre'
        );
        
        return $order_options;
    }
    
    /**
     * Establece si un usuario en sesión puede o no editar los datos de una institución
     */
    function editable($institution_id)
    {
        $editable = FALSE;
        if ( $this->session->userdata('role') <= 2 ) { $editable = TRUE; }
        if ( $this->session->userdata('institution_id') == $institution_id ) { $editable = TRUE; }

        return $editable;
    }

    /**
     * Opciones de instituciones en campos de autollenado
     * 2019-11-27
     */
    function autocomplete($filters, $limit = 15)
    {
        $role_filter = $this->role_filter();

        //Construir búsqueda
        //Crear array con términos de búsqueda
            if ( strlen($filters['q']) > 2 )
            {
                $words = $this->Search_model->words($filters['q']);

                foreach ($words as $word) {
                    $this->db->like('CONCAT(name, full_name, id_number)', $word);
                }
            }
        
        //Especificaciones de consulta
            //$this->db->select('id, CONCAT((display_name), " (",(username), ") Cod: ", IFNULL(code, 0)) AS value');
            $this->db->select('id, CONCAT((name), " (",(full_name), ")") AS value');
            $this->db->where($role_filter); //Filtro según el rol de usuario que se tenga
            $this->db->order_by('name', 'ASC');
            
        //Otros filtros
            if ( $filters['condition'] != '' ) { $this->db->where($filters['condition']); }    //Condición adicional
            
        $query = $this->db->get('institution', $limit); //Resultados por página
        
        return $query;
    }

// CRUD
//-----------------------------------------------------------------------------
    
    /**
     * Insertar un registro en la tabla institution.
     * 2019-10-31
     * 
     * @param type $arr_row
     * @return type
     */
    function insert($arr_row = NULL)
    {
        if ( is_null($arr_row) ) { $arr_row = $this->arr_row('insert'); }
        
        //Insert in table
            $this->db->insert('institution', $arr_row);
            $institution_id = $this->db->insert_id();

        //Set result
            $data = array('status' => 1, 'message' => 'Institución creada', 'institution_id' => $institution_id);
        
        return $data;
    }

    /**
     * Actualiza un registro en la tabla institution
     * 2019-10-30
     * 
     * @param type $arr_row
     * @return type
     */
    function update($institution_id, $arr_row = NULL)
    {
        if ( is_null($arr_row) ) { $arr_row = $this->arr_row('update'); }

        $data_validation = $this->validate($institution_id, $arr_row);  //Validar datos
        $data = $data_validation;
        
        if ( $data['status']  )
        {
            //Actualizar
                $this->db->where('id', $institution_id);
                $this->db->update('institution', $arr_row);
            
            //Preparar resultado
                $data['message'] = 'Los datos de la institución fueron guardados';
        }
        
        return $data;
    }

    /**
     * Array con datos para editar o crear un registro de una institución
     * 2019-10-29
     */
    function arr_row($process = 'update')
    {
        $this->load->model('Account_model');
        
        $arr_row = $this->input->post();
        $arr_row['editor_id'] = $this->session->userdata('user_id');
        
        if ( $process == 'insert' )
        {
            $arr_row['creator_id'] = $this->session->userdata('user_id');
        }
        
        return $arr_row;
    }
    
    function deletable()
    {
        $deletable = 0;
        if ( $this->session->userdata('role') <= 1 ) { $deletable = 1; }

        return $deletable;
    }

    /**
     * Eliminar un usuario de la base de datos, se elimina también de
     * las tablas relacionadas
     */
    function delete($institution_id)
    {
        $quan_deleted = 0;

        if ( $this->deletable($institution_id) ) 
        {
            //Tablas relacionadas

                //meta
                /*$this->db->where('table_id', 1000); //Tabla usuario
                $this->db->where('institution_id', $institution_id);
                $this->db->delete('meta');*/
            
            //Tabla principal
                $this->db->where('id', $institution_id);
                $this->db->delete('institution');

            $quan_deleted = $this->db->affected_rows();
        }

        return $quan_deleted;
    }

// VALIDATION
//-----------------------------------------------------------------------------

    

// VALIDATION
//-----------------------------------------------------------------------------

    /**
     * Valida datos de una institución nueva o existente, verificando validez respecto
     * a users ya existentes en la base de datos.
     */
    function validate($institution_id = NULL)
    {
        $data = array('status' => 1, 'message' => 'Los datos de la institución son válidos');
        
        $email_validation = $this->email_validation($this->input->post('email'), $institution_id);
        $id_number_validation = $this->id_number_validation($this->input->post('id_number'), $institution_id);

        $validation = array_merge($email_validation, $id_number_validation);
        $data['validation'] = $validation;

        foreach ( $validation as $value )
        {
            if ( $value == FALSE ) 
            {
                $data['status'] = 0;
                $data['message'] = 'Los datos de la institución NO son válidos';
            }
        }

        return $data;
    }

    /**
     * Valida que username sea único, si se incluye un ID Institution existente
     * lo excluye de la comparación cuando se realiza edición
     */
    function email_validation($email, $institution_id = null)
    {
        $validation['email_is_unique'] = $this->Db_model->is_unique('institution', 'email', $email, $institution_id);
        return $validation;
    }

    /**
     * Valida que número de identificacion (id_number) sea único, si se incluye un ID Institution existente
     * lo excluye de la comparación cuando se realiza edición
     */
    function id_number_validation($id_number, $institution_id = null)
    {
        $validation['id_number_is_unique'] = $this->Db_model->is_unique('institution', 'id_number', $id_number, $institution_id);
        return $validation;
    }

//IMAGEN DE PERFIL DE LA INSTITUCIÓN
//---------------------------------------------------------------------------------------------------
    
    /**
     * Asigna una imagen registrada en la tabla archivo como imagen de perfil de la institución
     * 
     * @param type $institution_id
     * @param type $file_id
     */
    function set_image($institution_id, $file_id)
    {
        $data = array('status' => 0, 'message' => 'La imagen no fue asignada'); //Resultado inicial

        $row_file = $this->Db_model->row_id('file', $file_id);
            
        $arr_row['image_id'] = $row_file->id;
        
        $this->db->where('id', $institution_id);
        $this->db->update('institution', $arr_row);
        
        if ( $this->db->affected_rows() )
        {
            $data = array('status' => 1, 'message' => 'La imagen de perfil fue asignada');
            $data['src'] = URL_UPLOADS . $row_file->folder . $row_file->file_name;  //URL de la imagen cargada
        }


        return $data;
    }
    
    /**
     * Le quita la imagen de perfil asignada a una institución, eliminado el archivo
     * correspondiente
     * 2019-10-30
     * 
     * @param type $institution_id
     * @return int
     */
    function remove_image($institution_id)
    {
        $data['status'] = 0;
        $row = $this->Db_model->row_id('institution', $institution_id);
        
        if ( ! is_null($row->image_id) )
        {
            $this->db->where('id', $institution_id);
            $this->db->update('institution', array('image_id' => 0));

            $this->load->model('File_model');
            $this->File_model->delete($row->image_id);
            $data['status'] = 1;
        }
        
        return $data;
    }

    /**
     * Establecer una institución como la principal de un usuario, actualiza el campo usser.institution_id
     * y agrega institution_id a las variables de sesión.
     * 2019-10-31
     */
    function set_main($institution_id)
    {
        //Actualizar tabla usuario
        if ( $this->session->userdata('role') > 10 )
        {
            $arr_row['institution_id'] = $institution_id;

            $this->db->where('id', $this->session->userdata('user_id'));
            $this->db->update('user', $arr_row);
        }

        //Establecer variable de sesión
            $this->session->set_userdata('institution_id', $institution_id);
    }

    /**
     * Cambia el rol de un usuario como propietario de una institución. Inicia nueva sesión con ese rol.
     * 2019-11-28
     */
    function set_owner($institution_id)
    {
        //Actualizar el rol
            $arr_row['role'] = 11;  //Propietario
            $this->db->where('id', $this->session->userdata('user_id'));
            $this->db->update('user', $arr_row);

        //Destruir sesión y crear una nueva, como propietario
            $username = $this->session->userdata('username');
            //$this->session->sess_destroy();
            $this->load->model('Account_model');
            $data_session = $this->Account_model->create_session($username, FALSE);

            return $data_session;
    }

    /**
     * Crea una solicitud de vinculcación de un usuario a una institución, con un rol específico
     * Se guarda en la tabla user_meta con el tipo 1053.
     * 2019-11-27
     */
    function require_join($institution_id, $user_id, $role)
    {
        $data = array('status' => 0, 'meta_id' => '0'); //Resultado por defecto

        //Construir registro
        $arr_row['user_id'] = $user_id;
        $arr_row['type_id'] = 1053;
        $arr_row['related_1'] = $institution_id;
        $arr_row['cat_1'] = $role;
        $arr_row['editor_id'] = $this->session->userdata('user_id');
        $arr_row['creator_id'] = $this->session->userdata('user_id');

        //Guardar solicitud
        $this->load->model('User_model');
        $meta_id = $this->User_model->save_meta($arr_row);

        //Actualizar resultado
        if ( $meta_id > 0 ) { $data = array('status' => 1, 'meta_id' => $meta_id);}

        return $data;
    }

// Calendarios
//-----------------------------------------------------------------------------

    /**
     * Calendarios escolares de una institución, tabla post, tipo 4021
     * 2019-11-29
     */
    function calendars($institution_id)
    {
        $this->db->select('id, post_name, LEFT(date_1, 10) AS date_1, date_2, integer_1');
        $this->db->where('related_1', $institution_id);
        $this->db->where('type_id', 4021);  //Tipo calendario
        $calendars = $this->db->get('post');

        return $calendars;
    }
}