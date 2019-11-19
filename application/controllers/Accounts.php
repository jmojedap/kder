<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends CI_Controller {
    
    function __construct() 
    {
        parent::__construct();
        
        $this->load->model('Account_model');
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Primera función de la aplicación
     */
    function index()
    {
        if ( $this->session->userdata('logged') )
        {
            redirect('app/logged');
        } else {
            redirect('accounts/login');
        }    
    }
    
//LOGIN
//---------------------------------------------------------------------------------------------------
    
    /**
     * Form login de users se ingresa con nombre de user y 
     * contraseña. Los datos se envían vía ajax a accounts/validate_login
     */
    function login()
    {
        //Verificar si es recordado en el equipo
            //$this->Account_model->login_cookie();
        
        //Verificar si está logueado
            if ( $this->session->userdata('logged') )
            {
                redirect('app/logged');
            } else {
                $data['head_title'] = APP_NAME;
                $data['view_a'] = 'accounts/login_v';
                $data['g_client'] = $this->Account_model->g_client(); //Para botón login con Google
                $this->load->view('templates/bootstrap/start_v', $data);
            }
    }

    function validate_login()
    {
        //Setting variables
            $userlogin = $this->input->post('username');
            $password = $this->input->post('password');
            
            $data = $this->Account_model->validate_login($userlogin, $password);
            
            if ( $data['status'] )
            {
                $this->Account_model->create_session($userlogin, TRUE);
            }
            
        //Salida
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));      
    }
    
    /**
     * Destroy session and redirect to login, start.
     */
    function logout()
    {
        $this->Account_model->logout();
        redirect('accounts/login');
    }

    //ML Master Login, 
    function ml($user_id)
    {
        $username = $this->Db_model->field_id('user', $user_id, 'username');
        if ( $this->session->userdata('role') <= 1 ) { $this->Account_model->create_session($username, FALSE); }
        
        redirect('app/logged');
    }
    
//USERS REGISTRATION
//---------------------------------------------------------------------------------------------------
    
    /**
     * Form de registro de nuevos users en el sistema, se envían los
     * datos a accounts/register
     */
    function signup($with_email = FALSE)
    {
        $data['head_title'] = 'Crear tu cuenta de ' . APP_NAME ;
        $data['view_a'] = 'accounts/signup_v';
        $data['with_email'] = $with_email;
        $data['g_client'] = $this->Account_model->g_client(); //Para botón login con Google
        $data['recaptcha_sitekey'] = K_RCSK;    //config/constants.php
        $this->load->view('templates/bootstrap/start_v', $data);
    }
    
    /**
     * AJAX JSON
     * 
     * Recibe los datos POST del form en accounts/signup. Si se validan los 
     * datos, se registra el user. Se devuelve $data, con resultados de registro
     * o de validación (si falló).
     * 2019-08-05
     */
    function register()
    {
        $data = array('status' => 0, 'message' => 'La cuenta no fue creada');  //Initial result values
        $res_validation = $this->Account_model->validate_form();
        $recaptcha = $this->Account_model->recaptcha();
            
        if ( $res_validation['status'] && $recaptcha['success'] )
        {
            //Construir registro del nuevo user
                $arr_row['first_name'] = $this->input->post('first_name');
                $arr_row['last_name'] = $this->input->post('last_name');
                $arr_row['display_name'] = $this->input->post('first_name') . ' ' . $this->input->post('last_name');
                $arr_row['email'] = str_replace('@gmail.com', '', $this->input->post('email')) . '@gmail.com';
                $arr_row['username'] = explode('@', $arr_row['email'])[0];
                $arr_row['role'] = 11;  //21: Propietario, default role

            //Insert user
                $this->load->model('User_model');
                $data = $this->User_model->insert($arr_row);
                
            //Enviar email con código de activación
                $this->Account_model->activation_key($data['user_id']);
                //$this->Account_model->email_activation($data['user_id']);
        } else {
            $data['validation'] = $res_validation['validation'];
        }

        //reCAPTCHA validation
        if ( ! $recaptcha['success'] ) { $data['recaptcha_valid'] = FALSE; }

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Validation of form signup user data
     */
    function validate_signup()
    {
        $data = $this->Account_model->validate_form();

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    /**
     * WEB VIEW
     * User signup confirmation message
     */
    function registered($user_id)
    {   
        //Solicitar vista
        $data['head_title'] = 'Usuario registrado';
        $data['row'] = $this->Db_model->row_id('user', $user_id);
        $data['view_a'] = 'accounts/registered_v';
        $this->load->view('templates/bootstrap/start_v', $data);
    }

// ACTIVATION
//-----------------------------------------------------------------------------

    function activation($activation_key, $activation_type = 'activation')
    {
        $row_user = $this->Db_model->row('user', "activation_key = '{$activation_key}'");        
        
        //Variables
            $data['activation_key'] = $activation_key;
            $data['activation_type'] = $activation_type;
            $data['row'] = $row_user;
            $data['view_a'] = 'accounts/activation_v';
            
        //Evaluar condiciones
            $conditions = 0;
            if ( ! is_null($row_user) ) { $conditions++; }
            if ( $this->session->userdata('logged') != TRUE ) { $conditions++; }
        
        if ( $conditions == 2 ) 
        {
            $data['head_title'] = "Cuenta de {$row_user->display_name}";
            $this->load->view('templates/bootstrap/start_v', $data);
        } else {
            redirect('app/denied');
        }
    }

    function activate($activation_key)
    {
        $data = array('status' => 0, 'message' => 'Ocurrió un error en la activación');
        $conditions = 0;
        if ( $this->input->post('password') == $this->input->post('passconf') ) { $conditions++; }
        if ( strlen($this->input->post('password')) > 0 ) { $conditions++; }
        
        if ( $conditions == 2 ) 
        {
            $row_user = $this->Account_model->activate($activation_key);

            $this->load->model('Account_model');
            $this->Account_model->create_session($row_user->username, 1);
            
            $data = array('status' => 1, 'message' => 'Usuario activado');
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// ACTUALIZACIÓN DE DATOS
//-----------------------------------------------------------------------------

    /**
     * AJAX JSON
     * Se validan los datos del usuario en sesión, los datos deben cumplir varios criterios
     * 
     * @param type $user_id
     */
    function validate_form()
    {
        $user_id = $this->session->userdata('user_id');

        $this->load->model('Account_model');
        $result = $this->Account_model->validate_form($user_id);
        
        //Enviar result
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($result));
    }

    /**
     * POST JSON
     * Actualiza los datos del usuario en sesión.
     * @param type $user_id
     */
    function update()
    {
        $arr_row = $this->input->post();
        $arr_row['display_name'] = $this->input->post('first_name') . ' ' . $this->input->post('last_name');
        $user_id = $this->session->userdata('user_id');

        $this->load->model('User_model');
        $data = $this->User_model->update($user_id, $arr_row);
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Ejecuta el proceso de cambio de contraseña de un usuario en sesión
     */
    function change_password()
    {
        $conditions = 0;
        $row_user = $this->Db_model->row_id('user', $this->session->userdata('user_id'));
        
        //Valores iniciales para el resultado del proceso
            $data = array('status' => 0, 'message' => 'La contraseña no fue modificada. ');
        
        //Verificar contraseña actual
            $validar_pw = $this->Account_model->validate_password($row_user->username, $this->input->post('current_password'));
            if ( $validar_pw['status'] ) {
                $conditions++;
            } else {
                $data['message'] = 'La contraseña actual es incorrecta. ';
            }
        
        //Verificar que contraseña nueva coincida con la confirmación
            if ( $this->input->post('password') == $this->input->post('passconf') ) {
                $conditions++;
            } else {
                $data['message'] .= 'La contraseña de confirmación no coincide.';
            }
        
        //Verificar condiciones necesarias
            if ( $conditions == 2 )
            {
                $this->Account_model->change_password($row_user->id, $this->input->post('password'));
                $data['status'] = 1;
                $data['message'] = 'La contraseña se cambió exitosamente.';
            }
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));   
    }

//RECUPERACIÓN DE CUENTAS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Formulario para restaurar contraseña o reactivar cuenta
     * se ingresa con nombre de usuario y contraseña
     */
    function recovery()
    {
        if ( $this->session->userdata('logged') )
        {
            redirect('app');
        } else {
            $data['head_title'] = 'Restauración de contraseña';
            $data['view_a'] = 'accounts/recovery_v';
            $this->load->view('templates/bootstrap/start_v', $data);
        }
    }

// ADMINISTRACIÓN DE CUENTA
//-----------------------------------------------------------------------------

    /** Perfil del usuario en sesión */
    function profile()
    {        
        $this->load->model('User_model');
        $data = $this->User_model->basic($this->session->userdata('user_id'));
        
        //Variables específicas
        $data['nav_2'] = 'accounts/menu_v';
        $data['view_a'] = 'accounts/profile_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Formulario para la edición de los datos del usuario en sessión. Los datos que se
     * editan dependen de la $section elegida.
     */
    function edit($section = 'basic')
    {
        //Datos básicos
        $user_id = $this->session->userdata('user_id');

        $this->load->model('User_model');
        $data = $this->User_model->basic($user_id);
        
        $view_a = "accounts/edit/{$section}_v";
        if ( $section == 'crop' )
        {
            $view_a = 'files/cropping_v';
            $data['image_id'] = $data['row']->image_id;
            $data['src_image'] = URL_UPLOADS . $data['row']->src_image;
            $data['back_destination'] = "accounts/edit/image";
        }
        
        //Array data espefícicas
            //$data['valores_form'] = $this->Pcrn->valores_form($data['row'], 'user');
            $data['nav_2'] = 'accounts/menu_v';
            $data['nav_3'] = 'accounts/edit/menu_v';
            $data['view_a'] = $view_a;
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

//IMAGEN DE PERFIL
//---------------------------------------------------------------------------------------------------

    /**
     * Carga archivo de imagen, y se la asigna como imagen de perfil al usuario
     * en sesión
     * @param type $user_id
     */
    function set_image()
    {
        $user_id = $this->session->userdata('user_id');

        //Cargue
        $this->load->model('File_model');
        
        $data_upload = $this->File_model->upload();
        
        $data = array('status' => 0, 'message' => 'La imagen no fue asignada');
        if ( $data_upload['status'] )
        {
            $this->load->model('User_model');
            $this->User_model->remove_image($user_id);                              //Quitar image actual, si tiene una
            $data = $this->User_model->set_image($user_id, $data_upload['row']->id);   //Asignar imagen nueva
        }

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    /**
     * AJAX
     * Desasigna y elimina la imagen asociada (si la tiene) al usuario en sesión.
     */
    function remove_image()
    {
        $user_id = $this->session->userdata('user_id');

        $this->load->model('User_model');
        $data = $this->User_model->remove_image($user_id);
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
    
// USER LOGIN AND REGISTRATION WITH GOOGLE ACCOUNT
//-----------------------------------------------------------------------------
    
    /**
     * Google Callback, recibe los datos después de solicitar autorización de
     * acceso a cuenta de Google de user.
     */
    function g_callback()
    {
        $g_client = $this->Account_model->g_client();
        
        $cf_redirect = 'accounts/login';
        
        if ( ! is_null($this->session->userdata('access_token')) )
        {
            //access_token exists, set in g_client
            $g_client->setAccessToken($this->session->userdata('access_token'));
        } else if ( $this->input->get('code') ) {
            //Google redirect to URL app/g_callback with GET variable (in URL) called 'code'
            $g_client->authenticate($this->input->get('code')); //Autenticate with this 'code'
            $access_token = $g_client->getAccessToken();        //
            $this->session->set_userdata('access_token', $access_token);
        }
        
        //Get data from the account
            $g_userinfo = $this->Account_model->g_userinfo($g_client);
        
        //Check if email already exists in the BD
            $row_user = $this->Db_model->row('user', "email = '{$g_userinfo['email']}'");

        //Create session or insert new user
            if ( ! is_null($row_user) )
            {
                $this->Account_model->create_session($row_user->username);
                $this->session->set_userdata('src_img', $g_userinfo['picture']);
                $cf_redirect = 'app/logged';
            } else {
                //Do not exists, insert new user
                $this->Account_model->g_register($g_userinfo);
            }
        
        redirect($cf_redirect);
    }
    
    function g_signup()
    {
        redirect('accounts/login');
    }
}