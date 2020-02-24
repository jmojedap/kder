<?php
    $att_img = $this->App_model->att_img_user($row);
    $destination_form = "users/set_image/{$row->id}";

    $style_image_section = '';
    if ( $row->image_id == 0 ) { $style_image_section = 'display: none;';}

    $style_form_section = 'display: none;';
    if ( $row->image_id == 0 ) { $style_form_section = '';}
?>

<script>
// Variables
//-----------------------------------------------------------------------------
    var user_id = '<?php echo $row->id ?>';
    var src_default = '<?php echo URL_IMG ?>app/nd.png';

// Document Ready
//-----------------------------------------------------------------------------
    $(document).ready(function(){

        //Al submit formulario, prevenir evento por defecto y ejecutar función ajax
        $('#file_form').submit(function()
        {
            send_form();
            return false;
        });

        $('#btn_remove_image').click(function(){
            remove_image();
        });
    });

// Functions
//-----------------------------------------------------------------------------

    /* Función AJAX para envío de archivo JSON a plataforma */
    function send_form()
    {
        var form = $('#file_form')[0];
        var form_data = new FormData(form);

        $.ajax({        
            type: 'POST',
            enctype: 'multipart/form-data', //Para incluir archivos en POST
            processData: false,  // Important!
            contentType: false,
            cache: false,
            url: app_url + 'users/set_image/' + user_id,
            data: form_data,
            beforeSend: function(){
                $('#status_text').html('Enviando archivo');
            },
            success: function(response){
                if ( response.status == 1 )
                {
                    $('#user_image').attr('src', response.src);
                    $('#image_section').show();
                    $('#image_form').hide();
                    $('#file_form')[0].reset();
                } else{
                    $('#upload_response').html(response.html);
                }
            }
        });
    }

    //Ajax
    function remove_image()
    {
       $.ajax({
            type: 'POST',
            url: app_url + 'users/remove_image/' + user_id,
            success: function (response) {
                if ( response.status == 1 )
                {
                    $('#user_image').attr('src', src_default);
                    $('#image_section').hide();
                    $('#image_form').show();
                    toastr['info']('La imagen de usuario fue eliminada');
                }
            }
        });
    }
</script>

<div class="card center_box_450" id="image_section" style="<?php echo $style_image_section ?>">
    <img
        id="user_image"
        class="card-img-top"
        width="100%"
        src="<?php echo $att_img['src'] ?>"
        alt="Imagen usuario"
        onerror="<?php echo $att_img['onerror'] ?>"
    >
    <div class="card-body">
        

        <a class="btn btn-info" id="btn_crop" href="<?php echo base_url("posts/cropping/{$row->id}") ?>">
            <i class="fa fa-crop"></i> Recortar
        </a>
        <button class="btn btn-warning" id="btn_remove_image">
            <i class="fa fa-trash"></i> Eliminar
        </button>
    </div>
</div>

<div id="image_form" style="<?php echo $style_form_section ?>">
    <div class="card center_box_750">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="file_form">
                <div class="form-group row">
                    <label for="file_field" class="col-md-3 col-form-label text-right">Archivo</label>
                    <div class="col-md-9">
                        <input
                            type="file"
                            name="file_field"
                            required
                            class="form-control"
                            placeholder="Archivo"
                            title="Arcivo a cargar"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-9 offset-md-3">
                        <button class="btn btn-success w120p" type="submit">
                            Cargar
                        </button>
                    </div>
                </div>
            </form>
            <div id="upload_response"></div>
        </div>
    </div>
</div>