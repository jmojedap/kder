<?php $this->load->view('assets/bs4_chosen') ?>

<?php
    $options_institution = $this->App_model->options_institution('id > 0');
    $options_charge_type = $this->Item_model->options('category_id = 172');
    $options_generation = $this->App_model->options_generation();
?>

<div id="app_edit">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="edit_form" accept-charset="utf-8" @submit.prevent="send_form">

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
                        <?= form_dropdown('charge_type_id', $options_charge_type, '', 'class="form-control" v-model="form_values.charge_type_id"') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="generation" class="col-md-4 col-form-label text-right">Año generación</label>
                    <div class="col-md-8">
                        <?= form_dropdown('generation', $options_generation, '0', 'class="form-control" v-model="form_values.generation"') ?>
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
    //Cargar valor en formulario
    var form_values = <?= json_encode($row) ?>;
    form_values.charge_type_id = '0<?= $row->charge_type_id ?>';
    form_values.generation = '0<?= $row->generation ?>';
    form_values.date_2 = '<?= substr($row->date_2, 0, 10) ?>';
    
    new Vue({
    el: '#app_edit',
        data: {
            row_id: form_values.id,
            form_values: form_values
        },
        methods: {
            send_form: function() {
                axios.post(url_app + 'charges/save/' + this.row_id, $('#edit_form').serialize())
                    .then(response => {
                        console.log('status: ' + response.data.message);
                        if (response.data.status == 1)
                        {
                            toastr['success']('Guardado');
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            }
        }
    });
</script>