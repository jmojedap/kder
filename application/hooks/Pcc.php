<?php

class Pcc {
    
    //Pcc, hace referencia al punto del hook, Post Controller Constructor
    
    function index()
    {
        //Crea instancia para obtener acceso a las librerías de codeigniter, basado en el id
            $this->CI = &get_instance();
        
        //Identificar controlador/función, y allow
            $cf = $this->CI->uri->segment(1) . '/' . $this->CI->uri->segment(2);
            $allow_cf = $this->allow_cf($cf);    //Permisos de acceso al recurso controlador/función
        
        //Verificar allow
            if ( $allow_cf )
            {
                //$this->no_leidos();     //Actualizar variable de sesión, cant mensajes no leídos
            } else {
                //No tiene allow
                //header('HTTP/1.0 403 Forbidden');
                redirect("app/denied/{$cf}");
                //exit;
            }
    }
    
    /**
     * Control de acceso de usuarios basado en el id de los recursos (sis_acl.id)
     * CF > Ruta Controller/Function
     */
    function allow_cf($cf)
    {
        //Valor inicial
        $allow_cf = TRUE;
        
        //Si no es administrador, verificar permiso
        if ( $this->CI->session->userdata('role') > 1 ) 
        {
            $acl = $this->CI->session->userdata('acl');    

            $functions = $this->CI->db->get_where('sis_acl', "cf = '{$cf}'");

            $cf_id = 0;
            if ( $functions->num_rows() > 0 ) { $cf_id = $functions->row()->id; }

            //Si el controlador/funcion requerido no está entre las functions permitidas
            if ( ! in_array($cf_id, $acl) ) { $allow_cf = FALSE; }      
        }
        
        //Si no está logueado
        if ( ! $this->CI->session->userdata('logged') ) { $allow_cf = FALSE; }
        
        //Si está ingresando a una función pública, se otorga permiso
        $public_functions = $this->public_functions();
        if ( in_array($cf, $public_functions) ) { $allow_cf = TRUE; }
        
        return $allow_cf;
        
    }
    
    /**
     * Array con las functions (controlador/funcion) a las que se pueden acceder
     * libremente, sin iniciar sesión de usuario.
     * 
     * @return string
     */
    function public_functions()
    {
        $public_functions[] = '/';
        $public_functions[] = 'accounts/';
        $public_functions[] = 'accounts/index';
        $public_functions[] = 'accounts/login';
        $public_functions[] = 'accounts/validate_login';
        $public_functions[] = 'accounts/logout';
        
        $public_functions[] = 'accounts/signup';
        $public_functions[] = 'accounts/validate_signup';
        $public_functions[] = 'accounts/register';
        $public_functions[] = 'accounts/registered';
        
        $public_functions[] = 'accounts/activation';
        $public_functions[] = 'accounts/activate';
        $public_functions[] = 'accounts/recovery';

        $public_functions[] = 'accounts/g_callback';
        $public_functions[] = 'accounts/g_signup';

        $public_functions[] = 'app/logged';
        $public_functions[] = 'app/denied';
        $public_functions[] = 'app/test';

        $public_functions[] = 'sync/tables_status';
        $public_functions[] = 'sync/get_rows';
        $public_functions[] = 'sync/quan_rows';
        
        $public_functions[] = 'orders/confirmation_payu';

        $public_functions[] = 'admin/phpinfo';
        $public_functions[] = 'admin/session';
        
        return $public_functions;
    }
}