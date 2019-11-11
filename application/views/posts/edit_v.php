<?php $this->load->view('assets/summernote') ?>

<?php
    $arr_fields = array(
        'status',
        'parent_id',
        'related_1',
        'text_1',
        'text_2',
        'integer_1',
        'integer_2',
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
                    toastr['success'](response.message);
                }
            }
        });
    }
    });
</script>

<div id="edit_post" style="max-width: 1500px; margin: 0px auto;">
    <form accept-charset="utf-8" method="POST" id="post_form">
        <div class="row">
            <div class="col-md-7">
                <textarea name="content" id="field-content" class="form-control"><?php echo $row->content ?></textarea>
                <br>

                <div class="form-group">
                    <label for="content_json">content json</label>
                    <textarea name="content_json" id="field-content_json" rows="3" class="form-control"><?php echo $row->content_json ?></textarea>
                </div>

                <div class="form-group">
                    <label for="excerpt">excerpt</label>
                    <textarea name="excerpt" id="field-excerpt" rows="3" class="form-control"><?php echo $row->excerpt ?></textarea>
                </div>

            </div>
            <div class="col-md-5">
                <div class="form-group row">
                    <div class="col-md-9 offset-md-3">
                        <button class="btn btn-success btn-block" type="submit">
                            Guardar
                        </button>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="post_name" class="col-md-3 col-form-label">Post Name</label>
                    <div class="col-md-9">
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
                    <label for="type_id" class="col-md-3 col-form-label">Type</label>
                    <div class="col-md-9">
                        <?php echo form_dropdown('type_id', $options_type, $row->type_id, 'class="form-control"') ?>
                    </div>
                </div>

                <?php foreach ( $arr_fields as $field ) { ?>
                    <div class="form-group row">
                        <label for="<?php echo $field ?>" class="col-md-3 col-form-label"><?php echo str_replace('_',' ',$field) ?></label>
                        <div class="col-md-9">
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
            </div>
        </div>
    </form>
</div>