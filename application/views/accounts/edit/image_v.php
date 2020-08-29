<script>
// Variables
//-----------------------------------------------------------------------------

    var user_id = <?= $row->id ?>;
    var src_default = '<?= URL_IMG ?>users/user.png';
    
// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function()
    {
        $('#btn_remove_image').click(function(){
            remove_image();
        });

        $('#file_form').submit(function()
        {
            set_image();
            return false;
        });
    });
    
// Funciones
//-----------------------------------------------------------------------------
    
    /* Función AJAX para envío de archivo JSON a plataforma */
    function set_image()
    {
        var form = $('#file_form')[0];
        var form_data = new FormData(form);

        $.ajax({        
            type: 'POST',
            enctype: 'multipart/form-data', //Para incluir archivos en POST
            processData: false,  // Important!
            contentType: false,
            cache: false,
            url: url_api + 'accounts/set_image/',
            data: form_data,
            beforeSend: function(){
                //$('#status_text').html('Enviando archivo');
            },
            success: function(response){
                if ( response.status == 1 )
                {
                    window.location = url_app + 'accounts/edit/crop/';
                }
            }
        });
    }

    //Ajax
    function remove_image()
    {
       $.ajax({
            type: 'POST',
            url: url_api + 'accounts/remove_image/',
            success: function (response) {
                console.log(response.status);
                if ( response.status == 1 )
                {
                    $('#user_image').attr('src', src_default);
                    $('#btn_remove_image').hide();
                    $('#btn_crop').hide();
                    toastr['success']('Imagen de perfil eliminada');
                }
            }
        });
    }
</script>

<?php
    $att_img = $this->App_model->att_img_user($row);
?>

<div class="row">
    <div class="col-md-4">
        <img
            id="user_image"
            class="img-rounded img-bordered img-bordered-primary mb-2"
            src="<?= $att_img['src'] ?>"
            alt=""
            width="100%"
            >
            <?php if ( $row->image_id > 0 ) { ?>
                <a class="btn btn-default" id="btn_crop" href="<?= base_url("accounts/edit/crop") ?>">
                    <i class="fa fa-crop"></i>
                    Recortar
                </a>
                <button class="btn btn-warning" id="btn_remove_image">
                    <i class="fa fa-times"></i>
                    Quitar imagen
                </button>
            <?php } ?>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="file_form">

                    <div class="form-group row">
                        <label for="field_file" class="col-md-4 col-form-label text-right">Archivo imagen</label>
                        <div class="col-md-8">
                            <input
                                type="file"
                                name="file_field"
                                required
                                class="form-control"
                                placeholder="Archivo"
                                title="Arcivo a cargar"
                                accept="image/*"
                                >
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="offset-md-4 col-md-4">
                            <button class="btn btn-success w120p" type="submit">Cargar</button>
                        </div>
                    </div>
                </form>
                
                
            </div>
        </div>
    
    </div>
</div>
