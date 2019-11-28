<?php
class App_model extends CI_Model{
    
    /* Application model,
     * Functions to Legalink Admin Application
     * 
     */
    
    function __construct(){
        parent::__construct();
        
    }
    
//SYSTEM
//---------------------------------------------------------------------------------------------------------
    
    /**
     * Carga la view solicitada, si por get se solicita una view específica
     * se devuelve por secciones el html de la view, por JSON.
     * 
     * @param type $view
     * @param type $data
     */
    function view($view, $data)
    {
        if ( $this->input->get('json') )
        {
            //Sende sections JSON
            $result['head_title'] = $data['head_title'];
            $result['head_subtitle'] = '';
            $result['nav_2'] = '';
            $result['nav_3'] = '';
            $result['view_a'] = '';
            
            if ( isset($data['head_subtitle']) ) { $result['head_subtitle'] = $data['head_subtitle']; }
            if ( isset($data['view_a']) ) { $result['view_a'] = $this->load->view($data['view_a'], $data, TRUE); }
            if ( isset($data['nav_2']) ) { $result['nav_2'] = $this->load->view($data['nav_2'], $data, TRUE); }
            if ( isset($data['nav_3']) ) { $result['nav_3'] = $this->load->view($data['nav_3'], $data, TRUE); }
            
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
            //echo trim(json_encode($result));
        } else {
            //Cargar view completa de forma normal
            $this->load->view($view, $data);
        }
    }
    
    /**
     * Devuelve el valor del campo sis_option.valor
     * @param type $option_id
     * @return type
     */
    function option_value($option_id)
    {
        $option_value = $this->Db_model->field_id('sis_option', $option_id, 'value');
        return $option_value;
    }

    /**
     * Array con datos de sesión adicionales específicos para la aplicación actual.
     * 2019-11-06
     */
    function app_session_data($row_user)
    {
        $data = array(
            'institution_id' => $row_user->institution_id,
            'generation' => date('Y')
        );

        //Si tiene institución, establecer año generación
        if ( $row_user->institution_id > 0 )
        {
            $data['generation'] = $this->Db_model->field('institution', "id = {$row_user->institution_id}", 'generation');
        }

        return $data;
    }

    /**
     * Validación de Google Recaptcha V3, la validación se realiza considerando el valor de
     * $recaptcha->score, que va de 0 a 1.
     * 2019-10-31
     */
    function recaptcha()
    {
        $secret = K_RCSC;   //Ver config/constants.php
        $response = $this->input->post('g-recaptcha-response');
        $json_recaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
        $recaptcha = json_decode($json_recaptcha);
        
        return $recaptcha;
    }

// NOMBRES
//-----------------------------------------------------------------------------

    /**
     * Devuelve el nombre de un user ($user_id) en un format específico ($format)
     */
    function name_user($user_id, $format = 'd')
    {
        $name_user = 'ND';
        $row = $this->Db_model->row_id('user', $user_id);

        if ( ! is_null($row) ) 
        {
            $name_user = $row->username;

            if ($format == 'u') {
                $name_user = $row->username;
            } elseif ($format == 'fl') {
                $name_user = "{$row->first_name} {$row->last_name}";
            } elseif ($format == 'lf') {
                $name_user = "{$row->last_name} {$row->first_name}";
            } elseif ($format == 'flu') {
                $name_user = "{$row->first_name} {$row->last_name} | {$row->username}";
            } elseif ($format == 'du') {
                $name_user = $row->display_name . ' | ' . $row->username;
            } elseif ($format == 'd') {
                $name_user = $row->display_name;
            }
        }

        return $name_user;
    }

    /**
     * Devuelve el nombre de una registro ($place_id) en un format específico ($format)
     */
    function place_name($place_id, $format = 1)
    {
        $place_name = 'ND';
        
        if ( strlen($place_id) > 0 )
        {
            $this->db->select("place.id, place.place_name, region, country"); 
            $this->db->where('place.id', $place_id);
            $row = $this->db->get('place')->row();

            if ( $format == 1 ){
                $place_name = $row->place_name;
            } elseif ( $format == 'CR' ) {
                $place_name = $row->place_name . ', ' . $row->region;
            } elseif ( $format == 'CRP' ) {
                $place_name = $row->place_name . ' - ' . $row->region . ' - ' . $row->country;
            }
            
        }
        
        
        return $place_name;
    }

// OPCIONES
//-----------------------------------------------------------------------------
    
    /**
     * Devuelve un array con las opciones de la tabla place, limitadas por una condición definida en 
     * un formato ($value_field) definido
     * 2019-08-20
     */
    function options_place($condition, $value_field = 'cr', $empty_text = 'Lugar')
    {
        $this->db->select("CONCAT('0', place.id) AS place_id, place_name, CONCAT((place_name), ', ', (region)) AS cr", FALSE); 
        $this->db->where($condition);
        $this->db->order_by('place.place_name', 'ASC');
        $query = $this->db->get('place');

        $options_place = array_merge(array('00' => '[ ' . $empty_text . ' ]'), $this->pml->query_to_array($query, $value_field, 'place_id'));
        //$options_place = $this->pml->query_to_array($query, $value_field, 'place_id');
        
        return $options_place;
    }

    /* Devuelve un array con las opciones de la tabla place, limitadas por una condición definida
    * en un format ($format) definido
    */
    function options_user($condition, $empty_text = 'Usuario', $value_field = 'display_name')
    {
        
        $this->db->select("CONCAT('0', user.id) AS user_id, display_name, username, CONCAT(display_name, (' | '), username) as du", FALSE); 
        $this->db->where($condition);
        $this->db->order_by('user.display_name', 'ASC');
        $query = $this->db->get('user');
        
        $options_user = array_merge(array('' => '[ ' . $empty_text . ' ]'), $this->pml->query_to_array($query, $value_field, 'user_id'));
        
        return $options_user;
    }

    /* Devuelve un array con las opciones de la tabla place, limitadas por una condición definida
    * en un format ($format) definido
    */
    function options_institution($condition, $empty_text = 'Institución', $value_field = 'name')
    {
        
        $this->db->select("CONCAT('0', institution.id) AS institution_id, name", FALSE); 
        $this->db->where($condition);
        $this->db->order_by('institution.name', 'ASC');
        $query = $this->db->get('institution');
        
        $options = array_merge(array('' => '[ ' . $empty_text . ' ]'), $this->pml->query_to_array($query, $value_field, 'institution_id'));
        
        return $options;
    }

    /**
     * Array con rango de años de generación de grupos de estudiantes
     * 2019-11-06
     */
    function options_generation($start = NULL, $end = NULL)
    {
        if ( is_null($start) ) { $start = date('Y') - 2; }
        if ( is_null($end) ) { $end = date('Y') + 2; }

        $years = range(2017,2025);
        $options_generation = array('' => '[ Año generación ]');
        foreach ($years as $year) 
        {
            $options_generation['0' . $year] = 'Año ' . $year;
        }

        return $options_generation;
    }

// IMÁGENES
//-----------------------------------------------------------------------------

    /**
     * String src atributo html para imagen, imagen de usuario
     * 2019-11-07
     */
    function src_img_user($row_user, $prefix = '')
    {
        $src = URL_IMG . 'users/'. $prefix . 'user.png';
            
        if ( $row_user->image_id > 0 )
        {
            $src = URL_UPLOADS . $row_user->src_image;
            if ( $prefix == 'sm_' )
            {
                $src = URL_UPLOADS . $row_user->src_thumbnail;
            }
        }
        
        return $src;
    }

    function att_img_user($row_user, $prefix = '')
    {
        $att_img = array(
            'src' => $this->src_img_user($row_user, $prefix),
            'alt' => 'Imagen del usuario ' . $row_user->username,
            'width' => '100%',
            'onerror' => "this.src='" . URL_IMG . 'users/sm_user.png' . "'"
        );
        
        return $att_img;
    }

    /**
     * Array con atributos de elemento imagen de una institución
     * 2019-10-30
     */
    function att_img_institution($row_institution, $prefix = '')
    {
        $src = URL_IMG . 'app/'. $prefix . 'institution.png';
            
        if ( $row_institution->image_id > 0 )
        {
            $row_file = $this->Db_model->row_id('file', $row_institution->image_id);
            $src = URL_UPLOADS . $row_file->folder . $prefix . $row_file->file_name;
        }

        $att_img = array(
            'src' => $src,
            'alt' => 'Imagen de la institución ' . $row_institution->name,
            'width' => '100%',
            'onerror' => "this.src='" . URL_IMG . 'users/sm_user.png' . "'"
        );
        
        return $att_img;
    }
}