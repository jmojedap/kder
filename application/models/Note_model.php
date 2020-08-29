<?php
class Note_model extends CI_Model{

    function basic($note_id)
    {
        $data['note_id'] = $note_id;
        $data['row'] = $this->Db_model->row_id('post', $note_id);
        $data['row_user'] = $this->Db_model->row_id('user', $data['row']->related_1);
        $data['head_title'] = $data['row']->post_name;
        $data['view_a'] = 'notes/user_v';
        $data['nav_2'] = 'notes/menu_v';

        return $data;
    }

// EXPLORE FUNCTIONS - notes/explore
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
            $data['controller'] = 'notes';                      //Nombre del controlador
            $data['cf'] = 'notes/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'notes/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Anotaciones';
            $data['head_subtitle'] = $data['search_num_rows'];
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    function get($num_page)
    {
        //Referencia
            $per_page = 8;                             //Cantidad de registros por página
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
        
        //Cliente asociado
        if ( $filters['u'] != '' ) { $condition .= "related_1 = {$filters['u']} AND "; }
        if ( $filters['type'] != '' ) { $condition .= "notes.cat_1 = {$filters['type']} AND "; }
        
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
            $this->db->select('notes.id, post_name, excerpt, notes.cat_1, notes.status, related_1 AS user_id, notes.updater_id, notes.updated_at, notes.creator_id, notes.created_at, user.display_name AS creator_display_name, user.url_thumbnail AS creator_url_thumbnail, client.display_name AS client_display_name, client.url_thumbnail AS client_url_thumbnail');
            $this->db->join('user', 'user.id = notes.creator_id');
            $this->db->join('user AS client', 'client.id = notes.related_1');
        
        //Crear array con términos de búsqueda
            $words_condition = $this->Search_model->words_condition($filters['q'], array('post_name', 'excerpt', 'content'));
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
                $this->db->order_by('updated_at', 'DESC');
            }
            
        //Filtros
            $this->db->where($role_filter); //Filtro según el rol de user en sesión
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
        if ( is_null($per_page) )
        {
            $query = $this->db->get('notes'); //Resultados totales
        } else {
            $query = $this->db->get('notes', $per_page, $offset); //Resultados por página
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
     * @param type $note_id
     * @return type 
     */
    function role_filter()
    {
        
        $role = $this->session->userdata('role');
        $condition = 'notes.id = 0';  //Valor por defecto, ningún user, se obtendrían cero user.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los user
            $condition = 'notes.id > 0';
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
            'id' => 'ID Nota',
            'post_name' => 'Título'
        );
        
        return $order_options;
    }
    
    function editable()
    {
        return TRUE;
    }


// CRUD
//-----------------------------------------------------------------------------

    /**
     * Actualiza un registro en la tabla.
     * 2020-02-25
     */
    function save($note_id)
    {
        $arr_row = $this->arr_row($note_id);
        $data['saved_id'] = $this->Db_model->save('post', "id = {$note_id}", $arr_row);

        //Resultado
        $data['status'] = 0;
        if ( $data['saved_id'] > 0 ) { $data['status'] = 1;}
        
        return $data;
    }
    
    function deletable()
    {
        $deletable = 1;
        if ( $this->session->userdata('role') <= 1 ) { $deletable = 1; }

        return $deletable;
    }

    /**
     * Eliminar una anotación de la base de datos, se elimina también de
     * las tablas relacionadas
     */
    function delete($note_id)
    {
        $qty_deleted = 0;

        if ( $this->deletable($note_id) ) 
        {
            $this->db->where('id', $note_id);
            $this->db->delete('post');
            $qty_deleted = 1;
        }

        return $qty_deleted;
    }

    /**
     * Array con los datos para crear o editar un registro en la vista notes, tabla post 
     * 2020-01-30
     */
    function arr_row($note_id)
    {
        $arr_row = $this->input->post();
        $arr_row['type_id'] = 1010; //Anotación sobre usuario
        $arr_row['updater_id'] = $this->session->userdata('user_id');
        $arr_row['updated_at'] = date('Y-m-d H:i:s');
        
        if ( $note_id == 0 )
        {
            $arr_row['created_at'] = date('Y-m-d H:i:s');
            $arr_row['creator_id'] = $this->session->userdata('user_id');
        }
        
        return $arr_row;
    }
}