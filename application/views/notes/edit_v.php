

<?php
    $options_type = $this->Item_model->options("category_id = 191", 'Tipo de anotación');
?>

<div id="app_edit">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="edit_form" accept-charset="utf-8" @submit.prevent="send_form">
                <div class="form-group row">
                    <label for="post_name" class="col-md-4 col-form-label text-right">Asunto</label>
                    <div class="col-md-8">
                        <input
                            type="text"
                            id="field-post_name"
                            name="post_name"
                            required
                            class="form-control"
                            placeholder=""
                            title=""
                            maxlength="70"
                            v-model="form_values.post_name"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="cat_1" class="col-md-4 col-form-label text-right">Tipo</label>
                    <div class="col-md-8">
                        <?= form_dropdown('cat_1', $options_type, '', 'class="form-control" required v-model="form_values.cat_1"') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="excerpt" class="col-md-4 col-form-label text-right">Anotación</label>
                    <div class="col-md-8">
                        <textarea
                            type="text"
                            id="field-excerpt"
                            name="excerpt"
                            required
                            class="form-control"
                            placeholder=""
                            title=""
                            rows="2"
                            maxlength="280"
                            v-model="form_values.excerpt"
                            ></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="content" class="col-md-4 col-form-label text-right">Especificación</label>
                    <div class="col-md-8">
                        <textarea
                            type="text"
                            id="field-content"
                            name="content"
                            class="form-control"
                            placeholder=""
                            title=""
                            rows="6"
                            v-model="form_values.content"
                            ></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-md-4 col-md-8">
                        <button class="btn btn-success w120p" type="submit">
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    //Loading values in variable
    var form_values = {
            post_name: '<?= $row->post_name ?>',
            excerpt: '<?= $row->excerpt ?>',
            content: '<?= $row->content ?>',
            cat_1: '0<?= $row->cat_1 ?>',
            status: '0<?= $row->status ?>'
    };
    new Vue({
    el: '#app_edit',
        data: {
            form_values: form_values,
            row_id: '<?= $row->id ?>'
        },
        methods: {
            send_form: function() {
                axios.post(url_app + 'notes/save/' + this.row_id, $('#edit_form').serialize())
                    .then(response => {
                        if (response.data.status == 1)
                        {
                        toastr['success']('Guardado');
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            },
        }
    });
</script>
<?php $this->load->view('assets/autosize') ?>