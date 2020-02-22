<?php
class Period_model extends CI_Model{

    function basic($period_id)
    {
        $data['period_id'] = $period_id;
        $data['row'] = $this->Db_model->row_id('period', $period_id);
        $data['head_title'] = 'Cobro ' . $data['row']->title;
        $data['view_a'] = 'period/period_v';
        $data['nav_2'] = 'period/menu_v';

        return $data;
    }

// EXPLORE FUNCTIONS - period/explore
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
            $data['controller'] = 'period';                      //Nombre del controlador
            $data['cf'] = 'period/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'period/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Cobros';
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
            $data['list'] = $this->list($data['filters'], $per_page, $offset);    //Resultados para página
        
        //Cargar datos
            $data['str_filters'] = $this->Search_model->str_filters();
            $data['search_num_rows'] = $this->search_num_rows($data['filters']);
            $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'],1) / $per_page);   //Cantidad de páginas

        return $data;
    }

    /**
     * Array Listado elemento resultado de la búsqueda (filtros).
     * 2020-01-21
     */
    function list($filters, $per_page = NULL, $offset = NULL)
    {
        $query = $this->search($filters, $per_page, $offset);
        $list = array();

        foreach ($query->result() as $row) { $list[] = $row; }

        return $list;
    }
    
    /**
     * String con condición WHERE SQL para filtrar el elemento
     * 
     * @param type $filters
     * @return type
     */
    function search_condition($filters)
    {
        $condition = NULL;
        
        //Filtros
        if ( $filters['i'] != '' ) { $condition .= "institution_id = {$filters['i']} AND "; }
        
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
            //$this->db->select('id, name, title, level, teacher_id, institution_id, status');
        
        //Crear array con términos de búsqueda
            $words_condition = $this->Search_model->words_condition($filters['q'], array('notes'));
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
            $query = $this->db->get('period'); //Resultados totales
        } else {
            $query = $this->db->get('period', $per_page, $offset); //Resultados por página
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
     * @param type $period_id
     * @return type 
     */
    function role_filter()
    {
        
        $role = $this->session->userdata('role');
        $condition = 'id = 0';  //Valor por defecto, ningún user, se obtendrían cero user.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los user
            $condition = 'id > 0';
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
            'id' => 'ID Periodo',
            'name' => 'Nombre Periodo'
        );
        
        return $order_options;
    }
    
    /**
     * Establece si un usuario en sesión puede o no editar los datos de un period
     */
    function editable($period_id)
    {
        $editable = FALSE;
        if ( $this->session->userdata('role') <= 2 ) { $editable = TRUE; }
        //if ( $this->session->userdata('institution_id') == $period_id ) { $editable = TRUE; }

        return $editable;
    }

// CRUD
//-----------------------------------------------------------------------------

    function save($period_id)
    {
        $data = array('status' => 0, 'save_id' => '0'); //Resultado por defecto

        $arr_row = $this->arr_row($period_id);
        $saved_id = $this->Db_model->save('period', "id = {$period_id}", $arr_row);
    
        //Preparar resultado
        if ( $saved_id > 0 ) { $data = array('status' => 1, 'saved_id' => $saved_id); }
        
        return $data;
    }
    
    /**
     * Insertar un registro en la tabla period.
     * 2019-10-31
     * 
     * @param type $arr_row
     * @return type
     */
    function insert($arr_row = NULL)
    {
        if ( is_null($arr_row) ) { $arr_row = $this->arr_row('insert'); }
        
        //Insert in table
            $this->db->insert('period', $arr_row);
            $period_id = $this->db->insert_id();

            if ( $period_id > 0 )
            {
                //$this->update_dependent($period_id);

                //Set result
                    $data = array('status' => 1, 'message' => 'Cobro creado', 'saved_id' => $period_id);
            }
        
        return $data;
    }

    /**
     * Actualiza un registro en la tabla period
     * 2019-10-30
     * 
     * @param type $arr_row
     * @return type
     */
    function update($period_id, $arr_row = NULL)
    {
        if ( is_null($arr_row) ) { $arr_row = $this->arr_row('update'); }

        //Actualizar
            $this->db->where('id', $period_id);
            $this->db->update('period', $arr_row);

        //Actualizar datos dependientes
            //$this->update_dependent($period_id);
    
        //Preparar resultado
            $data = array('status' => 1, 'message' => 'Los datos del period fueron actualizados');
        
        return $data;
    }

    /**
     * Array con datos para editar o crear un registro de un period
     * 2019-10-29
     */
    function arr_row($period_id)
    {
        $arr_row = $this->input->post();
        $arr_row['editor_id'] = $this->session->userdata('user_id');
        
        if ( ! ($period_id > 0) )
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
    function delete($period_id)
    {
        $quan_deleted = 0;

        if ( $this->deletable($period_id) ) 
        {
            //Tablas relacionadas

                //meta
                /*$this->db->where('table_id', 1000); //Tabla usuario
                $this->db->where('period_id', $period_id);
                $this->db->delete('meta');*/
            
            //Tabla principal
                $this->db->where('id', $period_id);
                $this->db->delete('period');

            $quan_deleted = $this->db->affected_rows();
        }

        return $quan_deleted;
    }

// Rango Semanal
//-----------------------------------------------------------------------------

    function weekly_lapse($date_1, $date_2)
    {
        $this->db->where('start >=', $date_1);
        $this->db->where('end <=', $date_2);
        $this->db->where('type_id', 9); //Días        
        $this->db->order_by('week_day', 'ASC');
        $this->db->order_by('id', 'ASC');
        $periods = $this->db->get('period');

        return $periods;
    }

}