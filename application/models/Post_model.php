<?php
class Post_model extends CI_Model{

    function basic_data($post_id)
    {
        $row = $this->Db_model->row_id('post', $post_id);

        $data['post_id'] = $post_id;
        $data['row'] = $row;
        $data['att_image'] = $this->att_image($row);
        $data['head_title'] = $data['row']->post_name;
        $data['view_a'] = 'posts/post_v';
        $data['nav_2'] = 'posts/menu_v';

        return $data;
    }

// CRUD
//-----------------------------------------------------------------------------
    
    /**
     * Insertar un registro en la tabla post.
     * 
     * @param type $arr_row
     * @return type
     */
    function insert($arr_row = NULL)
    {
        if ( is_null($arr_row) ) { $arr_row = $this->arr_row('insert'); }
        
        //Insert in table
            $this->db->insert('post', $arr_row);
            $post_id = $this->db->insert_id();

        //Set result
            $result['status'] = 1;
            $result['post_id'] = $post_id;
            $result['message'] = 'Post creado';
        
        return $result;
    }

    /**
     * Actualiza un registro en la tabla.
     * 
     * @param type $arr_row
     * @return type
     */
    function update($post_id, $arr_row)
    {   
        $arr_row = $this->Db_model->arr_row();

        //Actualizar
            $this->db->where('id', $post_id);
            $this->db->update('post', $arr_row);

        $data = array('status' => 1, 'message' => 'Los cambios fueron guardados');
        
        return $data;
    }

    /**
     * Actualiza un registro en la tabla post
     * 2019-11-29
     */
    function save($post_id)
    {
        $data = array('status' => 0, 'message' => 'Ocurrió un error al guardar');

        //Guardar
            $arr_row = $this->Db_model->arr_row($post_id);
            $saved_id = $this->Db_model->save('post', "id = {$post_id}", $arr_row);

        //Actualizar resultado
            if ( $saved_id > 0 ){
                $data = array('status' => 1, 'message' => 'Los cambios fueron guardados', 'saved_id' => $saved_id);
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
    function delete($post_id)
    {
        $quan_deleted = 0;

        if ( $this->deletable($post_id) ) 
        {
            //Tablas relacionadas
            
            //Tabla principal
                $this->db->where('id', $post_id);
                $this->db->delete('post');

            $quan_deleted = $this->db->affected_rows();
        }

        return $quan_deleted;
    }
    
// EXPLORE FUNCTIONS - posts/explore
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
            $data['controller'] = 'posts';                      //Nombre del controlador
            $data['views_folder'] = 'posts/explore/';           //Carpeta donde están las vistas de exploración
            $data['head_title'] = 'Posts';
                
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
            $data['cf'] = 'posts/explore/';     //CF Controlador Función
            $data['adv_filters'] = array('type');
        
        //Paginación
            $data['num_page'] = $num_page;                  //Número de la página de datos que se está consultado
            $data['per_page'] = 15;                           //Cantidad de registros por página
            $offset = ($num_page - 1) * $data['per_page'];    //Número de la página de datos que se está consultado
        
        //Búsqueda y Resultados
            $this->load->model('Search_model');
            $data['filters'] = $this->Search_model->filters();
            $data['str_filters'] = $this->Search_model->str_filters();
            $data['elements'] = $this->Post_model->search($data['filters'], $data['per_page'], $offset);    //Resultados para página
            
        //Otros
            $data['search_num_rows'] = $this->Post_model->search_num_rows($data['filters']);
            $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'],1) / $data['per_page']);   //Cantidad de páginas
            $data['all_selected'] = '-'. $this->pml->query_to_str($data['elements'], 'id');           //Para selección masiva de todos los elementos de la página
            
        return $data;
    }
    
    /**
     * String con condición WHERE SQL para filtrar post
     * 
     * @param type $filters
     * @return type
     */
    function search_condition($filters)
    {
        $condition = NULL;
        
        //Tipo de post
        if ( $filters['type'] != '' ) { $condition .= "type_id = {$filters['type']} AND "; }
        
        if ( strlen($condition) > 0 )
        {
            $condition = substr($condition, 0, -5);
        }
        
        return $condition;
    }
    
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        
        $role_filter = $this->role_filter($this->session->userdata('post_id'));

        //Construir consulta
            //$this->db->select('id, post_name, except, ');
        
        //Crear array con términos de búsqueda
            $words_condition = $this->Search_model->words_condition($filters['q'], array('post_name', 'content', 'excerpt', 'keywords'));
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
            $this->db->where($role_filter); //Filtro según el rol de post en sesión
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
        if ( is_null($per_page) )
        {
            $query = $this->db->get('post'); //Resultados totales
        } else {
            $query = $this->db->get('post', $per_page, $offset); //Resultados por página
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
     * @param type $post_id
     * @return type 
     */
    function role_filter()
    {
        
        $role = $this->session->userdata('role');
        $condition = 'id = 0';  //Valor por defecto, ningún post, se obtendrían cero post.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los post
            $condition = 'id > 0';
        } elseif ( $role == 10 )  {   //Directivo
            $condition = 'institution_id = ' . $this->session->userdata('institution_id');
        } else {
            $condition = 'id = 0';
        }
        
        return $condition;
    }
    
    /**
     * Array con options para ordenar el listado de post en la vista de
     * exploración
     * 
     * @return string
     */
    function order_options()
    {
        $order_options = array(
            '' => '[ Ordenar por ]',
            'id' => 'ID Post',
            'post_name' => 'Nombre'
        );
        
        return $order_options;
    }
    
    function editable()
    {
        return TRUE;
    }

// VALIDATION
//-----------------------------------------------------------------------------

    function arr_row($process = 'update')
    {
        $arr_row = $this->input->post();
        $arr_row['editor_id'] = $this->session->userdata('user_id');
        $arr_row['slug'] = $this->Db_model->unique_slug($arr_row['post_name'], 'post');
        
        if ( $process == 'insert' )
        {
            $arr_row['creator_id'] = $this->session->userdata('user_id');
        }
        
        return $arr_row;
    }

// GESTIÓN DE IMAGEN
//-----------------------------------------------------------------------------

    function att_image($row)
    {
        $att_image = array(
            'src' => URL_IMG . 'app/nd.png',
            'alt' => 'Imagen del Post ' . $row->id,
            'onerror' => "this.src='" . URL_IMG . "app/nd.png'"
        );

        $row_file = $this->Db_model->row_id('file', $row->image_id);
        if ( ! is_null($row_file) )
        {
            $att_image['src'] = URL_UPLOADS . $row_file->folder . $row_file->file_name;
            $att_image['alt'] = $row_file->title;
        }

        return $att_image;
    }
    
    /**
     * Asigna una imagen registrada en la tabla archivo como imagen del post
     * 
     * @param type $post_id
     * @param type $file_id
     */
    function set_image($post_id, $file_id)
    {
        $data = array('status' => 0, 'message' => 'La imagen no fue asignada'); //Resultado inicial
        $row_file = $this->Db_model->row_id('file', $file_id);
        
        $arr_row['image_id'] = $row_file->id;
        
        $this->db->where('id', $post_id);
        $this->db->update('post', $arr_row);
        
        if ( $this->db->affected_rows() )
        {
            $data = array('status' => 1, 'message' => 'La imagen del post fue asignada');
            $data['src'] = URL_UPLOADS . $row_file->folder . $row_file->file_name;  //URL de la imagen cargada
        }

        return $data;
    }

    /**
     * Le quita la imagen asignada a un post, eliminado el archivo
     * correspondiente
     * 
     * @param type $post_id
     * @return int
     */
    function remove_image($post_id)
    {
        $data['status'] = 0;
        $row = $this->Db_model->row_id('post', $post_id);
        
        if ( ! is_null($row->image_id) )
        {
            $this->load->model('File_model');
            $this->File_model->delete($row->image_id);
            $data['status'] = 1;

            //Modificar Row en tabla Post
            $arr_row['image_id'] = 0;
            $this->db->where('image_id', $row->image_id);
            $this->db->update('post', $arr_row);
        }
        
        return $data;
    }
}