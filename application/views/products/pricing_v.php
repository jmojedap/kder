<script>
    // Variables
    //-----------------------------------------------------------------------------
    var user_id = '<?php echo $this->session->userdata('user_id'); ?>';
    var suscription_type = '<?php echo $this->session->userdata('suscription_type'); ?>';

    // Document Ready
    //-----------------------------------------------------------------------------
    $(document).ready(function() {
        $('.btn_create_order').click(function() {
            if (user_id > 0) {
                var product_id = $(this).data('product_id');
                if ( suscription_type == 1 )
                {
                    toastr['success']('Ya tienes una suscripción activa a la aplicación');
                } else {
                    create_order(product_id);
                }
                //console.log(product_id);
            } else {
                window.location = app_url + 'accounts/signup/order';
                //$('#alert_signup').show('slow');
            }
            //create_order();
        });
    });

    // Functions
    //-----------------------------------------------------------------------------
    function create_order(product_id) {
        $.ajax({
            type: 'POST',
            url: app_url + 'orders/create/' + product_id,
            success: function(response) {
                console.log(response.message);
                /*if ( response.status == 1 ) {
                    window.location = app_url + 'orders/checkout';
                }*/
            }
        });
    }
</script>

<div class="alert alert-warning alert-dismissible fade show text-center" role="alert" id="alert_signup" style="display: none;">
    <i class="fa fa-user-plus"></i>
    Para continuar con la compra de tus suscripción debes registrarte.
    Haz clic <a href="<?php echo base_url('accounts/signup/order') ?>"><b>aquí</b></a>.
</div>

<div class="px-3 py-1 mx-auto text-center">
    <h1 class="display-4">Precios</h1>
    <h4>Elije tu plan</h4>
    <p class="lead">
        Suscríbete y disfruta de todas las publicaciones <b class="text-success"> exclusivas</b>
    </p>
</div>

<div class="card-deck mb-3 text-center">
    <?php foreach ( $products->result() as $row_product ) { ?>
        <?php
            $product_meta = json_decode($row_product->content_json);
        ?>
    <div class="card mb-4 shadow">
        <div class="card-header">
            <h4 class="my-0 font-weight-normal"><?php echo $row_product->name ?></h4>
        </div>
        <div class="card-body">
            <h1 class="card-title pricing-card-title">
                <?php echo $this->pml->money($row_product->price, 0); ?>
                <small class="text-muted">COP</small>
            </h1>
            <div style="min-height: 200px;">
                <?php echo $row_product->content ?>
            </div>
            <button type="button" class="btn btn-lg btn-block btn-success btn_create_order"
                data-product_id="<?php echo $row_product->id ?>">
                Comprar
            </button>
        </div>
    </div>
    <?php } ?>
</div>

<div class="py-1 mx-auto text-center">
    <p class="lead">
        Paga con <a href="https://www.payulatam.com/co/" target="_blank">PayU</a> a través de
    </p>
</div>

<div class="row mb-3">
    <div class="col-md-2">
        <img src="<?php echo URL_IMG ?>payu/medio_sured.png" alt="" class="img-thumbnail">
    </div>
    <div class="col-md-2">
        <img src="<?php echo URL_IMG ?>payu/medio_1.png" alt="" class="img-thumbnail">
    </div>
    <div class="col-md-2">
        <img src="<?php echo URL_IMG ?>payu/medio_efecty.png" alt="" class="img-thumbnail">
    </div>
    <div class="col-md-2">
        <img src="<?php echo URL_IMG ?>payu/medio_2.png" alt="" class="img-thumbnail">
    </div>
    <div class="col-md-2">
        <img src="<?php echo URL_IMG ?>payu/medio_bancos.png" alt="" class="img-thumbnail">
    </div>
    <div class="col-md-2">
        <img src="<?php echo URL_IMG ?>payu/medio_4.png" alt="" class="img-thumbnail">
    </div>
</div>