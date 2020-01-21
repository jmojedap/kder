<?php
class Charge_model extends CI_Model{

    function basic($charge_id)
    {
        $data['charge_id'] = $charge_id;
        $data['row'] = $this->Db_model->row_id('charges', $charge_id);
        $data['head_title'] = 'Cobro ' . $data['row']->title;
        $data['view_a'] = 'charges/charge_v';
        $data['nav_2'] = 'charges/menu_v';

        return $data;
    }

// EXPLORE FUNCTIONS - charges/explore
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
            $data['controller'] = 'charges';                      //Nombre del controlador
            $data['cf'] = 'charges/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'charges/explore/';           //Carpeta donde están las vistas de exploración
            
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
            $query = $this->db->get('charges'); //Resultados totales
        } else {
            $query = $this->db->get('charges', $per_page, $offset); //Resultados por página
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
     * @param type $charge_id
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
     * Establece si un usuario en sesión puede o no editar los datos de un cobro
     */
    function editable($charge_id)
    {
        $editable = FALSE;
        if ( $this->session->userdata('role') <= 2 ) { $editable = TRUE; }
        //if ( $this->session->userdata('institution_id') == $charge_id ) { $editable = TRUE; }

        return $editable;
    }

// CRUD
//-----------------------------------------------------------------------------

    function save($charge_id)
    {
        $data = array('status' => 0, 'save_id' => '0'); //Resultado por defecto

        $arr_row = $this->arr_row($charge_id);
        $saved_id = $this->Db_model->save('charges', "id = {$charge_id}", $arr_row);
    
        //Preparar resultado
        if ( $saved_id > 0 ) { $data = array('status' => 1, 'saved_id' => $saved_id); }
        
        return $data;
    }
    
    /**
     * Insertar un registro en la tabla charge.
     * 2019-10-31
     * 
     * @param type $arr_row
     * @return type
     */
    function insert($arr_row = NULL)
    {
        if ( is_null($arr_row) ) { $arr_row = $this->arr_row('insert'); }
        
        //Insert in table
            $this->db->insert('charges', $arr_row);
            $charge_id = $this->db->insert_id();

            if ( $charge_id > 0 )
            {
                //$this->update_dependent($charge_id);

                //Set result
                    $data = array('status' => 1, 'message' => 'Cobro creado', 'saved_id' => $charge_id);
            }
        
        return $data;
    }

    /**
     * Actualiza un registro en la tabla charge
     * 2019-10-30
     * 
     * @param type $arr_row
     * @return type
     */
    function update($charge_id, $arr_row = NULL)
    {
        if ( is_null($arr_row) ) { $arr_row = $this->arr_row('update'); }

        //Actualizar
            $this->db->where('id', $charge_id);
            $this->db->update('charges', $arr_row);

        //Actualizar datos dependientes
            //$this->update_dependent($charge_id);
    
        //Preparar resultado
            $data = array('status' => 1, 'message' => 'Los datos del cobro fueron actualizados');
        
        return $data;
    }

    /**
     * Array con datos para editar o crear un registro de un cobro
     * 2019-10-29
     */
    function arr_row($charge_id)
    {
        $arr_row = $this->input->post();
        $arr_row['type_id'] = 4031; //Post tipo cobro (charge)
        $arr_row['editor_id'] = $this->session->userdata('user_id');
        
        if ( ! ($charge_id > 0) )
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
    function delete($charge_id)
    {
        $quan_deleted = 0;

        if ( $this->deletable($charge_id) ) 
        {
            //Tablas relacionadas

                //meta
                /*$this->db->where('table_id', 1000); //Tabla usuario
                $this->db->where('charge_id', $charge_id);
                $this->db->delete('meta');*/
            
            //Tabla principal
                $this->db->where('id', $charge_id);
                $this->db->delete('charges');

            $quan_deleted = $this->db->affected_rows();
        }

        return $quan_deleted;
    }

// GRUPOS
//-----------------------------------------------------------------------------

    /**
     * Todos los grupos de una institución y generación, correspondiente a un cobro
     * Si el grupo está asociado al cobro, charte_id será > 0.
     * 2019-12-10
     */
    function groups($charge_id)
    {
        $row = $this->Db_model->row_id('charges', $charge_id);

        $this->db->select('groups.id, name, title, IF(group_meta.id IS NULL, 0, group_meta.id) AS meta_id');
        $this->db->where('groups.institution_id', $row->institution_id);
        $this->db->where('groups.generation', $row->generation);
        $this->db->where("(related_1 = {$charge_id} OR related_1 IS NULL)");
        $this->db->join('group_meta', 'groups.id = group_meta.group_id', 'left');
        $groups = $this->db->get('groups');

        return $groups;
    }

    /**
     * Le establece un cobro a un grupo, tabla group_meta
     * Agrega a los estudiantes del grupo al cobro, tabla payements
     * 2019-12-10
     */
    function set_group($charge_id, $group_id)
    {
        $data = array('status' => 0, 'meta_id' => 0, 'qty_students' => 0);  //Resultado inicial

        //Construir registro
        $arr_row = $this->Db_model->arr_row(0);
        $arr_row['group_id'] = $group_id;
        $arr_row['type_id'] = 4111;     //Cobro a grupo
        $arr_row['related_1'] = $charge_id;

        //Guardar
        $condition = "group_id = {$group_id} AND type_id = 4111 AND related_1 = {$charge_id}";
        $meta_id = $this->Db_model->save('group_meta', $condition, $arr_row);

        if ( $meta_id > 0 )
        {
            $data['status'] = 1;
            $data['meta_id'] = $meta_id;

            //Agregar a estudiantes del grupo
            $this->load->model('Group_model');
            $students = $this->Group_model->students($group_id);
            foreach ($students->result() as $row_student) 
            {
                $payment_id = $this->set_user($charge_id, $group_id, $row_student->id);
                if ( $payment_id > 0) { $data['qty_students']++; }
            }
        }

        return $data;
    }

    /**
     * Le quita un cobro a un grupo de estudiantes. Elimina registro de la tabla group_meta
     * y los pagos de los usuarios en la tabla payment.
     * 2019-12-12
     */
    function unset_group($charge_id, $meta_id)
    {
        $data = array('status' => 0, 'qty_deleted' => 0);  //Resultado inicial

        $row = $this->Db_model->row_id('charges', $charge_id);
        $row_meta = $this->Db_model->row('group_meta', "id = {$meta_id} AND related_1 = {$charge_id}");

        //Eliminar estudiantes del cobro, tabla payment
            $this->db->where('charge_id', $charge_id);
            $this->db->where('group_id', $row_meta->group_id);
            $this->db->delete('payment');
            $data['qyt_deleted'] = $this->db->affected_rows();

        //Eliminar registro de tabla group_meta
            $this->db->where('id', $meta_id);
            $this->db->where('related_1', $charge_id);
            $this->db->delete('group_meta');
            
            $meta_deleted = $this->db->affected_rows();
            if ( $meta_deleted > 0 ) { $data['status'] = 1; }

        return $data;
        
    }

// COBROS A USUARIOS
//-----------------------------------------------------------------------------

    /**
     * Le establece un cobro a un estudiante, crea registro en la tabla payment
     * para hacer seguimiento del pago.
     * 2019-12-10
     */
    function set_user($charge_id, $group_id, $user_id)
    {
        $row_charge = $this->Db_model->row_id('charges', $charge_id);

        //Preparar registro
        $arr_row = $this->Db_model->arr_row(0);
        $arr_row['charge_id'] = $charge_id;
        $arr_row['student_id'] = $user_id;
        $arr_row['group_id'] = $group_id;
        $arr_row['total_value'] = $row_charge->charge_value;

        $payment_id = $this->Db_model->save('payment', "charge_id = {$charge_id} AND student_id = {$user_id}", $arr_row);

        return $payment_id;
    }

    /**
     * Le retira un cobro a un usuario. Eliminando registro de la tabla payment.
     * 2019-12-10
     */
    function unset_user($charge_id, $user_id)
    {
        $this->db->where('charge_id', $charge_id);
        $this->db->where('student_id', $user_id);
        $this->db->delete('payment');
        
        $qty_deleted = $this->db->affected_rows();

        return $qty_deleted;
    }

    function students_group($charge_id, $group_id)
    {
        $this->db->select('payment.id AS payment_id, user.id, user.display_name, user.src_thumbnail, user.username, payment.status AS payment_status, payed_value, payment.edited_at');
        $this->db->join('payment', 'user.id = payment.student_id');
        $this->db->where('payment.group_id', $group_id);
        $this->db->order_by('last_name', 'ASC');    
        $students = $this->db->get('user');

        return $students;
    }
}