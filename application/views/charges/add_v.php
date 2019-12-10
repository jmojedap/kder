<?php $this->load->view('assets/bs4_chosen') ?>

<?php
    $options_institution = $this->App_model->options_institution('id > 0');
    $options_charge_type = $this->Item_model->options('category_id = 172');
    $options_generation = $this->App_model->options_generation();
    
    //Es usuario de una institución o interno
    /*if ( $this->session->userdata('institution_id') > 0 )
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

                <?php if ( $this->session->userdata('institution_id') > 0 ) { ?>
                    <input type="hidden" name="institution_id" value="<?php echo $this->session->userdata('institution_id') ?>">
                <?php } else { ?>
                    <div class="form-group row">
                        <label for="institution_id" class="col-md-4 controle-label text-right">Institución</label>
                        <div class="col-md-8">
                            <?php echo form_dropdown('institution_id', $options_institution, '', 'id="field-institution_id" class="form-control form-control-chosen" required v-model="form_values.institution_id"') ?>
                        </div>
                    </div>
                <?php } ?>

                <div class="form-group row">
                    <label for="title" class="col-md-4 col-form-label text-right">Nombre</label>
                    <div class="col-md-8">
                        <input
                            type="text"
                            id="field-title"
                            name="title"
                            required
                            class="form-control"
                            placeholder="Nombre"
                            title="Nombre"
                            v-model="form_values.title"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="charge_type_id" class="col-md-4 col-form-label text-right">Tipo</label>
                    <div class="col-md-8">
                        <?php echo form_dropdown('charge_type_id', $options_charge_type, '', 'class="form-control" v-model="form_values.charge_type_id"') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="generation" class="col-md-4 col-form-label text-right">Año generación</label>
                    <div class="col-md-8">
                        <?php echo form_dropdown('generation', $options_generation, '0', 'class="form-control" v-model="form_values.generation"') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="charge_value" class="col-md-4 col-form-label text-right">Valor</label>
                    <div class="col-md-8">
                        <input
                            type="text"
                            id="field-charge_value"
                            name="charge_value"
                            required
                            class="form-control"
                            placeholder="Valor del cobro"
                            title="Valor del cobro"
                            v-model="form_values.charge_value"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="date_2" class="col-md-4 col-form-label text-right">Fecha máxima de pago</label>
                    <div class="col-md-8">
                        <input
                            type="date"
                            id="field-date_2"
                            name="date_2"
                            required
                            class="form-control"
                            v-model="form_values.date_2"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="excerpt" class="col-md-4 col-form-label text-right">Descripción</label>
                    <div class="col-md-8">
                        <textarea
                            type="text"
                            id="field-excerpt"
                            name="excerpt"
                            class="form-control"
                            placeholder="Descripción"
                            title="Descripción"
                            rows="3"
                            v-model="form_values.excerpt"
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
        institution_id: '0100',
        title: 'Matrícula 2020',
        charge_type_id: '010',
        charge_value: '250000',
        date_2: '2020-01-31',
        generation: '02020',
        excerpt: 'El pago corresponde a la matrícula de 2020'
    };
    //var form_values = {institution_id: '', level: '093', letter: '', title: '', 'teacher_id': '', schedule: '01', generation: '2020', description: ''};
    
    new Vue({
    el: '#app_insert',
        data: {
            form_values: form_values
        },
        methods: {
            send_form: function() {
                axios.post(app_url + 'charges/save/', $('#add_form').serialize())
                    .then(response => {
                        console.log('status: ' + response.data.message);
                        if (response.data.status == 1)
                        {
                            toastr['success']('Cobro creado');
                            setTimeout(() => {
                                window.location = app_url + 'charges/info/' + response.data.saved_id;
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