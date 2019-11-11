<?php $this->load->view('assets/select2') ?>

<?php
    $options_city = $this->App_model->options_place('type_id = 4', 'cr', 'Ciudad');
    $options_id_number_type = $this->Item_model->options('category_id = 53 AND filters LIKE "%-organization-%"', 'Tipo documento');
?>

<div id="app_insert">
    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <div class="card-body">
            <form id="add_form" accept-charset="utf-8" @submit.prevent="validate_send">
                <div class="form-group row">
                    <label for="name" class="col-md-4 controle-label">Nombre Comercial</label>
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
                    <label for="full_name" class="col-md-4 controle-label">Nombre Completo / Razón social</label>
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
                    <label for="email" class="col-md-4 controle-label">E-mail</label>
                    <div class="col-md-4">
                        <input
                            id="field-email"
                            name="email"
                            class="form-control"
                            v-bind:class="{ 'is-invalid': ! validation.email_is_unique }"
                            placeholder="Correo electrónico"
                            required
                            title="Correo electrónico"
                            v-model="form_values.email"
                            v-on:change="validate_form"
                            >
                        <span class="invalid-feedback">
                            El correo electrónico escrito ya fue registrado para otra institución
                        </span>
                    </div>
                </div>

                <div class="form-group row" id="form-group_id_number">
                    <label for="id_number" class="col-md-4 controle-label">NIT o Documento</label>
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
                            v-on:change="validate_form"
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
                    <label for="city_id" class="col-md-4 controle-label">Ciudad ubicación</label>
                    <div class="col-md-8">
                        <?php echo form_dropdown('city_id', $options_city, '0909', 'id="field-city_id" class="form-control select2" required') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="celular" class="col-md-4 controle-label">Teléfono / Celular</label>
                    <div class="col-md-8">
                        <input
                            id="field-phone_number"
                            name="phone_number"
                            class="form-control"
                            placeholder="Número celular"
                            title="Número celular"
                            required
                            v-model="form_values.phone_number"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-md-4 col-md-8">
                        <button class="btn btn-info" type="submit" style="width: 120px;">
                            Crear
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    var form_values = {
            name: 'Jardín Ipiales Feliz',
            full_name: 'Jardín Ipiales Feliz SAS',
            email: 'jardinipiales@gmail.com',
            id_number: '',
            id_number_type: '011',
            city_id: '0<?php echo $row->city_id ?>',
            phone_number: '<?php echo $row->phone_number ?>'            
        };
    new Vue({
    el: '#app_insert',
        data: {
            form_values: form_values,
            row_id: '<?php echo $row->id ?>',
            validation: {
                email_is_unique: true,
                id_number_is_unique: true
            }
        },
        methods: {
            validate_form: function() {
                axios.post(app_url + 'institutions/validate_form/', $('#add_form').serialize())
                .then(response => {
                    //this.formulario_valido = response.data.status;
                    this.validation = response.data.validation;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validate_send: function () {
                axios.post(app_url + 'institutions/validate_form/', $('#add_form').serialize())
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
                axios.post(app_url + 'institutions/insert/', $('#add_form').serialize())
                    .then(response => {
                        console.log('status: ' + response.data.mensaje);
                        if (response.data.status == 1)
                        {
                            toastr['success']('La institución fue creada con éxito');
                            setTimeout(() => {
                                window.location = app_url + 'institutions/info/' + response.data.institution_id;
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