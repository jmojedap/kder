<?php $this->load->view('assets/select2') ?>

<?php
    $options_city = $this->App_model->options_place('type_id = 4', 'CR', 'Ciudad');

    //Formulario destino
    $url_action = 'https://checkout.payulatam.com/ppp-web-gateway-payu/';
    if ( $form_data['test'] == 1 ) { $url_action = 'https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu'; }
?>

<div class="px-3 py-1 mx-auto text-center">
    <h1 class="display-4">Verifica tus datos</h1>
    <p class="lead">
        Verifica que los datos de tu compra estén completos y correctos
    </p>
</div>

<?php $this->load->view('orders/checkout/steps_v') ?>

<div class="row">
    <div class="col-md-6">
        

        <table class="table bg-white">
            <thead>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Total</th>
            </thead>
            <tbody>
                <?php foreach ( $products->result() as $product ) { ?>
                    <tr>
                        <td><?php echo $product->description ?></td>
                        <td><?php echo $product->quantity ?></td>
                        <td><?php echo $this->pml->money($product->price) ?></td>
                        <td><?php echo $this->pml->money($product->price * $product->quantity) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>Cód Orden</td>
                    <td><?php echo $row->order_code ?></td>
                </tr>

                <tr>
                    <td>Nombre</td>
                    <td><?php echo $row->buyer_name ?></td>
                </tr>

                <tr>
                    <td>Correo electrónico</td>
                    <td><?php echo $row->email ?></td>
                </tr>

                <tr>
                    <td>Ciudad</td>
                    <td>
                        <?php echo $row->city; ?>
                    </td>
                </tr>

                <tr>
                    <td>Dirección</td>
                    <td>
                        <?php echo $row->address; ?>
                    </td>
                </tr>

                <tr>
                    <td>Teléfono</td>
                    <td><?php echo $row->phone_number ?></td>
                </tr>

                <tr>
                    <td>Valor Total</td>
                    <td>
                        <b class="text-success">
                            <?php echo $this->pml->money($row->amount) ?>
                            <small>COP</small>
                        </b>
                    </td>
                </tr>
            </tbody>
        </table>

        <form accept-charset="utf-8" method="POST" action="<?php echo $url_action ?>">
            <?php foreach ( $form_data as $field_name => $field_value ) { ?>
                <input type="hidden" name="<?php echo $field_name ?>" value="<?php echo $field_value ?>">
            <?php } ?>
            <button class="btn btn-success btn-block">
                Ir a Pagar
            </button>
            <a class="btn btn-secondary btn-block mt-2" role="button" href="<?php echo base_url('orders/checkout/1') ?>">
                <i class="fa fa-chevron-left"></i>
                Volver
            </a>
        </form>
    </div>
</div>