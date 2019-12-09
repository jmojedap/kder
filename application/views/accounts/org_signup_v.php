

<?php $this->load->view('assets/recaptcha') ?>

<div id="signup_app">
    
    <form id="signup_form" @submit.prevent="register">
        <!-- Campo para validación Google ReCaptcha V3 -->
        <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
        <div class="form-group">
            <label class="sr-only" for="first_name">Nombre</label>
            <input
                class="form-control"
                name="first_name"
                placeholder="Nombres"
                required
                autofocus
                title="Nombre, debe tener al menos dos caracteres"
                minlength="2"
                >
        </div>

        <div class="form-group">
            <label class="sr-only" for="last_name">Apellidos</label>
            <input
                class="form-control"
                name="last_name"
                placeholder="Apellidos"
                required
                title="Apellidos, debe tener al menos dos caracteres"
                minlength="2"
                >
        </div>

        <div class="form-group">
            <label class="sr-only" for="email">Correo electrónico</label>
            <input
                name="email"
                type="email"
                class="form-control"
                placeholder="ejemplo@gmail.com"
                required
                value=""
                title="Correo electrónico de Gmail"
                v-bind:class="{'is-invalid': !validation.email_is_valid}"
                v-on:change="validate_form"
                aria-describedby="basic-addon2"
                >
            <div class="invalid-feedback" v-show="!validation.email_is_gmail">
                El correo electrónico debe ser de @gmail.com
            </div>
            <div class="invalid-feedback" v-show="!validation.email_is_unique">
                Este correo electrónico ya fue registrado
            </div>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Crear cuenta</button>
        </div>
        <div class="text-center mb-2">
            O usa tu cuenta de Gmail para ingresar
        </div>
        <div class="form-group">
            <a href="<?php echo $g_client->createAuthUrl(); ?>" class="btn btn-light btn-block btn_google" style="" title="Registrarme utilizando mi cuenta de Google">
                <img src="<?php echo URL_IMG . 'app/google.png'?>" style="width: 20px">
                Ingresa con Google
            </a>
        </div>
        
    </form>

    

    <p class="text-center">¿Ya tienes una cuenta? <a href="<?php echo base_url('accounts/login') ?>">Iniciar sesión</a></p>
</div>



<script>
    var app_url = '<?php echo base_url() ?>';
    var url_api = '<?php echo URL_API ?>';
    new Vue({
        el: '#signup_app',
        data: {
            validation: {
                email_is_valid: true,
                email_is_unique: true,
                email_is_gmail: true,
                username_is_unique: true
            },
            validated: 0
        },
        methods: {
            register: function(){
                if ( this.validated ) {
                    
                    axios.post(url_api + 'accounts/register/', $('#signup_form').serialize())
                    .then(response => {
                        console.log(response.data.message);
                        if ( response.data.status == 1 ) {
                            window.location = app_url + 'accounts/registered/' + response.data.user_id;
                        } else {
                            this.recaptcha_message = response.data.recaptcha_message;
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }
            },
            validate_form: function(){
                var form_data = $('#signup_form').serialize();
                
                axios.post(url_api + 'accounts/validate_signup', form_data)
                .then(response => {
                    this.validated = response.data.status;
                    this.validation = response.data.validation;
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        }
    });
</script>