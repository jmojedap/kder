<?php
    $payu_data['merchant_id'] = '385merchant_id';
    $payu_data['state_pol'] = '4';
    $payu_data['risk'] = '816risk';
    $payu_data['response_code_pol'] = '1';
    $payu_data['reference_sale'] = $row->order_code;
    $payu_data['reference_pol'] = '895reference_pol';
    $payu_data['sign'] = '903sign';
    $payu_data['extra1'] = '636extra1';
    $payu_data['extra2'] = '588extra2';
    $payu_data['payment_method'] = '173payment_method';
    $payu_data['payment_method_type'] = '7';
    $payu_data['installments_number'] = '411installments_number';
    $payu_data['value'] = $row->amount;
    $payu_data['tax'] = '192tax';
    $payu_data['additional_value'] = '704additional_value';
    $payu_data['transaction_date'] = '999transaction_date';
    $payu_data['currency'] = 'COP';
    $payu_data['email_buyer'] = '566email_buyer';
    $payu_data['cus'] = '563cus';
    $payu_data['pse_bank'] = '710pse_bank';
    $payu_data['test'] = '501test';
    $payu_data['description'] = '382description';
    $payu_data['billing_address'] = '847billing_address';
    $payu_data['shipping_address'] = '702shipping_address';
    $payu_data['phone'] = '862phone';
    $payu_data['office_phone'] = '207office_phone';
    $payu_data['account_number_ach'] = '621account_number_ach';
    $payu_data['account_type_ach'] = '778account_type_ach';
    $payu_data['administrative_fee'] = '357administrative_fee';
    $payu_data['administrative_fee_base'] = '629administrative_fee_base';
    $payu_data['administrative_fee_tax'] = '900administrative_fee_tax';
    $payu_data['airline_code'] = '984airline_code';
    $payu_data['attempts'] = '768attempts';
    $payu_data['authorization_code'] = '912authorization_code';
    $payu_data['travel_agency_authorization_code'] = '587travel_agency_authorization_code';
    $payu_data['bank_id'] = '340bank_id';
    $payu_data['billing_city'] = '350billing_city';
    $payu_data['billing_country'] = '167billing_country';
    $payu_data['commision_pol'] = '413commision_pol';
    $payu_data['commision_pol_currency'] = '229commision_pol_currency';
    $payu_data['customer_number'] = '867customer_number';
    $payu_data['date'] = '223date';
    $payu_data['error_code_bank'] = '995error_code_bank';
    $payu_data['error_message_bank'] = '273error_message_bank';
    $payu_data['exchange_rate'] = '142exchange_rate';
    $payu_data['ip'] = '439ip';
    $payu_data['nickname_buyer'] = '115nickname_buyer';
    $payu_data['nickname_seller'] = '509nickname_seller';
    $payu_data['payment_method_id'] = '7';
    $payu_data['payment_request_state'] = '921payment_request_state';
    $payu_data['pseReference1'] = '164pseReference1';
    $payu_data['pseReference2'] = '954pseReference2';
    $payu_data['pseReference3'] = '555pseReference3';
    $payu_data['response_message_pol'] = 'APPROVED';
    $payu_data['shipping_city'] = '849shipping_city';
    $payu_data['shipping_country'] = '948shipping_country';
    $payu_data['transaction_bank_id'] = '924transaction_bank_id';
    $payu_data['transaction_id'] = '111transaction_id';
    $payu_data['payment_method_name'] = '820payment_method_name';
    
?>

<form action="<?php echo base_url('orders/confirmation_payu') ?>" accept-charset="utf-8" method="POST">
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-8 offset-md-4">
                    <button class="btn btn-success btn-block" type="submit">
                        Enviar
                    </button>
                </div>
            </div>

            <?php foreach ( $payu_data as $field => $field_value ) { ?>
                

            <div class="form-group row">
                <label for="" class="col-md-4"><?php echo $field ?></label>
                <div class="col-md-8">
                    <input
                        type="text"
                        name="<?php echo $field ?>"
                        required
                        class="form-control"
                        value="<?php echo $field_value ?>"
                        >
                </div>
            </div>

            <?php } ?>
            
        </div>
    </div>
</form>