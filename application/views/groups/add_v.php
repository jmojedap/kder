<?php $this->load->view('assets/bs4_chosen') ?>

<?php
    $options_level = $this->Item_model->options('category_id = 3 AND item_group = 1', 'Nivel escolar');
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

    $options_teacher = $this->App_model->options_user($condition_teacher, 'Asignado a', 'du');
?>

<div id="app_insert">
    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <div class="card-body">
            <form id="add_form" accept-charset="utf-8" @submit.prevent="send_form">

                <?php if ( $this->session->userdata('institution_id') > 0 ) { ?>
                    <input type="hidden" name="institution_id" value="<?php echo $this->session->userdata('institution_id') ?>">
                <?php } else { ?>
                    <div class="form-group row">
                        <label for="institution_id" class="col-md-4 controle-label">Institución</label>
                        <div class="col-md-8">
                            <?php echo form_dropdown('institution_id', $options_institution, '', 'id="field-institution_id" class="form-control form-control-chosen" required v-model="form_values.institution_id"') ?>
                        </div>
                    </div>
                <?php } ?>

                <div class="form-group row">
                    <label for="level" class="col-md-4 controle-label">Nivel escolar</label>
                    <div class="col-md-8">
                        <?php echo form_dropdown('level', $options_level, '', 'id="field-level" class="form-control" required v-model="form_values.level"') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="letter" class="col-md-4 controle-label">Letra o número</label>
                    <div class="col-md-8">
                        <input
                            id="field-letter"
                            name="letter"
                            class="form-control"
                            placeholder="Ej. B"
                            title="Letra o número con el que se identifica al grupo"
                            required
                            v-model="form_values.letter"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="title" class="col-md-4 control-label">Título*</label>
                    <div class="col-md-8">
                        <div class="input-group input-group-icon">
                            <div class="input-group-prepend">
                                <button v-on:click="generate_title" class="btn input-group-text" type="button">
                                    Generar
                                </button>
                            </div>
                            <input
                                id="field-title"
                                type="text"
                                name="title"
                                value=""
                                class="form-control"
                                required
                                maxlength="30"
                                placeholder="Ej. Transición - A"
                                v-model="form_values.title"
                                v-on:focus="empty_generate_title"
                                >
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="teacher_id" class="col-md-4 controle-label">Asignado a:</label>
                    <div class="col-md-8">
                        <?php echo form_dropdown('teacher_id', $options_teacher, '', 'id="field-level" class="form-control form-control-chosen" required v-model="form_values.teacher_id"') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="schedule" class="col-md-4 controle-label">Jornada horario</label>
                    <div class="col-md-8">
                        <?php echo form_dropdown('schedule', $options_schedule, '', 'id="field-schedule" class="form-control" required v-model="form_values.schedule"') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="generation" class="col-md-4 col-form-label">Año generación</label>
                    <div class="col-md-8">
                        <input
                            type="number"
                            id="field-generation"
                            name="generation"
                            required
                            class="form-control"
                            placeholder="Ej. 2020"
                            title="Año en el que el grupo termina el periodo escolar"
                            v-model="form_values.generation"
                            min="2017"
                            max="2022"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="description" class="col-md-4 col-form-label">Descripción</label>
                    <div class="col-md-8">
                        <textarea
                            type="text"
                            id="field-description"
                            name="description"
                            class="form-control"
                            placeholder="Notas adicionales sobre el grupo"
                            title="Notas adicionales sobre el grupo"
                            v-model="form_values.description"
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
    /* var form_values = {
            institution_id: '0100',
            level: '093',
            letter: 'A',
            title: '',
            teacher_id : '0200116',
            schedule : '01',
            generation: '2020',
            description: 'Grupo ubicado en el salón más grande'
        }; */
    var form_values = {institution_id: '', level: '093', letter: '', title: '', 'teacher_id': '', schedule: '01', generation: '2020', description: ''};
    
    new Vue({
    el: '#app_insert',
        data: {
            form_values: form_values
        },
        methods: {
            send_form: function() {
                axios.post(app_url + 'groups/insert/', $('#add_form').serialize())
                    .then(response => {
                        console.log('status: ' + response.data.mensaje);
                        if (response.data.status == 1)
                        {
                            toastr['success']('El grupo fue creado con éxito');
                            setTimeout(() => {
                                window.location = app_url + 'groups/info/' + response.data.saved_id;
                            }, 2000);
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            },
            generate_title: function(){
                form_values.title = $("#field-level option:selected").text() + ' - ' + form_values.letter;
            },
            empty_generate_title: function(){
                if ( form_values.title.length == 0 ) { this.generate_title(); }
            }
        }
    });
</script>