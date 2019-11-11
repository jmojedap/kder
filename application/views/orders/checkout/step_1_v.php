<?php $this->load->view('assets/select2') ?>

<?php
    $options_city = $this->App_model->options_place('type_id = 4', 'CR', 'Ciudad');
?>

<script>
// Variables
//-----------------------------------------------------------------------------
    var order_id = <?php echo $row->id; ?>;

// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function(){
        $('#checkout_form').submit(function(){
            update_order();
            return false;
        });
    });

// Functions
//-----------------------------------------------------------------------------
    function update_order(){
        $.ajax({        
            type: 'POST',
            url: app_url + 'orders/update/' + order_id,
            data: $('#checkout_form').serialize(),
            success: function(response){
                console.log(response.message);
                if ( response.status == 1 ) { window.location = app_url + 'orders/checkout/2'; }
            }
        });
    }
</script>

<div class="px-3 py-1 mx-auto text-center">
    <h1 class="display-4">Completa tus datos</h1>
    <p class="lead">
        Completa los datos requeridos por
        <a href="https://www.payulatam.com/co/compradores/" target="_blank">PayU</a>
        para realizar la compra
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

        <form accept-charset="utf-8" method="POST" id="checkout_form">
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="city_id" class="col-md-3 control-label">Tu ciudad</label>
                        <div class="col-md-9">
                            <?php echo form_dropdown('city_id', $options_city, $row->city_id, 'id="field-city_id" class="form-control select2" required') ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="address" class="col-md-3 control-label">Dirección</label>
                        <div class="col-md-9">
                            <input
                                id="field-address"
                                name="address"
                                class="form-control"
                                required
                                value="<?php echo $row->address ?>"
                                type="text"
                                title="Escribe tu dirección"
                                >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="phone_number" class="col-md-3 control-label">Teléfono</label>
                        <div class="col-md-9">
                            <input
                                id="field-phone_number"
                                name="phone_number"
                                class="form-control"
                                required
                                minlength="7"
                                value="<?php echo $row->phone_number ?>"
                                type="text"
                                title="Escribe tu número de teléfono"
                                >
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-9 offset-md-3">
                            <button class="btn btn-success btn-block">
                                Continuar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>