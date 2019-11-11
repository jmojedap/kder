<?php
    $options_gender = $this->Item_model->options('category_id = 59 AND cod <= 2', 'Sexo');
    $options_city = $this->App_model->options_place('type_id = 4', 'cr', 'Ciudad');
    $options_id_number_type = $this->Item_model->options('category_id = 53', 'Tipo documento');
?>

<div id="app_edit">
    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <div class="card-body">
            <form id="edit_form" accept-charset="utf-8" @submit.prevent="validate_send">
                <div class="form-group row">
                    <div class="offset-md-4 col-md-8">
                        <button class="btn btn-info btn-block" type="submit">
                            Guardar
                        </button>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="first_name" class="col-md-4 controle-label">Nombre y Apellidos</label>
                    <div class="col-md-4">
                        <input
                            id="field-first_name"
                            name="first_name"
                            class="form-control"
                            placeholder="Nombres"
                            title="Nombres"
                            required
                            autofocus
                            v-model="form_values.first_name"
                            >
                    </div>
                    <div class="col-md-4">
                        <input
                            id="field-last_name"
                            name="last_name"
                            class="form-control"
                            placeholder="Apellidos"
                            title="Apellidos"
                            required
                            v-model="form_values.last_name"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="display_name" class="col-md-4 controle-label">Mostrar como</label>
                    <div class="col-md-8">
                        <input
                            id="field-display_name"
                            name="first_name"
                            class="form-control"
                            placeholder="Nombre para mostrar"
                            title="Nombre para mostrar"
                            required
                            v-model="form_values.display_name"
                            >
                    </div>
                </div>

                <div class="form-group row" id="form-group_id_number">
                    <label for="id_number" class="col-md-4 controle-label">No. Documento</label>
                    <div class="col-md-4">
                        <input
                            id="field-id_number"
                            name="id_number"
                            class="form-control"
                            v-bind:class="{ 'is-invalid': ! validation.id_number_is_unique }"
                            placeholder="Número de documento"
                            title="Solo números, sin puntos, debe tener al menos 5 dígitos"
                            pattern=".{5,}[0-9]"
                            required
                            v-model="form_values.id_number"
                            v-on:change="validate_form"
                            >
                        <span class="invalid-feedback">
                            El número de documento escrito ya fue registrado para otro usuario
                        </span>
                    </div>
                    <div class="col-md-4">
                        <?php echo form_dropdown('id_number_type', $options_id_number_type, '', 'class="form-control" required v-model="form_values.id_number_type"') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="city_id" class="col-md-4 controle-label">Ciudad residencia</label>
                    <div class="col-md-8">
                        <?php echo form_dropdown('city_id', $options_city, $row->city_id, 'id="field-city_id" class="form-control select2"') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="birth_date" class="col-md-4 controle-label">Fecha de nacimiento</label>
                    <div class="col-md-8">
                        <input
                            id="field-birth_date"
                            name="birth_date"
                            class="form-control bs_datepicker"
                            v-model="form_values.birth_date"
                            type="date"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="gender" class="col-md-4 controle-label">Sexo</label>
                    <div class="col-md-8">
                        <?php echo form_dropdown('gender', $options_gender, $row->gender, 'class="form-control" required') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="celular" class="col-md-4 controle-label">No. celular</label>
                    <div class="col-md-8">
                        <input
                            id="field-phone_number"
                            name="phone_number"
                            class="form-control"
                            placeholder="Número celular"
                            title="Número celular"
                            v-model="form_values.phone_number"
                            >
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var form_values = {
            first_name: '<?php echo $row->first_name ?>',
            last_name: '<?php echo $row->last_name ?>',
            display_name: '<?php echo $row->display_name ?>',
            id_number: '<?php echo $row->id_number ?>',
            id_number_type: '0<?php echo $row->id_number_type ?>',
            city_id: '0<?php echo $row->city_id ?>',
            birth_date: '<?php echo $row->birth_date ?>',
            gender: '<?php echo $row->gender ?>',
            phone_number: '<?php echo $row->phone_number ?>',
    };
    new Vue({
    el: '#app_edit',
        data: {
            form_values: form_values,
            row_id: '<?php echo $row->id ?>',
            validation: {
                id_number_is_unique: true
            }
        },
        methods: {
            validate_form: function() {
                axios.post(url_api + 'accounts/validate_form/', $('#edit_form').serialize())
                .then(response => {
                    //this.formulario_valido = response.data.status;
                    this.validation = response.data.validation;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validate_send: function () {
                axios.post(url_api + 'accounts/validate_form/' + this.row_id, $('#edit_form').serialize())
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
                axios.post(url_api + 'accounts/update/', $('#edit_form').serialize())
                    .then(response => {
                        console.log('status: ' + response.data.message);
                        if (response.data.status == 1)
                        {
                        toastr['success']('Datos actualizados');
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            }
        }
    });
</script>