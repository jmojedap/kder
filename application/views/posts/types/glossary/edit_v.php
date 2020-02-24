<?php $this->load->view('assets/summernote') ?>

<?php
    $arr_fields = array(
        'status' => 'Status',
        'slug' => 'Slug',
        'keywords' => 'Palabras clave',
        'text_1' => 'Módulo',
        'text_2' => 'Elemento'
    );
?>

<script>
    var post_id = <?php echo $row->id ?>;

    $(document).ready(function(){
        $('#field-content').summernote({
            lang: 'es-ES',
            height: 300
        });

        $('#post_form').submit(function(){
            update_post();
            return false;
        });

// Funciones
//-----------------------------------------------------------------------------
    function update_post(){
        $.ajax({        
            type: 'POST',
            url: app_url + 'posts/update/' + post_id,
            data: $('#post_form').serialize(),
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

<div id="edit_post">
    <form accept-charset="utf-8" method="POST" id="post_form">
        <div class="card center_box_750">
            <div class="card-body">
                <div class="form-group row">
                    <label for="post_name" class="col-md-4 col-form-label text-right">Título Palabra</label>
                    <div class="col-md-8">
                        <input
                            type="text"
                            name="post_name"
                            required
                            class="form-control"
                            placeholder="post name"
                            title="post name"
                            value="<?php echo $row->post_name ?>"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-right" for="excerpt">Definición</label>
                    <div class="col-md-8">
                        <textarea name="excerpt" id="field-excerpt" rows="5" class="form-control" required><?php echo $row->excerpt ?></textarea>
                    </div>
                </div>

                <?php foreach ( $arr_fields as $field => $title ) { ?>
                    <div class="form-group row">
                        <label for="<?php echo $field ?>" class="col-md-4 col-form-label text-right"><?php echo $title ?></label>
                        <div class="col-md-8">
                            <input
                                type="text"
                                name="<?php echo $field ?>"
                                class="form-control"
                                title="<?php echo $field ?>"
                                value="<?php echo $row->$field ?>"
                                >
                        </div>
                    </div>
                <?php } ?>

                <div class="form-group row">
                    <div class="col-md-8 offset-md-4">
                        <button class="btn btn-success w120p" type="submit">
                            <i class="fa fa-save"></i>
                            Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>