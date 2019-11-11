<?php if ( $this->uri->segment(3) == $this->session->userdata('user_id') ) { ?>
    <div id="app_password" class="card" style="max-width: 550px; margin: 0px auto;">
        <div class="card-body">
            
            <form accept-charset="utf-8" id="password_form" @submit.prevent="send_form">
                
                <div class="form-group row">
                    <label for="current_password" class="col-md-5 control-label">
                        <span class="float-right">Contraseña actual</span>
                    </label>
                    <div class="col-md-7">
                        <input
                            id="field-current_password"
                            name="current_password"
                            type="password"
                            class="form-control"
                            placeholder="Escriba su contraseña actual"
                            title="Contraseña actual"
                            required
                            autofocus
                            >
                    </div>
                </div>
                
                <div class="form-group row">
                        <label for="password" class="col-md-5 control-label">
                            <span class="float-right">Nueva contraseña</span>
                        </label>
                        <div class="col-md-7">
                            <input
                                id="field-password"
                                name="password"
                                type="password"
                                class="form-control"
                                placeholder="Nueva contraseña"
                                title="Al menos un número y una letra minúscula, y al menos 8 caractéres"
                                pattern="(?=.*\d)(?=.*[a-z]).{8,}"
                                required
                                >
                        </div>
                    </div>
            
                <div class="form-group row">
                    <label for="passconf" class="col-md-5 control-label">
                        <span class="float-right">Confirmar contraseña</span>
                    </label>
                    <div class="col-md-7">
                        <input
                            id="field-passconf"
                            name="passconf"
                            type="password"
                            class="form-control"
                            placeholder="Confirme la nueva contraseña"
                            title="Confirme la nueva contraseña"
                            pattern="(?=.*\d)(?=.*[a-z]).{8,}"
                            required
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-7 offset-md-5">
                        <button class="btn btn-success btn-block" type="submit">
                            Cambiar
                        </button>
                    </div>
                </div>
            </form>

            <div class="alert alert-success" role="alert" id="success_alert" style="display: none;">
                <i class="fa fa-check"></i>
                La contraseña fue cambiada exitosamente.
            </div>
            <div class="alert alert-danger" role="alert" id="error_alert" style="display: none;">
                <i class="fa fa-exclamation-triangle"></i>
                <span id="error_text"></span>
            </div>
        </div>
    </div>
<?php } ?>

<script>

// Document ready
//-----------------------------------------------------------------------------
    $(document).ready(function(){
        $('#password_form').submit(function(){
            change_password();
            return false;
        });
    });

// Funciones
//-----------------------------------------------------------------------------

    function change_password(){
        $.ajax({        
            type: 'POST',
            url: app_url + 'accounts/change_password',
            data: $('#password_form').serialize(),
            success: function(response){
                if ( response.status == 1 ) {
                    $('#success_alert').show() 
                    $('#error_alert').hide() 
                } else {
                    $('#error_text').html(response.message) 
                    $('#error_alert').show() 
                }
                clean_form();
            }
        });
    }

    function clean_form()
    {
        $('#field-current_password').val('');
        $('#field-password').val('');
        $('#field-passconf').val('');
    }
</script>