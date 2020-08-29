<script src='https://www.google.com/recaptcha/api.js'></script>

<div id="recovery_app" class="text-center">

    <div v-show="app_status == 'start'">
        <p>
            Escribe tu dirección de correo electrónico.
            Te enviaremos un enlace para que asignes una nueva contraseña.
        </p>

        

        <form id="app_form" @submit.prevent="send_form" >
            <div class="form-group">
                <label class="sr-only" for="email">Correo electrónico</label>
                <input
                    name="email"
                    type="email"
                    class="form-control"
                    placeholder="Correo electrónico"
                    required
                    value=""
                    title="Correo electrónico"
                    >
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Enviar</button>
            </div>
        </form>
    </div>

    <div class="alert alert-warning" role="alert" v-show="no_user">
        <i class="fa fa-user-slash"></i>
        <br/>
        No existe ningún usuario registrado con
        el correo electrónico escrito.
    </div>

    <div class="card" style="margin-bottom: 10px;" v-show="app_status == 'sent'">
        <div class="card-body">
            <i class="fa fa-check fa-2x text-success"></i>
            <p>
                Enviamos a tu correo electrónico
                un enlace para que reestablezcas
                tu contraseña.
            <p>
            <p>
                Recuerda revisar también tu carpeta
                de correo no deseado.
            </p>
        </div>
    </div>

    <p>¿No tienes una cuenta? <a href="<?= base_url('accounts/signup') ?>">Regístrate</a></p>
</div>

<script>
    var api_url = '<?= URL_API ?>';
    new Vue({
        el: '#recovery_app',
        data: {
            app_status: 'start',
            no_user: false
        },
        methods: {
            send_form: function(){
                axios.post(api_url + 'accounts/recover/', $('#app_form').serialize())
                .then(response => {
                    console.log(response.data.status);
                    if ( response.data.status == 1 ) {
                        this.no_user = false;
                        this.app_status = 'sent';
                    } else if ( response.data.status == 2 ) {
                        this.no_user = true;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        }
    });
</script>
