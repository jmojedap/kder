<?php $this->load->view('assets/bs4_chosen') ?>

<?php
    /*$options_level = $this->Item_model->options('category_id = 3 AND item_group = 1', 'Nivel escolar');
    $options_institution = $this->App_model->options_institution('id > 0');
    $options_schedule = $this->Item_model->options('category_id = 17', 'Jornada');
    
    //Es usuario de una institución o interno
    if ( $this->session->userdata('institution_id') > 0 )
    {
        $condition_teacher = "role < 20 AND role > 10 AND institution_id = {$this->session->userdata('institution_id')}";
    } else {
        $condition_teacher = "role < 20 AND role > 10";
        $options_institution = $this->App_model->options_institution('id > 0');
    }

    $options_teacher = $this->App_model->options_user($condition_teacher, 'Asignado a', 'du');*/
?>

<div id="app_insert">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="add_form" accept-charset="utf-8" @submit.prevent="send_form">
                <input type="hidden" name="related_1" value="213639">
                <div class="form-group row">
                    <label for="post_name" class="col-md-4 col-form-label text-right">Asunto</label>
                    <div class="col-md-8">
                        <input
                            type="text"
                            id="field-post_name"
                            name="post_name"
                            required
                            class="form-control"
                            placeholder="Asunto"
                            title="Asunto"
                            v-model="form_values.post_name"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="excerpt" class="col-md-4 col-form-label text-right">Anotación</label>
                    <div class="col-md-8">
                        <textarea
                            id="field-excerpt"
                            name="excerpt"
                            required
                            class="form-control"
                            placeholder="Anotación"
                            title="Anotación"
                            v-model="form_values.excerpt"
                            maxlength="280"
                            rows="2"
                            ></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="content" class="col-md-4 col-form-label text-right">Detalle</label>
                    <div class="col-md-8">
                        <textarea
                            id="field-content"
                            name="content"
                            class="form-control"
                            placeholder="Detalle de la anotación"
                            title="Detalle de la anotación"
                            v-model="form_values.content"
                            rows="10"
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
     var form_values = {
            post_name: 'Aumento de revistas',
            excerpt: 'Aumento de revistas 50',
            content: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis optio quae autem sed necessitatibus nihil earum nesciunt cum minus quidem expedita laboriosam iusto, non, soluta eos consequuntur quos itaque animi.At, sed deserunt! Inventore, debitis. Molestias, sunt. Deserunt exercitationem maxime totam vitae, expedita earum laudantium ipsum porro, nemo, dolorum libero soluta facere quo consectetur eum quam autem. Ab, nisi sapiente?'
        };
    //var form_values = {institution_id: '', level: '093', letter: '', title: '', 'teacher_id': '', schedule: '01', generation: '2020', description: ''};
    
    new Vue({
    el: '#app_insert',
        data: {
            form_values: form_values
        },
        methods: {
            send_form: function() {
                axios.post(url_app + 'notes/insert/', $('#add_form').serialize())
                    .then(response => {
                        console.log('status: ' + response.data.mensaje);
                        if (response.data.status == 1)
                        {
                            toastr['success']('Anotación guardada');
                            setTimeout(() => {
                                window.location = url_app + 'notes/info/' + response.data.saved_id;
                            }, 2000);
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            }
        }
    });
</script>