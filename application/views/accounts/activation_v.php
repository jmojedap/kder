<?php //$this->load->view('assets/toastr'); ?>

<?php

    //Textos
        $texts['subtitle'] = 'Activación de cuenta';
        $texts['button'] = 'Activar mi cuenta';
        
        if ( $activation_type == 'recovery' )
        {
            $texts['subtitle'] = 'Reestablecer contraseña';
            $texts['button'] = 'Guardar';
        }
?>

<div id="activation_app">
    <div class="" style="text-align: center; padding-bottom: 0px;">
        <h2 class="white"><?php echo $texts['subtitle'] ?></h2>
        <h4 class="white"><?php echo $row->first_name . ' ' . $row->last_name ?></h4>
        <p class="text-muted">
            <i class="fa fa-user"></i>
            <?php echo $row->username ?>
        </p>
        <p>Establece tu contraseña para <?php echo APP_NAME ?></p>
    </div>

    <form id="activation_form" method="post" accept-charset="utf-8" @submit.prevent="send_form">
        <div class="form-group">
            <input
                type="password"
                name="password"
                class="form-control"
                placeholder="contrase&ntilde;a"
                required
                autofocus
                title="Debe tener un número y una letra minúscula, y al menos 8 caractéres"
                pattern="(?=.*\d)(?=.*[a-z]).{8,}"
                >
        </div>
        <div class="form-group">
            <input
                type="password"
                name="passconf"
                class="form-control"
                placeholder="confirma tu contrase&ntilde;a"
                required
                title="passconf contrase&ntilde;a"
                >
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">
                <?php echo $texts['button'] ?>
            </button>
        </div>
    </form>

    <div class="alert alert-danger" v-show="!hide_message">
        <i class="fa fa-info-circle"></i>
        Las contraseñas no coinciden
    </div>

</div>

<script>
    new Vue({
        el: '#activation_app',
        data: {
            app_url: '<?php echo base_url() ?>',
            activation_key: '<?php echo $activation_key ?>',
            hide_message: true
        },
        methods: {
            send_form: function(){
                
                axios.post(this.app_url + 'accounts/activate/' + this.activation_key, $('#activation_form').serialize())
                .then(response => {
                    this.hide_message = response.data.status;
                    if ( response.data.status == 1 ) {
                        window.location = this.app_url + 'app/logged';
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        }
    });
</script>