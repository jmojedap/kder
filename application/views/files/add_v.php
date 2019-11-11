<script>
    //Docoment Ready
    $(document).ready(function(){

        //Al submit formulario, prevenir evento por defecto y ejecutar función ajax
        $('#file_form').submit(function()
        {
            send_form();
            return false;
        });
    });

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
            url: url_api + 'files/upload/',
            data: form_data,
            beforeSend: function(){
                $('#status_text').html('Enviando archivo');
            },
            success: function(response){
                console.log(response.message);
                ///*$('#status_text').html(response.message);
                if ( response.status == 1 )
                {
                    window.location = app_url + 'files/cropping/' + response.row.id;
                }
            }
        });
    }
</script>

<div id="add_file" style="max-width: 700px; margin: 0 auto;">
    <div class="card">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="file_form" @submit.prevent="send_form">
                <div class="form-group row">
                    <label for="file_field" class="col-md-3 col-form-label ">Archivo</label>
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
                        <button class="btn btn-success btn-block" type="submit">
                            Cargar
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <!-- div para cargar resultados recibidos: response.html_results -->
    <div id="html_results"></div>
</div>