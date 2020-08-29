<?php
    $att_img = $this->App_model->att_img_user($row);
    $att_img['id'] = 'img_user';
    $att_img['class'] = 'img-rounded img-bordered img-bordered-primary';
    $att_img['width'] = '100%';

    $destination_form = "users/set_image/{$row->id}";
?>

<script>
// Variables
//-----------------------------------------------------------------------------

    var user_id = <?= $row->id ?>;
    var src_default = '<?= URL_IMG . 'users/user.png' ?>';
    
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
            url: url_api + 'users/set_image/' + user_id,
            data: form_data,
            beforeSend: function(){
                //$('#status_text').html('Enviando archivo');
            },
            success: function(response){
                if ( response.status == 1 )
                {
                    window.location = url_app + 'users/edit/' + user_id +'/cropping';
                }
            }
        });
    }
    
    //Ajax
    function remove_image()
    {
       $.ajax({
            type: 'POST',
            url: url_app + 'users/remove_image/' + user_id,
            success: function (response) {
                console.log(response.status);
                if ( response.status == 1 )
                {
                    $('#user_image').attr('src', src_default);
                    $('#btn_remove_image').hide();
                    $('#btn_crop').hide();
                    toastr['success']('Imagen de usuario eliminada');
                }
            }
        });
    }
</script>

<div class="card" style="max-width: 500px; margin: 0px auto;">
    <img id="user_image" src="<?= $att_img['src'] ?>" alt="Imagen usuario" width="100%" class="card-img-top">
    <div class="card-body">
        <?php if ( $row->image_id > 0 ) { ?>
            <a class="btn btn-info" id="btn_crop" href="<?= base_url("users/edit/{$row->id}/cropping") ?>">
                <i class="fa fa-crop"></i>
                Recortar
            </a>
            <button class="btn btn-warning" id="btn_remove_image">
                <i class="fa fa-trash"></i>
                Eliminar
            </button>
        <?php } ?>
        
        <?php $this->load->view('common/process_result_v'); ?>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form accept-charset="utf-8" method="POST" id="file_form">
            <div class="form-group row">
                <label for="file_field" class="col-md-3 col-form-label text-right">Archivo</label>
                <div class="col-md-9">
                    <input type="file" name="file_field" required="1" accept="image/*">
                </div>
            </div>

            <div class="form-group row">
                <div class="offset-md-3 col-md-9">
                    <button class="btn btn-success w120p" type="submit">Cargar</button>
                </div>
            </div>
        </form>
    </div>
</div>