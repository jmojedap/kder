<?php
    $elements['icon'] = 'fa fa-check-circle';
    $elements['class'] = 'text-success';

    if ( ! $success )
    {
        $success = FALSE;
        $elements['icon'] = 'fa fa-exclamation-triangle';
        $elements['class'] = 'text-warning';
    }
?>

<div class="px-3 py-1 mx-auto text-center">
    <h1 class="display-4 <?php echo $elements['class'] ?>">
        <i class="<?php echo $elements['icon'] ?>"></i>
        <?php echo $head_title ?>
    </h1>
    <p class="lead">
        Resultado de la transacción: 
        <b class="<?php echo $elements['class'] ?>"><?php echo $this->Item_model->name(10, $this->input->get('polResponseCode')); ?></b>
    </p>
</div>

<?php $this->load->view('orders/checkout/steps_v') ?>

<?php if ( $success == TRUE ) { ?>
    <div class="jumbotron">
        <h1 class="display-4">¡Ya eres Suscriptor!</h1>
        <p class="lead">
            Ahora puedes disfrutar de todo el contenido de nuestras bonitas.
        </p>
        <p class="lead">
            <a class="btn btn-success btn-lg" href="<?php echo base_url('girls/explore') ?>" role="button">
                <i class="fa fa-heart"></i>
                Ver bonitas
            </a>
        </p>
    </div>

<?php } else {?>
    <div class="jumbotron">
        <h1 class="display-4">Tu pago no se realizó</h1>
        <p class="lead">
            Verifica el resultado de la transacción en tu correo electrónico o escríbenos un mensaje a nuestra Fanpage en Facebook para más información.
        </p>
        <p class="lead">
            <a class="btn btn-primary btn-lg" href="<?php echo base_url('products/pricing') ?>" role="button">
                Volver
            </a>
        </p>
    </div>
<?php } ?>

<h3>Datos finales de la transacción</h3>

<table class="table bg-white">
    <tbody>
        <tr class="table-info">
            <td>Código compra</td>
            <td><?php echo $this->input->get('referenceCode'); ?></td>
        </tr>
        <tr>
            <td>Fecha transacción</td>
            <td><?php echo $this->input->get('processingDate'); ?></td>
        </tr>
        <tr>
            <td>Referencia Transacción PayU</td>
            <td><?php echo $this->input->get('reference_pol'); ?></td>
        </tr>
        <tr>
            <td>Medio de pago</td>
            <td><?php echo $this->input->get('lapPaymentMethod'); ?></td>
        </tr>
        <tr>
            <td>Código Único de Seguimiento</td>
            <td><?php echo $this->input->get('cus'); ?></td>
        </tr>
        <tr>
            <td>Banco</td>
            <td><?php echo $this->input->get('pseBank'); ?></td>
        </tr>
        <tr>
            <td>Valor</td>
            <td>
                <?php echo $this->pml->money($this->input->get('TX_VALUE')); ?>
                <small>
                    <?php echo $this->input->get('currency'); ?>
                </small>
            </td>
        </tr>
    </tbody>
</table>

<div style="height: 200px;"></div>