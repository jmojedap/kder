<script>
// Variables
//-----------------------------------------------------------------------------
    var element_id = '<?= $row->id ?>';
    var src_default = '<?= URL_IMG ?>app/nd.png';

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

    /* Función AJAX para envío de imagen */
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
            url: url_app + 'institutions/set_image/' + element_id,
            data: form_data,
            beforeSend: function(){
                $('#status_text').html('Enviando archivo');
            },
            success: function(response){
                console.log(response.message);
                ///*$('#status_text').html(response.message);
                if ( response.status == 1 )
                {
                    $('#element_image').attr('src', response.src);
                    $('#section_image').removeClass('d-none');
                    $('#section_upload').addClass('d-none');
                    $('#file_form')[0].reset();
                    toastr['success']('Imagen cargada');
                }
            }
        });
    }

    //Ajax
    function remove_image()
    {
       $.ajax({
            type: 'POST',
            url: url_app + 'institutions/remove_image/' + element_id,
            success: function (response) {
                console.log(response.status);
                if ( response.status == 1 )
                {
                    $('#element_image').attr('src', src_default);
                    $('#section_image').addClass('d-none');
                    $('#section_upload').removeClass('d-none');
                    toastr['info']('La imagen del elemento fue eliminada');
                }
            }
        });
    }
</script>