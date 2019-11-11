<?php
    $payu_data['merchantId'] = '854merchantId';
    $payu_data['transactionState'] = '4';
    $payu_data['risk'] = '992risk';
    $payu_data['polResponseCode'] = '6';
    $payu_data['referenceCode'] = $row->order_code;
    $payu_data['reference_pol'] = '7069375';
    $payu_data['signature'] = 'FIRMA98791654';
    $payu_data['polPaymentMethod'] = '400';
    $payu_data['polPaymentMethodType'] = '869255';
    $payu_data['installmentsNumber'] = '502signature';
    $payu_data['TX_VALUE'] = $row->amount;
    $payu_data['TX_TAX'] = '256polPaymentMethodType';
    $payu_data['buyerEmail'] = '236installmentsNumber';
    $payu_data['processingDate'] = date('Y-m-d H:i:s');
    $payu_data['currency'] = 'COP';
    $payu_data['cus'] = 'cus987654';
    $payu_data['pseBank'] = 'Bancolombia';
    $payu_data['lng'] = 'ES';
    $payu_data['description'] = '143cus';
    $payu_data['lapResponseCode'] = 'APPROVED';
    $payu_data['lapPaymentMethod'] = 'VISA';
    $payu_data['lapPaymentMethodType'] = '102description';
    $payu_data['lapTransactionState'] = 'APPROVED';
    $payu_data['message'] = '474lapPaymentMethod';
    $payu_data['extra1'] = '654lapPaymentMethodType';
    $payu_data['extra2'] = '718lapTransactionState';
    $payu_data['extra3'] = '759message';
    $payu_data['authorizationCode'] = '317extra1';
    $payu_data['merchant_address'] = '439extra2';
    $payu_data['merchant_name'] = '402extra3';
    $payu_data['merchant_url'] = '359authorizationCode';
    $payu_data['orderLanguage'] = '731merchant_address';
    $payu_data['pseCycle'] = '229merchant_name';
    $payu_data['pseReference1'] = '633merchant_url';
    $payu_data['pseReference2'] = '191orderLanguage';
    $payu_data['pseReference3'] = '244pseCycle';
    $payu_data['telephone'] = '992pseReference1';
    $payu_data['transactionId'] = '606pseReference2';
    $payu_data['trazabilityCode'] = '198pseReference3';
    $payu_data['TX_ADMINISTRATIVE_FEE'] = '958telephone';
    $payu_data['TX_TAX_ ADMINISTRATIVE_FEE'] = '795transactionId';
    $payu_data['TX_TAX_ADMINISTRATIVE'] = '192trazabilityCode';
    $payu_data['_FEE_RETURN_BASE'] = '136TX_ADMINISTRATIVE_FEE';
?>

<form action="<?php echo base_url('orders/result/') ?>" accept-charset="utf-8" method="GET">
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