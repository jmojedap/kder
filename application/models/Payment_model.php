<?php
class Payment_model extends CI_Model{

    function basic($payment_id)
    {
        $data['payment_id'] = $payment_id;
        $data['row'] = $this->Db_model->row_id('payment', $payment_id);
        $data['row_charge'] = $this->Db_model->row_id('charges', $data['row']->charge_id);
        $data['head_title'] = 'Pago ' . $data['row_charge']->title;
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
            $data = $this->get($num_page);
        
        //Elemento de exploración
            $data['controller'] = 'payments';                      //Nombre del controlador
            $data['cf'] = 'payments/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'payments/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Pagos';
            $data['head_subtitle'] = $data['search_num_rows'];
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    function get($num_page)
    {
        //Referencia
            $per_page = 50;                             //Cantidad de registros por página
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
     * String con condición WHERE SQL para filtrar el elemento
     * 
     * @param type $filters
     * @return type
     */
    function search_condition($filters)
    {
        $condition = NULL;
        
        //Filtros
        if ( $filters['type'] != '' ) { $condition .= "charge_type_id = {$filters['type']} AND "; }
        
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
            /*$this->db->select('payment.id, charge_id, payment.status, student_id, charges.title, user.display_name AS student_name');
            /*$this->db->join('charges', 'charges.id = payment.charge_id');
            $this->db->join('user', 'user.id = payment.student_id');*/
        
        //Crear array con términos de búsqueda
            $words_condition = $this->Search_model->words_condition($filters['q'], array('notes', 'title', 'student_name'));
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
                $this->db->order_by('payments.created_at', 'DESC');
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
        $condition = 'payments.id = 0';  //Valor por defecto, ningún user, se obtendrían cero user.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los user
            $condition = 'payments.id > 0';
        } elseif ( $role == 11 )  {
            $condition = 'charges.institution_id = ' . $this->session->userdata('institution_id');
        } else {
            $condition = 'payments.id = 0';
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
     * Guardar pago en la tabla payment
     * 2019-12-14
     */
    function save($payment_id)
    {
        $data = array('status' => 0, 'saved_id' => 0);  //Resultado inicial

        $arr_row = $this->arr_row($payment_id);
        $saved_id = $this->Db_model->save('payment', "id = {$payment_id}", $arr_row);

        //Verificación del resultado
        if ( $saved_id > 0 ) { $data = array('status' => 1, 'saved_id' => $saved_id); }

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
            if ( $saved_id > 0 ) { $data = array('status' => 1, 'saved_id' => $saved_id); }

        return $data;
    }

}