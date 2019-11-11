<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Order_model');
        $this->load->model('Post_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Listado de órdenes de compra
     */
    function explore()
    {
        $this->db->order_by('edited_at', 'DESC');
        $data['orders'] = $this->db->get('orders');

        $data['head_title'] = 'Órdenes de compra';
        $data['view_a'] = 'orders/explore_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Crear un nuevo pedido, tabla orders. Le agrega un producto inicial con cantidad 1.
     */
    function create($product_id)
    {
        $data = $this->Order_model->create();

        if ( $data['status'] )
        {
            $this->session->set_userdata('order_id', $data['order_id']);
            $this->Order_model->add_product($product_id);
        }

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    /**
     * Pasos en el proceso de compra:
     * Step 1: formulario para completar datos personales
     * Step 2: Verificación de datos y totales
     */
    function checkout($step = 1)
    {
        $order_id = $this->session->userdata('order_id');
        $data = $this->Order_model->basic($order_id);

        $data['products'] = $this->Order_model->products($order_id);
        $data['form_data'] = $this->Order_model->payu_form_data($order_id);
        $data['step'] = $step;

        $data['head_title'] = 'Completa tus datos';
        $data['view_a'] = "orders/checkout/step_{$step}_v";
        $this->App_model->view(TPL_FRONT, $data);
    }

    /**
     * Vista HTML, Página de respuesta, redireccionada desde PayU para mostrar el resultado
     * de una transacción de pago. Toma los datos de resultado de GET
     */
    function result()
    {
        $data = $this->Order_model->result_data();

        //Si el pago fue exitoso, se agrega el tipo de suscripción a las variables de sesión
        if ( $data['success'] )
        {
            $row_user = $this->Db_model->row('user', $this->session->userdata('user_id'));
            $suscription_type = $this->Order_model->suscription_type($row_user);
            $this->session->set_userdata('suscription_type', $suscription_type);
        }

        $data['step'] = 3;  //Tercer y último paso, resultado
        $data['view_a'] = "orders/checkout/result_v";
        $this->App_model->view(TPL_FRONT, $data);
    }

    /**
     * AJAX JSON
     * Actualiza los datos de un pedido
     * 2019-06-17
     */
    function update($order_id)
    {
        $data = $this->Order_model->update($order_id);
        
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }

    /**
     * Formulario para probar el resultado de ejecución de la página de confirmación
     * ejecutada por PayU remotamente
     */
    function test($type, $order_id)
    {
        $data = $this->Order_model->basic($order_id);

        $data['head_title'] = 'Test compras';
        $data['view_a'] = "orders/checkout/test_{$type}_v";
        $this->App_model->view(TPL_FRONT, $data);
    }

    /**
     * Página de confirmación que ejecuta remotamente PagosOnLine (pol) al 
     * terminar una transacción. Recibe datos de POL vía post, actualiza 
     * datos del pago del pedido
     */
    function confirmation_payu()
    {
        $confirmation_payu = $this->Order_model->confirmation_payu();
    }

    function test_email($order_id)
    {
        $row_order = $this->Db_model->row_id('orders', $order_id);
        $message = $this->Order_model->message_buyer($row_order);
        echo $message;
    }

// Compras y suscripciones de Usuarios
//-----------------------------------------------------------------------------

    function my_orders()
    {
        $user_id = $this->session->userdata('user_id');
        $this->load->model('User_model');
        $data = $this->User_model->basic_data($user_id);
        $data['orders'] = $this->Order_model->user_orders($user_id);
        
        //Variables específicas
        $data['nav_2'] = 'accounts/menu_v';
        $data['view_a'] = 'orders/my_orders_v';
        
        $this->App_model->view(TPL_FRONT, $data);
    }

    /**
     * Listado de suscripciones del usuario, activas e inactivas.
     * 2019-06-23
     */
    function my_suscriptions()
    {
        $user_id = $this->session->userdata('user_id');
        $this->load->model('User_model');
        $data = $this->User_model->basic_data($user_id);
        $data['suscriptions'] = $this->Order_model->user_suscriptions($user_id);
        
        //Variables específicas
        $data['nav_2'] = 'accounts/menu_v';
        $data['view_a'] = 'orders/my_suscriptions_v';
        
        $this->App_model->view(TPL_FRONT, $data);
    }
}