<script>
// Variables
//-----------------------------------------------------------------------------
    var file_id = '<?= $row->id ?>';

// Document ready
//-----------------------------------------------------------------------------
    $(document).ready(function(){
        $('#file_form').submit(function(){
            send_form();
            return false;
        });
    });

// Enviar formulario
//-----------------------------------------------------------------------------

    function send_form(){
        $.ajax({        
            type: 'POST',
            url: url_app + 'files/update/' + file_id,
            data: $('#file_form').serialize(),
            success: function(response){
                if ( response.status == 1) {
                    toastr['success'](response.message);
                } else {
                    toastr['info'](response.message);
                }
            }
        });
    }
</script>

<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="card">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="file_form">
                    <div class="form-group row">
                        <label for="title" class="col-sm-3 control-label">Título archivo</label>
                        <div class="col-sm-9">
                            <input
                                type="text"
                                name="title"
                                required
                                class="form-control"
                                placeholder="Título archivo"
                                title="Título archivo"
                                value="<?= $row->title ?>"
                                >
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="subtitle" class="col-sm-3 control-label">Subtítulo</label>
                        <div class="col-sm-9">
                            <input
                                type="text"
                                name="subtitle"
                                class="form-control"
                                placeholder="Subtítulo archivo"
                                title="Subtítulo archivo"
                                value="<?= $row->subtitle ?>"
                                >
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="keywords" class="col-sm-3 control-label">Palabras clave *</label>
                        <div class="col-sm-9">
                            <input
                                type="text"
                                id="field-keywords"
                                name="keywords"
                                class="form-control"
                                placeholder="Palabras clave"
                                title="Palabras clave"
                                value="<?= $row->keywords ?>"
                                >
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="descripcion" class="col-sm-3 control-label">Descripción</label>
                        <div class="col-sm-9">
                            <textarea
                                type="text"
                                id="field-description"
                                name="description"
                                class="form-control"
                                placeholder="Descripción"
                                title="Descripción"
                                ><?= $row->description ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="link" class="col-sm-3 control-label">Link</label>
                        <div class="col-sm-9">
                            <input
                                type="url"
                                id="field-external_link"
                                name="external_link"
                                class="form-control"
                                placeholder="Link que se abre al hacer clic en el archivo"
                                title="Link que se abre al hacer clic en el archivo"
                                value="<?= $row->external_link ?>"
                                >
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="offset-sm-3 col-sm-9">
                            <button class="btn btn-success btn-block">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>    
    </div>
    
    <div class="col-md-6 col-sm-12">
        <img src="<?= $src ?>" alt="Imagen archivo" class="rounded mb-2" style="width: 100%; max-width: 500px;">
        <br/>
        <a href="<?= base_url("files/change/{$row->id}") ?>" class="btn btn-primary" title="Cambiar esta imagen">
            Cambiar
        </a>
    </div>
</div>



