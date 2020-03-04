<?php
class Student_model extends CI_Model{

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
            $data = $this->get($num_page);
        
        //Elemento de exploración
            $data['controller'] = 'students';                      //Nombre del controlador
            $data['cf'] = 'students/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'students/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Estudiantes';
            $data['head_subtitle'] = $data['search_num_rows'];
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    function get($num_page)
    {
        //Referencia
            $per_page = 10;                             //Cantidad de registros por página
            $offset = ($num_page - 1) * $per_page;      //Número de la página de datos que se está consultado

        //Búsqueda y Resultados
            $this->load->model('Search_model');
            $data['filters'] = $this->Search_model->filters();
            $elements = $this->search($data['filters'], $per_page, $offset);    //Resultados para página
        
        //Cargar datos
            $data['list'] = $elements->result();
            $data['str_filters'] = $this->Search_model->str_filters();
            $data['search_num_rows'] = $this->search_num_rows($data['filters']);
            $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'],1) / $per_page);   //Cantidad de páginas

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
            $this->db->select('user.id, username, display_name, id_number, email, group_id, src_image, src_thumbnail, user.status, code, groups.title AS group_title');
            $this->db->join('groups', 'user.group_id = groups.id', 'left');
        
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
                $this->db->order_by('user.edited_at', 'DESC');
            }
            
        //Filtros
            $this->db->where('role', 23);   //Estudiante
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
            $condition = 'user.institution_id = ' . $this->session->userdata('institution_id');
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

// IMPORTAR
//-----------------------------------------------------------------------------}

    /**
     * Array con configuración de la vista de importación según el tipo de usuario
     * que se va a importar.
     * 2019-11-20
     */
    function import_config($type)
    {
        $data = array();

        if ( $type == 'students' )
        {
            $data['help_note'] = 'Se importarán estudiantes a la plataforma asignándolos a grupos existentes.';
            $data['help_tips'] = array();
            $data['template_file_name'] = 'f02_estudiantes.xlsx';
            $data['sheet_name'] = 'estudiantes';
            $data['head_subtitle'] = 'Importar estudiantes';
            $data['destination_form'] = "users/import_e/{$type}";
        }

        return $data;
    }

    /**
     * Importa usuarios a la base de datos
     * 2019-11-21
     */
    function import($arr_sheet)
    {
        $data = array('qty_imported' => 0, 'results' => array());
        $this->load->model('Group_model');
        
        foreach ( $arr_sheet as $key => $row_data )
        {
            $data_import = $this->import_student($row_data);
            $data['qty_imported'] += $data_import['status'];
            $data['results'][$key + 2] = $data_import;
        }
        
        return $data;
    }

    /**
     * Realiza la importación de una fila del archivo excel. Valida los campos, crea registro
     * en la tabla user, y agrega al grupo asignado.
     * 2019-11-21
     */
    function import_student($row_data)
    {
        //Validar
            $error_text = '';
                            
            if ( strlen($row_data[0]) == 0 ) { $error_text = 'La casilla Nombre está vacía. '; }
            if ( strlen($row_data[1]) == 0 ) { $error_text .= 'La casilla Apellido está vacía. '; }
            if ( strlen($row_data[2]) == 0 ) { $error_text .= 'La casilla No Documento está vacía. '; }
            if ( ! $this->Db_model->is_unique('user', 'id_number', $row_data[2]) ) { $error_text .= 'El No Documento (' . $row_data[2] . ') ya está registrado. '; }

        //Identificar grupo
            $row_group = $this->Db_model->row('groups', "id = '{$row_data[7]}'");
            if ( is_null($row_group) ) {
                $error_text .= 'El ID de grupo (' . $row_data[7] . ') es incorrecto o no existe. ';
            } else {
                //El grupo existe, pero debe ser de la misma institución del usuario
                if ( $row_group->institution_id != $this->session->userdata('institution_id') ) { $error_text .= 'Tu institución no tiene un grupo con ID: ' . $row_data[7]; }
            }

        //Si no hay error
            if ( $error_text == '' )
            {
                $arr_row['first_name'] = $row_data[0];
                $arr_row['last_name'] = $row_data[1];
                $arr_row['display_name'] = $row_data[0] . ' ' . $row_data[1];
                $arr_row['id_number'] = $row_data[2];
                $arr_row['username'] = $this->User_model->generate_username($arr_row['first_name'], $arr_row['last_name']);
                $arr_row['institution_id'] = $row_group->institution_id;
                $arr_row['id_number_type'] = $row_data[3];
                $arr_row['gender'] = $row_data[4];
                $arr_row['birth_date'] = date('Y-m-d H:i:s', $this->pml->dexcel_unix($row_data[5]));
                $arr_row['code'] = $row_data[6];
                $arr_row['creator_id'] = $this->session->userdata('user_id');
                $arr_row['editor_id'] = $this->session->userdata('user_id');

                //Guardar en tabla user
                $data_insert = $this->User_model->insert($arr_row);

                //Agregar al grupo
                $this->Group_model->add_student($row_data[7], $data_insert['saved_id']);

                $data = array('status' => 1, 'text' => '', 'imported_id' => $data_insert['saved_id']);
            } else {
                $data = array('status' => 0, 'text' => $error_text, 'imported_id' => 0);
            }

        return $data;
    }

// Gestión de Familiares y acudientes
//-----------------------------------------------------------------------------

    /**
     * Query listado de familiares de un estudiante.
     * 2019-11-26
     */
    function relatives($user_id)
    {
        $this->db->select('user.id, display_name, username, email, phone_number, src_thumbnail, item_name AS relation_type');
        $this->db->join('user_meta', "user.id = user_meta.related_1 AND user_meta.user_id = {$user_id}");
        $this->db->join('item', 'item.cod = user_meta.cat_1 AND item.category_id = 171');
        $this->db->where('user_meta.type_id', 1051);
        $relatives = $this->db->get('user');

        return $relatives;
    }

    /**
     * Le asigna un familiar o acudiente a un estudiante
     * 2019-11-26
     */
    function add_relative($user_id, $relative_id, $relation_type)
    {
        $data = array('status' => 0, 'meta_id' => '0');    //Resultado inicial por defecto

        //Construir registro y guardar
            $arr_row['user_id'] = $user_id;
            $arr_row['type_id'] = 1051;             //Familiar
            $arr_row['related_1'] = $relative_id;   //ID del usuario familiar
            $arr_row['cat_1'] = $relation_type;     //Tipo de familiar
            $arr_row['creator_id'] = $this->session->userdata('user_id');
            $arr_row['editor_id'] = $this->session->userdata('user_id');

            $meta_id = $this->save_meta($arr_row);

        //Actualizar resultado
        if ( $meta_id ) { $data = array('status' => 1, 'meta_id' => $meta_id); }

        return $data;
    }
    
}