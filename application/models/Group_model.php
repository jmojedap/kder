<?php
class Group_model extends CI_Model{

    function basic($group_id)
    {
        $data['group_id'] = $group_id;
        $data['row'] = $this->Db_model->row_id('groups', $group_id);
        $data['head_title'] = 'Grupo ' . $data['row']->title;
        $data['view_a'] = 'groups/group_v';
        $data['nav_2'] = 'groups/menu_v';

        return $data;
    }

// EXPLORE FUNCTIONS - groups/explore
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
            $data['controller'] = 'groups';                      //Nombre del controlador
            $data['cf'] = 'groups/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'groups/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Grupos';
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
     * String con condición WHERE SQL para filtrar el elemento
     * 
     * @param type $filters
     * @return type
     */
    function search_condition($filters)
    {
        $condition = NULL;
        
        //Nivel del grupo
        if ( $filters['level'] != '' ) { $condition .= "level = {$filters['level']} AND "; }
        if ( $filters['u'] != '' ) { $condition .= "teacher_id = {$filters['u']} AND "; }
        if ( $filters['y'] != '' ) { $condition .= "generation = {$filters['y']} AND "; }
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
            $this->db->select('groups.id, name, title, level, teacher_id, groups.institution_id, groups.status, user.display_name AS teacher_name, groups.description');
            $this->db->join('user', 'groups.teacher_id = user.id', 'left');
        
        //Crear array con términos de búsqueda
            $words_condition = $this->Search_model->words_condition($filters['q'], array('name', 'cod', 'description'));
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
                $this->db->order_by('groups.edited_at', 'DESC');
            }
            
        //Filtros
            $this->db->where($role_filter); //Filtro según el rol de user en sesión
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
        if ( is_null($per_page) )
        {
            $query = $this->db->get('groups'); //Resultados totales
        } else {
            $query = $this->db->get('groups', $per_page, $offset); //Resultados por página
        }
        
        return $query;
    }

    /**
     * Array Listado elemento resultado de la búsqueda (filtros).
     * 2020-01-21
     */
    function list($filters, $per_page = NULL, $offset = NULL)
    {
        $query = $this->search($filters, $per_page, $offset);
        $list = array();

        foreach ($query->result() as $row)
        {
            $row->qty_students = $this->Db_model->num_rows('group_user', "group_id = {$row->id}");  //Cantidad de estudiantes
            $list[] = $row;
        }

        return $list;
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
     * @param type $group_id
     * @return type 
     */
    function role_filter()
    {
        
        $role = $this->session->userdata('role');
        $condition = 'groups.id = 0';  //Valor por defecto, ningún user, se obtendrían cero user.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los user
            $condition = 'groups.id > 0';
        } elseif ( $role == 11 )  {
            $condition = 'groups.institution_id = ' . $this->session->userdata('institution_id');
        } else {
            $condition = 'groups.id = 0';
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
            'id' => 'ID Grupo',
            'name' => 'Nombre Grupo'
        );
        
        return $order_options;
    }
    
    /**
     * Establece si un usuario en sesión puede o no editar los datos de un grupo
     */
    function editable($group_id)
    {
        $editable = FALSE;
        if ( $this->session->userdata('role') <= 2 ) { $editable = TRUE; }
        if ( $this->session->userdata('group_id') == $group_id ) { $editable = TRUE; }

        return $editable;
    }

// CRUD
//-----------------------------------------------------------------------------
    
    /**
     * Insertar un registro en la tabla group.
     * 2019-10-31
     * 
     * @param type $arr_row
     * @return type
     */
    function insert($arr_row = NULL)
    {
        if ( is_null($arr_row) ) { $arr_row = $this->arr_row('insert'); }
        
        //Insert in table
            $this->db->insert('groups', $arr_row);
            $group_id = $this->db->insert_id();

            if ( $group_id > 0 )
            {
                $this->update_dependent($group_id);

                //Set result
                    $data = array('status' => 1, 'message' => 'Grupo creado', 'saved_id' => $group_id);
            }
        
        return $data;
    }

    /**
     * Actualiza un registro en la tabla group
     * 2019-10-30
     * 
     * @param type $arr_row
     * @return type
     */
    function update($group_id, $arr_row = NULL)
    {
        if ( is_null($arr_row) ) { $arr_row = $this->arr_row('update'); }

        //Actualizar
            $this->db->where('id', $group_id);
            $this->db->update('groups', $arr_row);

        //Actualizar campos dependientes
            $this->update_dependent($group_id);
    
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
    
    /**
     * Establece permiso para eliminar un grupo
     */
    function deletable($group_id)
    {
        $deletable = 0;
        $row = $this->Db_model->row_id('groups', $group_id);

        //echo $row->institution_id . ' = ' . $this->session->userdata('institution_id');

        if ( $this->session->userdata('role') <= 1 ) { $deletable = 1; }
        if ( $row->institution_id == $this->session->userdata('institution_id') ) { $deletable = 1; }
        
        return $deletable;
    }

    /**
     * Eliminar un usuario de la base de datos, se elimina también de
     * las tablas relacionadas
     */
    function delete($group_id)
    {
        $qty_deleted = 0;

        if ( $this->deletable($group_id) ) 
        {
            //Tablas relacionadas

                //meta
                /*$this->db->where('table_id', 1000); //Tabla usuario
                $this->db->where('group_id', $group_id);
                $this->db->delete('meta');*/
            
            //Tabla principal
                $this->db->where('id', $group_id);
                $this->db->delete('groups');

            $qty_deleted = $this->db->affected_rows();
        }

        return $qty_deleted;
    }

// GESTIÓN DE CAMPOS DEPENDIENTES
//-----------------------------------------------------------------------------

    /**
     * Actualiza los campos adicionales de la tabla grupo, dependientes del 
     * curso y la institución
     * 
     * @param type $group_id
     */
    function update_dependent($group_id) 
    {
        //Datos iniciales
            $row = $this->Db_model->row_id('groups', $group_id);
        
        //Construir registro
            $arr_row['name'] = $this->generate_name($row->id);
            $arr_row['letter'] = strtoupper($row->letter);
            
        //Si está vacío el título
            if ( strlen($row->title) == 0 ) { $arr_row['title'] = $arr_row['title']; }
        
        //Actualizar
            $this->db->where('id', $group_id);
            $this->db->update('groups', $arr_row);
    }

    function generate_name($group_id, $field = 'title')
    {   
        //Datos referencia
            $row = $this->Db_model->row_id('groups', $group_id);
            $row_level = $this->Db_model->row('item', "category_id = 3 AND cod = {$row->level}");
            $row_schedule = $this->Db_model->row('item', "category_id = 17 AND cod = {$row->schedule}");
        
        //Armar nombre
            $group_name = "{$row_level->abbreviation}-{$row->letter}";
            if ( $row->schedule > 1) 
            {
                $group_name = "{$row_schedule->abbreviation}-{$row_level->abbreviation}-{$row->letter}";
            }
            
        //Si es nombre completo (PENDIENTE)
            if ( $field == 'cod' )
            {
                $this->load->helper('string');
                $group_name = str_pad($row->institution_id, 3, '0', STR_PAD_LEFT);
                $group_name .= $row->id;
                $group_name .= random_string('numeric', 2);
                $group_name .= $this->pml->if_strlen($row_schedule->abbreviation, '', '-' . $row_schedule->abbreviation);
                $group_name .="-{$row_level->abbreviation}-{$row->letter}";
            }
            
            $group_name = strtoupper($group_name);
            
        return $group_name;
    }

    

// VALIDATION
//-----------------------------------------------------------------------------

    /**
     * Valida datos de un grupo nueva o existente, verificando validez respecto
     * a users ya existentes en la base de datos.
     */
    function validate_form($group_id = NULL)
    {
        $data = array('status' => 1, 'message' => 'Los datos del grupo son válidos');
        
        $email_validation = $this->email_validation($this->input->post('email'), $group_id);
        $id_number_validation = $this->id_number_validation($this->input->post('id_number'), $group_id);

        $validation = array_merge($email_validation, $id_number_validation);
        $data['validation'] = $validation;

        foreach ( $validation as $value )
        {
            if ( $value == FALSE ) 
            {
                $data['status'] = 0;
                $data['message'] = 'Los datos del grupo NO son válidos';
            }
        }

        return $data;
    }

//IMAGEN DE PERFIL DE LA INSTITUCIÓN
//---------------------------------------------------------------------------------------------------
    
    /**
     * Asigna una imagen registrada en la tabla archivo como imagen de perfil de el grupo
     * 
     * @param type $group_id
     * @param type $file_id
     */
    function set_image($group_id, $file_id)
    {
        $data = array('status' => 0, 'message' => 'La imagen no fue asignada'); //Resultado inicial

        $row_file = $this->Db_model->row_id('file', $file_id);
            
        $arr_row['image_id'] = $row_file->id;
        
        $this->db->where('id', $group_id);
        $this->db->update('groups', $arr_row);
        
        if ( $this->db->affected_rows() )
        {
            $data = array('status' => 1, 'message' => 'La imagen de perfil fue asignada');
            $data['src'] = URL_UPLOADS . $row_file->folder . $row_file->file_name;  //URL de la imagen cargada
        }


        return $data;
    }
    
    /**
     * Le quita la imagen de perfil asignada a un grupo, eliminado el archivo
     * correspondiente
     * 2019-10-30
     * 
     * @param type $group_id
     * @return int
     */
    function remove_image($group_id)
    {
        $data['status'] = 0;
        $row = $this->Db_model->row_id('groups', $group_id);
        
        if ( ! is_null($row->image_id) )
        {
            $this->db->where('id', $group_id);
            $this->db->update('groups', array('image_id' => 0));

            $this->load->model('File_model');
            $this->File_model->delete($row->image_id);
            $data['status'] = 1;
        }
        
        return $data;
    }

    /**
     * Establecer un grupo como el principal de un usuario, actualiza el campo usser.group_id
     * y agrega group_id a las variables de sesión.
     * 2019-10-31
     */
    function set_main($group_id, $user_id)
    {
        //Actualizar tabla usuario
        if ( $this->session->userdata('role') > 20 )
        {
            $this->db->where('id', $user_id);
            $this->db->update('user', array('group_id' => $group_id));
        }

        //Establecer como variable de sesión
            $this->session->set_userdata('group_id', $group_id);
    }

// GESTIÓN DE ESTUDIANTES
//-----------------------------------------------------------------------------

    function students($group_id)
    {
        $this->db->select('group_user.id AS gu_id, user.id, user.display_name, user.src_thumbnail, user.username');
        $this->db->join('group_user', 'user.id = group_user.user_id');
        $this->db->where('group_user.group_id', $group_id);
        $this->db->order_by('last_name', 'ASC');    
        $students = $this->db->get('user');

        return $students;
    }

    /**
     * Agrega estudiante un grupo
     * 2020-02-10
     */
    function add_student($group_id, $user_id)
    {
        $data = array('status' => 0, 'ug_id' => '0');   //Resultado inicial por defecto

        //Construir registro para tabla grupo_usuerio
        $arr_row['group_id'] = $group_id;
        $arr_row['user_id'] = $user_id;
        $arr_row['editor_id'] = $this->session->userdata('user_id');
        $arr_row['creator_id'] = $this->session->userdata('user_id');

        //Guardar
        $condition = "group_id = {$arr_row['group_id']} AND user_id = {$arr_row['user_id']}";
        $ug_id = $this->Db_model->save('group_user', $condition, $arr_row);
    
        //Resultado
        if ( $ug_id > 0 )
        {
            $data = array('status' => 1, 'ug_id' => $ug_id);

            $this->remove_user_another_groups($group_id, $user_id); //Eliminar de otros grupos de la misma generación
            $this->set_user_group($user_id);    //Establecer grupo actual a usuario (user.group_id)
        }
    
        return $data;
    }

    /**
     * Actualiza el grupo actual
     * PENDIENTE AJUSTE PARA GRUPO GENERACIÓN ACTUAL
     * 2016-11-06
     */
    function set_user_group($user_id)
    {
        $arr_row['group_id'] = 0;   //Valor inicial

        //Buscar grupo con más alta generación
        $this->db->select('id');
        $this->db->where("id IN (SELECT group_id FROM group_user WHERE user_id = {$user_id})");
        $this->db->order_by('generation', 'DESC');
        $groups = $this->db->get('groups');

        if ( $groups->num_rows() > 0 ) { $arr_row['group_id'] = $groups->row()->id; }
        
        $this->Db_model->save('user', "id = {$user_id}", $arr_row);
    }

    /**
     * Remueve a un usuario de otros grupos de la misma generación del grupo al que fue agregado.
     * Un usuario no puede estar al mismo tiempo en dos grupos de la misma generación.
     * 2019-11-18
     */
    function remove_user_another_groups($group_id, $user_id)
    {
        $row = $this->Db_model->row_id('groups', $group_id);
        
        //Eliminar asignaciones de grupo
        $this->db->where("user_id", $user_id);      //Al usuario
        $this->db->where("group_id <>", $group_id); //De diferentes al grupo agregado
        $this->db->where("group_id IN (SELECT id FROM groups WHERE institution_id = {$row->institution_id} AND generation = {$row->generation})");   //De grupos que sean de la misma generación
        $this->db->delete('group_user');
        
        $quan_deleted = $this->db->affected_rows();

        return $quan_deleted;
    }

    /**
     * Quita un estudiante de un grupo. No lo elimina de la plataforma.
     * gu_id corresponde a (group_user.id). Por seguridad y confirmación se solicita
     * también el grupo_id.
     * 2019-11-13
     */
    function remove_student($group_id, $user_id, $gu_id)
    {
        $data = array('status' => 0, 'message' => 'El estudiante NO fue removido');

        //Eliminando
            $this->db->where('id', $gu_id);
            $this->db->where('group_id', $group_id);
            $this->db->delete('group_user');
            
            $quan_deleted = $this->db->affected_rows();

        //Actualizar user.group_id = 0
            $this->db->where('id', $user_id);
            $this->db->where('group_id', $group_id);   //Si el grupo actual es el removido
            $this->db->update('user', array('group_id' => 0));
    
        //Verificando resultado
        if ( $quan_deleted > 0 ) { $data = array('status' => 1, 'message' => 'El estudiante fue removido'); }
    
        return $data;
    }
}