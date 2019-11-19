<?php
class User_model extends CI_Model{

    function basic($user_id)
    {
        $data['user_id'] = $user_id;
        $data['row'] = $this->Db_model->row_id('user', $user_id);
        $data['head_title'] = $data['row']->display_name;
        $data['view_a'] = 'users/user_v';
        $data['nav_2'] = 'users/menus/user_v';

        if ( $data['row']->role == 13 ) { $data['nav_2'] = 'users/menus/model_v'; }

        return $data;
    }

// EXPLORE FUNCTIONS - users/explore
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
            $data['controller'] = 'users';                      //Nombre del controlador
            $data['views_folder'] = 'users/explore/';           //Carpeta donde están las vistas de exploración
            $data['head_title'] = 'Usuarios';
                
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
            $data['cf'] = 'users/explore/';     //CF Controlador Función
            $data['adv_filters'] = array('role');
        
        //Paginación
            $data['num_page'] = $num_page;                  //Número de la página de datos que se está consultado
            $data['per_page'] = 15;                           //Cantidad de registros por página
            $offset = ($num_page - 1) * $data['per_page'];    //Número de la página de datos que se está consultado
        
        //Búsqueda y Resultados
            $this->load->model('Search_model');
            $data['filters'] = $this->Search_model->filters();
            $data['str_filters'] = $this->Search_model->str_filters();
            $data['elements'] = $this->User_model->search($data['filters'], $data['per_page'], $offset);    //Resultados para página
            
        //Otros
            $data['search_num_rows'] = $this->User_model->search_num_rows($data['filters']);
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
            $this->db->select('id, username, display_name, first_name, last_name, city_id, id_number, email, role, image_id, src_image, src_thumbnail, status, code');
        
        //Crear array con términos de búsqueda
            $words_condition = $this->Search_model->words_condition($filters['q'], array('first_name', 'last_name', 'display_name', 'email', 'id_number', 'code'));
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
            $query = $this->db->get('user'); //Resultados totales
        } else {
            $query = $this->db->get('user', $per_page, $offset); //Resultados por página
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
     * @param type $user_id
     * @return type 
     */
    function role_filter()
    {
        
        $role = $this->session->userdata('role');
        $condition = 'id = 0';  //Valor por defecto, ningún user, se obtendrían cero user.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los user
            $condition = 'id > 0';
        } elseif ( $role == 11 )  {   //Directivo
            $condition = 'institution_id = ' . $this->session->userdata('institution_id');
        } else {
            $condition = 'id = 0';
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
            'last_name' => 'Apellidos',
            'id_number' => 'No. documento',
        );
        
        return $order_options;
    }
    
    function editable()
    {
        return TRUE;
    }

    /**
     * Opciones de usuario en campos de autollenado, como agregar usuarios a una conversación
     * 2019-11-13
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
                    $this->db->like('CONCAT(first_name, last_name, username, code)', $word);
                }
            }
        
        //Especificaciones de consulta
            //$this->db->select('id, CONCAT((display_name), " (",(username), ") Cod: ", IFNULL(code, 0)) AS value');
            $this->db->select('id, CONCAT((display_name), " (",(username), ")") AS value');
            $this->db->where($role_filter); //Filtro según el rol de usuario que se tenga
            $this->db->order_by('last_name', 'ASC');
            
        //Otros filtros
            if ( $filters['condition'] != '' ) { $this->db->where($filters['condition']); }    //Condición adicional
            
        $query = $this->db->get('user', $limit); //Resultados por página
        
        return $query;
    }

// CRUD
//-----------------------------------------------------------------------------
    
    /**
     * Insertar un registro en la tabla user.
     * 
     * @param type $arr_row
     * @return type
     */
    function insert($arr_row = NULL)
    {
        if ( is_null($arr_row) ) { $arr_row = $this->arr_row('insert'); }
        
        //Insert in table
            $this->db->insert('user', $arr_row);
            $user_id = $this->db->insert_id();

        //Set result
            $data = array('status' => 1, 'message' => 'Usuario creado', 'saved_id' => $user_id);
        
        return $data;
    }

    /**
     * Actualiza un registro en la tabla.
     * 
     * @param type $arr_row
     * @return type
     */
    function update($user_id, $arr_row)
    {
        $this->load->model('Account_model');
        $data_validation = $this->validate_row($user_id, $arr_row);  //Validar datos
        
        $data = $data_validation;
        
        if ( $data['status'] )
        {
            //Actualizar
                $this->db->where('id', $user_id);
                $this->db->update('user', $arr_row);
            
            //Preparar resultado
                $data['message'] = 'Los datos fueron guardados';
        }
        
        return $data;
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
    function delete($user_id)
    {
        $quan_deleted = 0;

        if ( $this->deletable($user_id) ) 
        {
            //Tablas relacionadas

                //meta
                /*$this->db->where('table_id', 1000); //Tabla usuario
                $this->db->where('user_id', $user_id);
                $this->db->delete('meta');*/
            
            //Tabla principal
                $this->db->where('id', $user_id);
                $this->db->delete('user');

            $quan_deleted = $this->db->affected_rows();
        }

        return $quan_deleted;
    }

    /**
     * Array con los datos para crear o editar un registro en la tabla user
     * 2019-11-06
     */
    function arr_row($process = 'update')
    {
        $this->load->model('Account_model');
        
        $arr_row = $this->input->post();
        
        $arr_row['editor_id'] = $this->session->userdata('user_id');

        //Si se le estableció contraseña
        if ( isset($arr_row['password']) )
        {
            $arr_row['password'] = $this->Account_model->crypt_pw($arr_row['password']);
        }

        if ( ! isset($arr_row['display_name']) ) { $arr_row['display_name'] = $arr_row['first_name'] . ' ' . $arr_row['last_name']; }
        
        if ( $process == 'insert' )
        {
            $arr_row['creator_id'] = $this->session->userdata('user_id');
        }
        
        return $arr_row;
    }

// VALIDACIÓN
//-----------------------------------------------------------------------------

    /**
     * Valida datos de un user nuevo o existente, verificando validez respecto
     * a users ya existentes en la base de datos.
     */
    function validate_row($user_id = NULL)
    {
        $data = array('status' => 1, 'message' => 'Los datos de usuario son válidos');
        
        $username_validation = $this->username_validation($this->input->post('username'), $user_id);
        $email_validation = $this->email_validation($this->input->post('email'), $user_id);
        $id_number_validation = $this->id_number_validation($this->input->post('id_number'), $user_id);

        $validation = array_merge($username_validation, $email_validation, $id_number_validation);
        $data['validation'] = $validation;

        foreach ( $validation as $value )
        {
            if ( $value == FALSE ) 
            {
                $data['status'] = 0;
                $data['message'] = 'Los datos de usuario NO son válidos';
            }
        }

        return $data;
    }

    /**
     * Valida que username sea único, si se incluye un ID User existente
     * lo excluye de la comparación cuando se realiza edición
     */
    function username_validation($username, $user_id = null)
    {
        $validation['username_is_unique'] = $this->Db_model->is_unique('user', 'username', $username, $user_id);
        return $validation;
    }

    /**
     * Valida que username sea único, si se incluye un ID User existente
     * lo excluye de la comparación cuando se realiza edición
     * 2019-10-29
     */
    function email_validation($email, $user_id = null)
    {
        $validation['email_is_unique'] = $this->Db_model->is_unique('user', 'email', $email, $user_id);

        return $validation;
    }

    /**
     * Valida que número de identificacion (id_number) sea único, si se incluye un ID User existente
     * lo excluye de la comparación cuando se realiza edición
     */
    function id_number_validation($id_number, $user_id = null)
    {
        $validation['id_number_is_unique'] = $this->Db_model->is_unique('user', 'id_number', $id_number, $user_id);
        return $validation;
    }

//IMAGEN DE PERFIL DE USUARIO
//---------------------------------------------------------------------------------------------------
    
    /**
     * Asigna una imagen registrada en la tabla archivo como imagen de perfil del usuario
     * 
     * @param type $user_id
     * @param type $file_id
     */
    function set_image($user_id, $file_id)
    {
        $data = array('status' => 0, 'message' => 'La imagen no fue asignada'); //Resultado inicial
        $row_file = $this->Db_model->row_id('file', $file_id);
        
        $arr_row['image_id'] = $row_file->id;
        $arr_row['src_image'] = $row_file->folder . $row_file->file_name;
        $arr_row['src_thumbnail'] = $row_file->folder . 'sm_' . $row_file->file_name;
        
        $this->db->where('id', $user_id);
        $this->db->update('user', $arr_row);
        
        if ( $this->db->affected_rows() )
        {
            $data = array('status' => 1, 'message' => 'La imagen de perfil fue asignada');
            $data['src'] = URL_UPLOADS . $row_file->folder . $row_file->file_name;  //URL de la imagen cargada
        }

        return $data;
    }
    
    /**
     * Le quita la imagen de perfil asignada a un usuario, eliminado el archivo
     * correspondiente
     * 
     * @param type $user_id
     * @return int
     */
    function remove_image($user_id)
    {
        $data['status'] = 0;
        $row = $this->Db_model->row_id('user', $user_id);
        
        if ( ! is_null($row->image_id) )
        {
            $this->load->model('File_model');
            $this->File_model->delete($row->image_id);
            $data['status'] = 1;
        }
        
        return $data;
    }

// IMPORTAR
//-----------------------------------------------------------------------------

    /**
     * Importa usuarios a la base de datos
     * 
     * @param type $array_sheet    Array con los datos de usuarios
     * @return type
     */
    function import($arr_sheet)
    {
        $data = array('quan_imported' => 0, 'not_imported' => array());
        
        foreach ( $arr_sheet as $key => $row_data )
        {    
            //Validar
                $conditions = 0;
                if ( strlen($row_data[0]) > 0 ) { $conditions++; }       //Debe tener nombre escrito
                if ( strlen($row_data[1]) > 0 ) { $conditions++; }       //Debe tener apellido
                
            //Si cumple las conditions
            if ( $conditions == 2 )
            {
                $arr_row['first_name'] = $row_data[0];
                $arr_row['last_name'] = $row_data[1];

                $this->insert($arr_row);
                $data['quan_imported']++;
            } else {
                $data['not_imported'][] = $key + 2;    //Se agrega número de fila al array (inicia en la fila 2)
            }
        }
        
        return $data;
    }

// GENERAL
//-----------------------------------------------------------------------------

    function generate_username($first_name, $last_name)
    {
        //Sin espacios iniciales o finales
        $first_name = trim($first_name);
        $last_name = trim($last_name);
        
        //Sin acentos
        $this->load->helper('text');
        $first_name = convert_accented_characters($first_name);
        $last_name = convert_accented_characters($last_name);
        
        //Arrays con partes
        $arr_last_name = explode(" ", $last_name);
        $arr_first_name = explode(" ", $first_name);
        
        //Construyendo por partes
            $username = $arr_first_name[0];
            //if ( isset($arr_first_name[1]) ){ $username .= substr($arr_first_name[1], 0, 2);}
            
            //Apellidos
            $username .= '_' . $arr_last_name[0];
            //if ( isset($arr_last_name[1]) ){ $username .= substr($arr_last_name[1], 0, 2); }    
        
        //Reemplazando caracteres
            $username = str_replace (' ', '', $username); //Quitando espacios en blanco
            $username = strtolower($username); //Se convierte a minúsculas    
        
        //Verificar, si el username requiere un suffix numérico para hacerlo único
            $suffix = $this->username_suffix($username);
            $username .= $suffix;
        
        return $username;
    }

    /**
     * Devuelve un entero aleatorio de tres cifras cuando el username generado inicialmente (generate_username)
     * ya exista dentro de la plataforma.
     * 2019-11-05
     */
    function username_suffix($username)
    {
        $suffix = '';
        
        $condition = "username = '{$username}'";
        $qty_users = $this->Db_model->num_rows('user', $condition);

        if ( $qty_users > 0 ) {
            $this->load->helper('string');
            $suffix = random_string('numeric', 3);
        }
        
        return $suffix;
    }
    
}