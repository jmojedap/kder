<?php
    $options_city = $this->App_model->options_place('type_id = 4', 'cr', 'Ciudad');
    $options_id_number_type = $this->Item_model->options('category_id = 53 AND filters LIKE "%organization%"', 'Tipo documento');
?>

<?php $this->load->view('assets/summernote') ?>
<?php $this->load->view('assets/bs4_chosen') ?>

<div id="app_edit">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="edit_form" accept-charset="utf-8" @submit.prevent="validate_send">
                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-right">Nombre comercial</label>
                    <div class="col-md-8">
                        <input
                            id="field-name"
                            name="name"
                            class="form-control"
                            placeholder="Nombre"
                            title="Nombres comercial o marca"
                            required
                            autofocus
                            v-model="form_values.name"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="full_name" class="col-md-4 col-form-label text-right">Nombre Completo / Razón social</label>
                    <div class="col-md-8">
                        <input
                            id="field-full_name"
                            name="full_name"
                            class="form-control"
                            placeholder="Nombre mostrar"
                            title="Nombre mostrar"
                            v-model="form_values.full_name"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-right">Correo electrónico</label>
                    <div class="col-md-4">
                        <input
                            id="field-email"
                            name="email"
                            class="form-control"
                            v-bind:class="{ 'is-invalid': ! validation.email_is_unique }"
                            placeholder="Correo electrónico"
                            title="Correo electrónico"
                            v-model="form_values.email"
                            v-on:change="validate"
                            >
                        <span class="invalid-feedback">
                            El correo electrónico escrito ya fue registrado para otra institución
                        </span>
                    </div>
                </div>

                <div class="form-group row" id="form-group_id_number">
                    <label for="id_number" class="col-md-4 col-form-label text-right">Documento / Tipo</label>
                    <div class="col-md-4">
                        <input
                            id="field-id_number"
                            name="id_number"
                            class="form-control"
                            v-bind:class="{ 'is-invalid': ! validation.id_number_is_unique }"
                            placeholder="Número de documento"
                            title="Solo números, sin puntos, debe tener al menos 5 dígitos"
                            required
                            pattern=".{5,}[0-9]"
                            v-model="form_values.id_number"
                            v-on:change="validate"
                            >
                        <span class="invalid-feedback">
                            El número de documento escrito ya fue registrado para otra institución
                        </span>
                    </div>
                    <div class="col-md-4">
                        <?php echo form_dropdown('id_number_type', $options_id_number_type, '', 'class="form-control" required v-model="form_values.id_number_type"') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="generation" class="col-md-4 col-form-label text-right">Año generación actual</label>
                    <div class="col-md-8">
                        <input
                            type="number"
                            min="<?php echo date('Y') - 2 ?>"
                            max="<?php echo date('Y') + 2 ?>"
                            id="field-generation"
                            name="generation"
                            required
                            class="form-control"
                            placeholder="Año generación actual"
                            title="Año generación actual"
                            v-model="form_values.generation"
                            >
                        <small id="generation_help" class="form-text text-muted">Año en el que terminan los grupos actuales</small>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="address" class="col-md-4 col-form-label text-right">Dirección</label>
                    <div class="col-md-8">
                        <input
                            type="text"
                            id="field-address"
                            name="address"
                            required
                            class="form-control"
                            placeholder=""
                            title="Dirección"
                            v-model="form_values.address"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="city_id" class="col-md-4 control-form-label text-right">Ciudad ubicación</label>
                    <div class="col-md-8">
                        <?php echo form_dropdown('city_id', $options_city, $row->city_id, 'id="field-city_id" class="form-control form-control-chosen" required') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="celular" class="col-md-4 col-form-label text-right">Celular</label>
                    <div class="col-md-8">
                        <input
                            id="field-phone_number"
                            name="phone_number"
                            type="number"
                            class="form-control"
                            placeholder="Número celular"
                            title="Número celular"
                            v-model="form_values.phone_number"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-md-4 col-md-8">
                        <button class="btn btn-info w120p" type="submit">
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
            name: '<?php echo $row->name ?>',
            full_name: '<?php echo $row->full_name ?>',
            email: '<?php echo $row->email ?>',
            id_number: '<?php echo $row->id_number ?>',
            id_number_type: '0<?php echo $row->id_number_type ?>',
            address: '<?php echo $row->address ?>',
            city_id: '0<?php echo $row->city_id ?>',
            phone_number: '<?php echo $row->phone_number ?>',    
            generation: '<?php echo $row->generation ?>'            
        };
    new Vue({
    el: '#app_edit',
        data: {
            form_values: form_values,
            row_id: '<?php echo $row->id ?>',
            validation: {
                email_is_unique: true,
                id_number_is_unique: true
            }
        },
        methods: {
            validate: function() {
                axios.post(app_url + 'institutions/validate/' + this.row_id, $('#edit_form').serialize())
                .then(response => {
                    //this.formulario_valido = response.data.status;
                    this.validation = response.data.validation;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validate_send: function () {
                axios.post(app_url + 'institutions/validate/' + this.row_id, $('#edit_form').serialize())
                .then(response => {
                    if (response.data.status == 1) {
                    this.send_form();
                    } else {
                    toastr['error']('Revise las casillas en rojo');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            send_form: function() {
                axios.post(app_url + 'institutions/update/' + this.row_id, $('#edit_form').serialize())
                    .then(response => {
                        result = 'error';
                        if ( response.data.status == 1 ){ result = 'success'; }
                        toastr[result](response.data.message);

                    })
                    .catch(function (error) {
                        console.log(error);
                });
            }
        }
    });
</script>