<?php
class   Event_model extends CI_Model{
    
// EXPLORE FUNCTIONS - events/explore
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
            $data['controller'] = 'events';                      //Nombre del controlador
            $data['views_folder'] = 'events/explore/';           //Carpeta donde están las vistas de exploración
            $data['head_title'] = 'Eventos';
                
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
            $data['cf'] = 'events/explore/';     //CF Controlador Función
            $data['adv_filters'] = array('type');
        
        //Paginación
            $data['num_page'] = $num_page;                  //Número de la página de datos que se está consultado
            $data['per_page'] = 15;                           //Cantidad de registros por página
            $offset = ($num_page - 1) * $data['per_page'];    //Número de la página de datos que se está consultado
        
        //Búsqueda y Resultados
            $this->load->model('Search_model');
            $data['filters'] = $this->Search_model->filters();
            $data['str_filters'] = $this->Search_model->str_filters();
            $data['elements'] = $this->Event_model->search($data['filters'], $data['per_page'], $offset);    //Resultados para página
            
        //Otros
            $data['search_num_rows'] = $this->Event_model->search_num_rows($data['filters']);
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
        
        //Tipo de evento
        if ( $filters['type'] != '' ) { $condition .= "type_id = {$filters['type']} AND "; }
        if ( $filters['u'] != '' ) { $condition .= "user_id = {$filters['u']} AND "; }
        
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
            $this->db->select('event.id, event.type_id, start, end, seconds, event.status, event.element_id, user_id, user.display_name');
            $this->db->join('user', 'event.user_id = user.id');
        
        //Crear array con términos de búsqueda
            /*$words_condition = $this->Search_model->words_condition($filters['q'], array('first_name', 'last_name', 'display_name', 'email'));
            if ( $words_condition )
            {
                $this->db->where($words_condition);
            }*/
            
        //Orden
            if ( $filters['o'] != '' )
            {
                $order_type = $this->pml->if_strlen($filters['ot'], 'ASC');
                $this->db->order_by($filters['o'], $order_type);
            } else {
                $this->db->order_by('event.created_at', 'DESC');
            }
            
        //Filtros
            $this->db->where($role_filter); //Filtro según el rol de user en sesión
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
        if ( is_null($per_page) )
        {
            $query = $this->db->get('event'); //Resultados totales
        } else {
            $query = $this->db->get('event', $per_page, $offset); //Resultados por página
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
        $condition = 'event.id = 0';  //Valor por defecto, ningún user, se obtendrían cero user.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los user
            $condition = 'event.id > 0';
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
            'id' => 'ID Evento',
            'start' => 'Inicio'
        );
        
        return $order_options;
    }

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Determina si un user tiene el permiso para eliminar un registro de event
     * 
     * @param type $event_id
     * @return boolean
     */
    function deletable($event_id)
    {   
        $deletable = FALSE;
        $row_event = $this->Db_model->row_id('event', $event_id);
        
        //El user creó el event
        if ( $row_event->creator_id == $this->session->userdata('user_id') ) {
            $deletable = TRUE;
        }
        
    //El user es aministrador
        if ( $this->session->userdata('rol_id') <= 1 ) { $deletable = TRUE; }
            
        return $deletable;
    }
    
    /**
     * Elimina un registro de event y sus registros relacionados en otras tablas
     * 
     * @param type $event_id
     * @return type
     */
    function delete($event_id)
    {
        $quan_deleted = 0;
        $deletable = $this->deletable($event_id);
        
        if ( $deletable ) 
        {
            //Tabla
                $this->db->where('id', $event_id);
                $this->db->delete('event');
                
            $quan_deleted = $this->db->affected_rows();
        }
            
        return $quan_deleted;
    }
    
    /**
     * Modifica el campo event.status para un registro específico
     * 
     * @param type $type_id
     * @param type $element_id
     * @param type $estado
     */
    function update_status($type_id, $element_id, $estado)
    {
        $arr_row['status'] = $status;
        
        $this->db->where('type_id', $type_id);
        $this->db->where('element_id', $element_id);
        $this->db->update('event', $arr_row);
    }
    
    /**
     * Guarda un registro en la tabla event
     * 
     * @param type $arr_row
     * @return type
     */
    function save($arr_row, $condition_add = NULL)
    {
        //Condición para identificar el registro del event
            $condition = "type_id = {$arr_row['type_id']} AND element_id = {$arr_row['element_id']}";
            if ( ! is_null($condition_add) )
            {
                $condition .= " AND " . $condition_add;
            }
        
            $event_id = $this->Db_model->exists('event', $condition);
        
        //Guardar el event
        if ( $event_id == 0 )
        {
            //No existe, se inserta
            $arr_row['ip_address'] = $this->input->ip_address();
            $arr_row['created_at'] = date('Y-m-d H:i:s');
            $arr_row['creator_id'] = $this->pml->if_strlen($this->session->userdata('user_id'), 0);
            
            $this->db->insert('event', $arr_row);
            $event_id = $this->db->insert_id();
        } else {
            //Ya existe, editar
            $this->db->where('id', $event_id);
            $this->db->update('event', $arr_row);
        }
        
        return $event_id;
    }
    
    /**
     * Devuelve array con datos registro base, para crear o editar un registro
     * de event, datos primordiales, comunes.
     * 
     */
    function basic_row()
    {
        $user_id = 0;
        if ($this->session->userdata('logged') ) { $user_id = $this->session->userdata('user_id'); }
        
        $start = date('Y-m-d H:i:s');
        
        $arr_row['user_id'] = $user_id;
        $arr_row['ip_address'] = $this->input->ip_address();
        $arr_row['start'] = $start;
        $arr_row['created_at'] = $start;
        $arr_row['creator_id'] = $user_id;
        
        return $arr_row;
    }
    
// DATOS
//---------------------------------------------------------------------------------------------------------
    
    function quan_events($filters)
    {
        if ( $filters['u'] != '' ) { $this->db->where('user_id', $filters['u']); }   //Usuario
        if ( $filters['t'] != '' ) { $this->db->where('type_id', $filters['t']); }      //Tipo
        
        $query = $this->db->get('event');
        
        return $query->num_rows();
    }
    
    function row_event($arr_conditions)
    {
        //Valor por defecto
        $row = NULL;
        
        $this->db->where($arr_conditions);
        $query = $this->db->get('event');
        if ( $query->num_rows() > 0 ){ $row = $query->row(); }
        
        return $row;
    }
    
    /**
     * Cantidad de seconds entre la fecha y hora de start, y una fecha y hora 
     * determinados
     */
    function seconds($row_event, $end_date)
    {
        $seconds = $this->pml->seconds($row_event->start, $end_date);
        
        return $seconds;
    }
    
// GESTIÓN DE EVENTO LOGIN
//-----------------------------------------------------------------------------
    
    /**
     * Crea un registro en la tabla event, asociado al start de sesión
     * @return type
     */
    function save_ev_login()
    {
        $row_user = $this->Db_model->row_id('user', $this->session->userdata('user_id'));
        
        //Registro, valores generales
            $arr_row['type_id'] = 101;   //Login de usuario
            $arr_row['start'] = date('Y-m-d H:i:s');
            $arr_row['element_id'] = $row_user->id;
            $arr_row['user_id'] = $row_user->id;
            $arr_row['status'] = 1;    //Login iniciado

            $condition_add = 'id = 0';  //Se pone una condición adicional incumplible, para que siempre agregue el registro
            $event_id = $this->save($arr_row, $condition_add);
        
        //Agregar event_id a los datos de sesión
            $this->session->set_userdata('login_id', $event_id);
        
        return $event_id;
    }
}