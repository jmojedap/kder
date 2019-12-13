<?php
class Payment_model extends CI_Model{

    function basic($payment_id)
    {
        $data['payment_id'] = $payment_id;
        $data['row'] = $this->Db_model->row_id('payments', $payment_id);
        $data['head_title'] = 'Pago ' . $data['row']->title;
        $data['view_a'] = 'payments/payment_v';
        $data['nav_2'] = 'payments/menu_v';

        return $data;
    }

// EXPLORE FUNCTIONS - payments/explore
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
            $data['controller'] = 'payments';                      //Nombre del controlador
            $data['views_folder'] = 'payments/explore/';           //Carpeta donde están las vistas de exploración
            $data['head_title'] = 'Pagos';
                
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
            $data['cf'] = 'payments/explore/';     //CF Controlador Función
            $data['adv_filters'] = array('y', 'i');
        
        //Paginación
            $data['num_page'] = $num_page;                  //Número de la página de datos que se está consultado
            $data['per_page'] = 50;                           //Cantidad de registros por página
            $offset = ($num_page - 1) * $data['per_page'];    //Número de la página de datos que se está consultado
        
        //Búsqueda y Resultados
            $this->load->model('Search_model');
            $data['filters'] = $this->Search_model->filters();
            $data['str_filters'] = $this->Search_model->str_filters();
            $data['elements'] = $this->search($data['filters'], $data['per_page'], $offset);    //Resultados para página
            
        //Otros
            $data['search_num_rows'] = $this->search_num_rows($data['filters']);
            $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'],1) / $data['per_page']);   //Cantidad de páginas
            $data['all_selected'] = '-'. $this->pml->query_to_str($data['elements'], 'id');           //Para selección masiva de todos los elementos de la página
            
        return $data;
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
            $words_condition = $this->Search_model->words_condition($filters['q'], array('notes, title'));
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
            $query = $this->db->get('payments'); //Resultados totales
        } else {
            $query = $this->db->get('payments', $per_page, $offset); //Resultados por página
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
     * @param type $payment_id
     * @return type 
     */
    function role_filter()
    {
        
        $role = $this->session->userdata('role');
        $condition = 'id = 0';  //Valor por defecto, ningún user, se obtendrían cero user.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los user
            $condition = 'id > 0';
        } elseif ( $role == 11 )  {
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
            'id' => 'ID Pago',
            'name' => 'Nombre Pago'
        );
        
        return $order_options;
    }
    
    /**
     * Establece si un usuario en sesión puede o no editar los datos de un grupo
     */
    function editable($payment_id)
    {
        $editable = FALSE;
        if ( $this->session->userdata('role') <= 2 ) { $editable = TRUE; }
        //if ( $this->session->userdata('institution_id') == $payment_id ) { $editable = TRUE; }

        return $editable;
    }

// CRUD
//-----------------------------------------------------------------------------
    
    /**
     * Insertar un registro en la tabla payment.
     * 2019-10-31
     * 
     * @param type $arr_row
     * @return type
     */
    function insert($arr_row = NULL)
    {
        if ( is_null($arr_row) ) { $arr_row = $this->arr_row('insert'); }
        
        //Insert in table
            $this->db->insert('payment', $arr_row);
            $payment_id = $this->db->insert_id();

            if ( $payment_id > 0 )
            {
                $this->update_dependent($payment_id);

                //Set result
                    $data = array('status' => 1, 'message' => 'Pago creado', 'saved_id' => $payment_id);
            }
        
        return $data;
    }

    /**
     * Actualiza un registro en la tabla payment
     * 2019-10-30
     * 
     * @param type $arr_row
     * @return type
     */
    function update($payment_id, $arr_row = NULL)
    {
        if ( is_null($arr_row) ) { $arr_row = $this->arr_row('update'); }

        //Actualizar
            $this->db->where('id', $payment_id);
            $this->db->update('payment', $arr_row);

        //Actualizar campos dependientes
            $this->update_dependent($payment_id);
    
        //Preparar resultado
            $data = array('status' => 1, 'message' => 'Los datos del grupo fueron actualizados');
        
        return $data;
    }

    /**
     * Array con datos para editar o crear un registro de un grupo
     * 2019-10-29
     */
    function arr_row($process = 'update')
    {
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
    function delete($payment_id)
    {
        $quan_deleted = 0;

        if ( $this->deletable($payment_id) ) 
        {
            //Tablas relacionadas

                //meta
                /*$this->db->where('table_id', 1000); //Tabla usuario
                $this->db->where('payment_id', $payment_id);
                $this->db->delete('meta');*/
            
            //Tabla principal
                $this->db->where('id', $payment_id);
                $this->db->delete('payment');

            $quan_deleted = $this->db->affected_rows();
        }

        return $quan_deleted;
    }

// ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Establecer un pago como pagado simple
     * 2019-12-12
     */
    function set_payed($payment_id, $charge_id, $payment_status = 1)
    {
        $row_charge = $this->Db_model->row_id('charges', $charge_id);

        //Construir registro
            $arr_row = $this->Db_model->arr_row($payment_id);
            $arr_row['status'] = $payment_status; //Pagado
            $arr_row['payed_value'] = ( $payment_status == 1 ) ? $row_charge->charge_value : 0 ;
            $arr_row['payed_at'] = date('Y-m-d H:i:s');

        //Guardar
            $saved_id = $this->Db_model->save('payment', "id = {$payment_id}", $arr_row);

        //Establecere resultado
            $data = array('status' => 1, 'saved_id' => 0);
            if ( $saved_id > 0 ) {
                $data = array('status' => 1, $saved_id => $saved_id);
            }

        return $data;
    }

}