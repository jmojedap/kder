<?php
class Order_model extends CI_Model{

    function basic($order_id)
    {
        $data['row'] = $this->Db_model->row_id('orders', $order_id);
        $data['head_title'] = $data['row']->order_code;

        return $data;
    }

    /**
     * Crear un pedido en la tabla orders
     */
    function create()
    {
        $data = array('status' => 0, 'message' => 'El pedido no fue creado');

        $row_user = $this->Db_model->row_id('user', $this->session->userdata('user_id'));
    
        //Construir registro
            $arr_row['buyer_name'] = $row_user->display_name;
            $arr_row['email'] = $row_user->email;
            $arr_row['address'] = 'Tu dirección';
            $arr_row['phone_number'] = $row_user->phone_number;
            $arr_row['user_id'] = $row_user->id;

            $arr_row['city'] = 'Bogotá DC - Colombia';    //Colombia
            $arr_row['country_id'] = 51;    //Colombia
            $arr_row['region_id'] = 267;    //Bogotá DC
            $arr_row['city_id'] = 909;      //Bogotá

        //Crear registro
            $this->db->insert('orders', $arr_row);
            $order_id = $this->db->insert_id();
    
        //Establecer resultado
        if ( $order_id > 0 )
        {
            $data = array('status' => 1, 'message' => 'Pedido creado');
            $data['order_id'] = $order_id;
            $data['order_code'] = $this->set_order_code($order_id);
        }
    
        return $data;
    }

    /**
     * Actualizar los datos de un pedido.
     */
    function update($order_id)
    {
        $arr_row = $this->Db_model->arr_row_edit(TRUE);

        //Establecer ciudad y datos
        if ( isset($arr_row['city_id']) )
        {
            $row_city = $this->Db_model->row_id('place', $arr_row['city_id']);
            $arr_row['city'] = $row_city->place_name . ' - ' . $row_city->region . ' - ' . $row_city->country;
            $arr_row['country_id'] = $row_city->country_id;
            $arr_row['region_id'] = $row_city->region_id;
        }

        $this->db->where('id', $order_id);
        $this->db->update('orders', $arr_row);
        
        $data = array('status' => 1, 'message' => 'Datos de pedido guardados');

        return $data;
    }

    /**
     * Agrega un producto en una cantidad definida a una orden, guarda el registro
     * en la tabla order_producto (op), devuelve ID del registro guardado.
     * 2019-06-17
     */
    function add_product($product_id, $quantity = 1)
    {
        $order_id = $this->session->userdata('order_id');

        $this->load->model('Product_model');
        $row_product = $this->Product_model->row($product_id);

        $arr_row['order_id'] = $order_id;
        $arr_row['product_id'] = $product_id;
        $arr_row['original_price'] = $row_product->price;
        $arr_row['price'] = $row_product->price;
        $arr_row['quantity'] = $quantity;

        $op_id = $this->Db_model->save('order_product', "order_id = {$arr_row['order_id']} AND product_id = {$arr_row['product_id']}", $arr_row);

        //Actualizar totales del pedido
        $this->update_totals($order_id);

        return $op_id;
    }

    /**
     * Genera y establece un código único para un pedido. Campo order.order_code
     * 2019-06-17
     */
    function set_order_code($order_id)
    {
        $this->load->helper('string');
        
        $order_code = 'VBN-' . strtoupper(random_string('alpha', 3)) . '-' . $order_id;

        $arr_row['order_code'] = $order_code;
        $arr_row['description'] = 'Suscripción ' . $order_code . ' en ' . APP_NAME;
        
        $this->db->where('id', $order_id);
        $this->db->update('orders', $arr_row);

        return $arr_row['order_code'];
    }

// CÁLCULO Y ACTUALIZACIÓN DE TOTALES
//-----------------------------------------------------------------------------

    /**
     * Actualiza los valores numéricos totales del pedido, a partir de los datos detallados en la tabla
     * order_product.
     * 2019-06-17
     */
    function update_totals($order_id)
    {
        $this->update_totals_1($order_id);  //Total productos
        $this->update_totals_3($order_id);  //Total valor, order.amount
    }

    function update_totals_1($order_id)
    {
        //Valor inicial por defecto
        $arr_row = $this->Db_model->arr_row_edit(FALSE);
        $arr_row['total_products'] = 0;
        $arr_row['total_tax'] = 0;

        //Consulta para calcular totales
        $this->db->select('SUM(order_product.price * quantity) AS total_products, SUM(order_product.tax * quantity) AS total_tax');
        $this->db->where('order_id', $order_id);
        $this->db->where('order_product.type_id', 1);  //Productos
        $query = $this->db->get('order_product');

        if ( $query->num_rows() > 0 ) 
        {
            $arr_row['total_products'] = $query->row()->total_products;
            $arr_row['total_tax'] = $query->row()->total_tax;
        }

        //Actualizar
        $this->db->where('id', $order_id);
        $this->db->update('orders', $arr_row);
    }

    /**
     * Actualiza los totales: order.amount
     * @param type $order_id
     */
    function update_totals_3($order_id)
    {
        $sql = "UPDATE orders SET amount = total_products + total_extras WHERE id = {$order_id}";
        $this->db->query($sql);
    }

// DATOS DEL PEDIDO
//-----------------------------------------------------------------------------

    //Productos incluidos en un pedido
    function products($order_id)
    {
        $this->db->select('products.name, products.description, order_product.*');
        $this->db->join('products', 'products.id = order_product.product_id');
        $this->db->where('order_id', $order_id);
        $products = $this->db->get('order_product');

        return $products;
    }

    /**
     * Devuelve un elemento row, de un pedido dado el código del pedido
     * @param type $order_code
     * @return type
     */
    function row_by_code($order_code) 
    {
        $row = $this->Db_model->row('orders', "order_code = '{$order_code}'");
        return $row;
    }

// CHECKOUT PayU
//-----------------------------------------------------------------------------

    /**
     * Array con todos los datos para construir el formulario que se envía a PayU
     * para iniciar el proceso de pago.
     */
    function payu_form_data($order_id)
    {
        //Registro del pedido
        $row = $this->Db_model->row_id('orders', $order_id);

        //Construir array
            $data['merchantId'] = K_PUMI;
            $data['referenceCode'] = $row->order_code;
            $data['description'] = $row->description;
            $data['amount'] = $row->amount;
            $data['tax'] = $row->total_tax;
            $data['taxReturnBase'] = 0; //No tiene IVA
            $data['signature'] = $this->payu_signature($row);
            $data['accountId'] = K_PUAI;
            $data['currency'] = 'COP';  //Pesos colombianos
            $data['test'] = ( $this->input->get('test') == 1 ) ? 1 : 0;
            $data['buyerFullName'] = $row->buyer_name;
            $data['buyerEmail'] = $row->email;
            $data['shippingAddress'] = $row->address;
            $data['shippingCity'] = $row->city;
            $data['shippingCountry'] = 'CO';
            $data['telephone'] = $row->phone_number;
            $data['responseUrl'] = base_url('orders/result');
            $data['confirmationUrl'] = base_url('orders/confirmation_payu');

        return $data;
    }

    /**
     * Genera la firma que se envía en el Formulario para ir al pago en PayU
     */
    function payu_signature($row_order)
    {
        $signature_pre = K_PUAK;
        $signature_pre .= '~' . K_PUMI;
        $signature_pre .= '~' . $row_order->order_code;
        $signature_pre .= '~' . $row_order->amount;
        $signature_pre .= '~' . 'COP';
        
        return md5($signature_pre);
    }

    /**
     * Tomar y procesar los datos POST que envía PayU a la página 
     * de confirmación.
     * url_confirmacion >> 'orders/confirmation_payu'
     * 
     * @return type
     */
    function confirmation_payu()
    {
        
        //Identificar Pedido
        $row = $this->row_by_code($this->input->post('reference_sale'));    

        if ( ! is_null($row) )
        {
            //Guardar array completo de confirmación en la tabla "meta"
                $row_suscription = $this->json_confirmation($row);

            //Actualizar registro de pedido
                $this->update_status($row_suscription);

            //Descontar cantidades de producto.cant_disponibles, si está en estado 3: pago confirmado
                /*if ( $order_status == 1 )
                {
                    //Pago confirmado
                    //$this->create_suscription($row->id);
                }*/

            //Enviar mensaje a administradores de tienda y al cliente
                $this->email_buyer($row->id);
                //if ( $order_status == 1 ) { $this->email_admon($row->id); }
                

            return $row->id;
        }
    }

    /**
     * Crea un registro en la tabla post, con los datos recibidos tras en la 
     * ejecución de la página de confirmación por parte de PayU.
     * 
     * @param type $row
     * @return type
     */
    function json_confirmation($row)
    {
        //Datos POL
            $arr_confirmation_payu = $this->input->post();
            $arr_confirmation_payu['ip_address'] = $this->input->ip_address();
            $json_confirmation_payu = json_encode($arr_confirmation_payu);
            $duration = $this->duration_sucription($row->id);
        
        //Construir registro para tabla Post
            $arr_row['type_id'] = 54;  //54: Confirmación de pago, Ver: items.category_id = 33
            $arr_row['post_name'] = 'Suscripción ' . $arr_confirmation_payu['reference_sale'];
            $arr_row['content'] = 'Duración: ' . $duration;
            $arr_row['content_json'] = $json_confirmation_payu;
            $arr_row['status'] = ( $arr_confirmation_payu['response_code_pol'] == 1 ) ? 1 : 0;
            $arr_row['parent_id'] = $row->id;
            $arr_row['related_1'] = $arr_confirmation_payu['response_code_pol'];
            $arr_row['related_2'] = $arr_confirmation_payu['payment_method_id'];
            $arr_row['date_1'] = date('Y-m-d H:i:s');
            $arr_row['date_2'] = date("Y-m-d H:i:S", strtotime($arr_row['date_1'] . $duration));
            $arr_row['text_1'] = $arr_confirmation_payu['sign'];
            $arr_row['text_2'] = $arr_confirmation_payu['response_message_pol'];
            $arr_row['edited_at'] = date('Y-m-d H:i:s');
            $arr_row['created_at'] = date('Y-m-d H:i:s');
            $arr_row['editor_id'] = $row->user_id;
            $arr_row['creator_id'] = $row->user_id;
        
        //Guardar
            $condition = "type_id = 54 AND parent_id = {$row->id}";
            $this->Db_model->save('post', $condition, $arr_row);
            $suscription_id = $this->db->insert_id();

        //Row de confirmación
            $row_suscription = $this->Db_model->row_id('suscriptions', $suscription_id);
        
        return $row_suscription;
    }

    /**
     * Devuelve string con expresión para con PHP hacer suma de fechas en la definición del rango
     * activo de una suscripción pagada.
     */
    function duration_sucription($order_id)
    {
        $duration = '+ 1 month';    //Valor por defecto

        $this->db->select('duration');
        $this->db->where('order_id', $order_id);
        $this->db->join('products', 'order_product.product_id = products.id');
        $query = $this->db->get('order_product');

        if ( $query->num_rows() > 0 ) { $duration = $query->row()->duration; }

        return $duration;
    }

    /**
     * Actualiza el estado de un pedido, dependiendo del código de respuesta en la 
     * confirmación
     */
    function update_status($row_suscription)
    {
        $arr_row['status'] = ( $row_suscription->response_code_pol == 1 ) ? 1 : 5;
        $arr_row['response_code_pol'] = $row_suscription->response_code_pol;
        $arr_row['edited_at'] = date('Y-m-d H:i:s');
        $arr_row['editor_id'] = 10000;  //ID User procesos automáticos

        $this->db->where('id', $row_suscription->order_id);
        $this->db->update('orders', $arr_row);
    }

    function result_data()
    {
        $order_code = $this->input->get('referenceCode');
        $row = $this->row_by_code($order_code);

        $data = array('status' => 0, 'message' => 'Compra no identificada', 'success' => 0);
        $data['success'] = 0;
        $data['order_id'] = 0;
        $data['head_title'] = 'Pago no realizado';

        if ( ! is_null($row) )
        {
            $data['status'] = 1;
            $data['message'] = 'Resultado recibido';
            $data['order_id'] = $row->id;

            if ( $this->input->get('polResponseCode') == 1 )
            {
                $data['success'] = 1;
                $data['head_title'] = 'Pago exitoso';
            }
        }

        return $data;
    }

    /**
     * Pedidos realizados por el usuario, tabla orders.
     */
    function user_orders($user_id)
    {
        $this->db->select('*');
        $this->db->where('user_id', $user_id);
        $orders = $this->db->get('orders');

        return $orders;
    }

    /**
     * Suscripciones asociadas a un usuario, guardadas de la tabla post, y tomadas
     * a través de la vista sql "suscriptions". Las suscripciones son registros creados
     * tras la confirmación de pago por PayU.
     */
    function user_suscriptions($user_id)
    {
        $this->db->where('creator_id', $user_id);
        $orders = $this->db->get('suscriptions');

        return $orders;
    }

    /**
     * Tipo de suscripción actual que tiene el usuario en sesión. 0 si no tiene ninguna
     * suscripción pagada.
     */
    function suscription_type($row_user)
    {
        $suscription_type = 0;  //Valor por defecto

        //Buscar suscripciones activas
            $this->db->where('status', 1);  //Esté pagada
            $this->db->where('creator_id', $row_user->id);  //Esté pagada
            $this->db->where('date_2 >=', date('Y-m-d H:i:s')); //Fecha 2 posterior a fecha actual
            $suscriptions = $this->db->get('suscriptions');

            if ( $suscriptions->num_rows() > 0 ) { $suscription_type = 1; }

        //Si es administrador, tiene suscripción automática permanente
            if ( $row_user->role <= 1 ) { $suscription_type = 1; }

        return $suscription_type;
    }

// MENSAJES DE CORREO ELECTRÓNICO
//-----------------------------------------------------------------------------

    /**
     * Tras la confirmación PayU, se envía un mensaje de estado del pedido
     * al cliente
     * 
     * @param type $order_id
     */
    function email_buyer($order_id)
    {
        $row_order = $this->Db_model->row_id('orders', $order_id);
        $admin_email = $this->Db_model->field_id('sis_option', 25); //Opción 25
            
        //Asunto de mensaje
            $subject = "Estado de la compra {$row_order->order_code}: " . $this->Item_model->name(10, $row_order->response_cod_pol);
        
        //Enviar Email
            $this->load->library('email');
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $this->email->from('info@' . APP_DOMAIN, APP_NAME);
            $this->email->to($row_order->email);
            $this->email->bcc($admin_email);
            $this->email->subject($subject);
            $this->email->message($this->message_buyer($row_order));
            
            $this->email->send();   //Enviar
            
    }

    /**
     * String con contenido del mensaje del correo electrónico enviado al comprador
     * después de recibir la confirmación de pago
     */
    function message_buyer($row_order)
    {
        $data['row_order'] = $row_order;
        $data['products'] = $this->products($row_order->id);

        $str_style = file_get_contents(URL_RESOURCES . 'css/email.json');
        $data['style'] = json_decode($str_style);
        
        $message = $this->load->view('orders/emails/message_buyer_v', $data, TRUE);
        
        return $message;
    }
}