<?php
    $arr_fields = array(
        'file_name' => 'Nombre archivo',
        'title' => 'Título',
        'subtitle' => 'Subtítulo',
        'description' => 'Descripción',
        'keywords' => 'Palabras clave',
        'link' => 'Link'
    );
?>

<script>
    var file_id = <?= $row->id ?>;

    $(document).ready(function(){
        $('#file_form').submit(function(){
            update_file();
            return false;
        });

// Funciones
//-----------------------------------------------------------------------------
    function update_file(){
        $.ajax({        
            type: 'POST',
            url: url_app + 'files/update/' + file_id,
            data: $('#file_form').serialize(),
            success: function(response){
                if ( response.status == 1 )
                {
                    toastr['success']('Guardado');
                }
            }
        });
    }
    });
</script>

<form accept-charset="utf-8" method="POST" id="file_form">
    <div class="card center_box_750">
        <div class="card-body">
            <?php foreach ( $arr_fields as $field => $title ) { ?>
                <div class="form-group row">
                    <label for="<?= $field ?>" class="col-md-4 col-form-label text-right"><?= $title ?></label>
                    <div class="col-md-8">
                        <input
                            type="text"
                            name="<?= $field ?>"
                            class="form-control"
                            title="<?= $title ?>"
                            value="<?= $row->$field ?>"
                            >
                    </div>
                </div>
            <?php } ?>
            <div class="form-group row">
                <div class="col-md-8 offset-md-4">
                    <button class="btn btn-success w120p" type="submit">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>