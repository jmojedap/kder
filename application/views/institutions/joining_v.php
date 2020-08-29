<?php
    $options_role = $this->Item_model->options('category_id = 58 AND cod IN (13,15,21)', 'Tipo de usuario');

    //Verificar que el usuario haya completado sus datos
    $with_data = TRUE;
    if ( strlen($row_user->id_number) == 0 ) { $with_data = FALSE; }
    if ( $row_user->id_number_type == 0 ) { $with_data = FALSE; }
    if ( $row_user->city_id == 0 ) { $with_data = FALSE; }
    if ( strlen($row_user->birth_date) == 0 ) { $with_data = FALSE; }
    if ( $row_user->birth_date == '0000-00-00' ) { $with_data = FALSE; }
    if ( $row_user->gender == 0 ) { $with_data = FALSE; }
?>

<div id="joining_app">
    <div class="center_box_750">
        <div class="card mb-2" v-show="sended == 0">
            <div class="card-body text-center">
                <h3 class="card-title">Ya estás en DeKinder</h3>
                <p>
                    Ahora selecciona la opción para vincularte a una institución o jardín.
                </p>
                <?php if ( $with_data ) { ?>
                <p>
                    <a href="<?= base_url("institutions/my_institutions") ?>" class="btn btn-success btn-lg">
                        <i class="fa fa-plus"></i>
                        Crear institución
                    </a>
                    <button class="btn btn-primary btn-lg" v-on:click="set_type(2)">
                        <i class="fa fa-school"></i>
                        Institución existente
                    </button>
                </p>
                <?php } else { ?>
                <p>
                    <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#complete_profile">
                        <i class="fa fa-plus"></i>
                        Crear institución
                    </button>
                    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#complete_profile">
                        <i class="fa fa-school"></i>
                        Institución existente
                    </button>

                </p>
                <?php } ?>
            </div>
        </div>

        <div class="card" v-show="type == 2">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="joining_form" @submit.prevent="send_form">
                    <div class="form-group row">
                        <label for="institution_id" class="col-md-4 col-form-label text-right">Institución</label>
                        <div class="col-md-8">
                            <input type="text" id="field-term" name="term" required class="form-control"
                                placeholder="Buscar institución..." title="Escriba el nombre de una institución"
                                v-model="term" v-on:change="search_institutions">
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <table class="table mt-2 bg-white" v-show="institution_id == 0 && search_num_rows > 0">
            <thead>
                <th>Institución</th>
                <th>Ubicación</th>
                <th width="35px"></th>
            </thead>
            <tbody>
                <tr v-for="(institution, key) in list">
                    <td>{{ institution.name }}</td>
                    <td>
                        {{ institution.address }}
                        <br>
                        {{ institution.place_name }}
                    </td>
                    <td>
                        <button class="btn btn-primary" v-on:click="set_institution(key)">
                            Vincularme
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="alert alert-info" v-show="search_num_rows == 0">
            No se encontraron coincidencias para '{{ term }}'
        </div>

        <div class="card" v-show="institution_id > 0">
            <div class="card-body">
                <h3 class="card-title text-center">{{ current_institution.name }}</h3>
                <p class="text-center">
                    {{ current_institution.address }} -
                    {{ current_institution.place_name }}
                </p>

                <div class="form-group row">
                    <label for="role" class="col-md-4 col-form-label text-right">Vincular como:</label>
                    <div class="col-md-8">
                        <?php //echo form_dropdown('role', $options_role, '', 'class="form-control" v-model="role"') ?>
                        <select name="role" class="form-control" v-model="role">
                            <option value="">[ Tipo de usuario ]</option>
                            <option v-for="option_role in roles" v-bind:value="option_role.id">{{ option_role.title }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="row" v-show="role.length">
                    <div class="col-md-8 offset-md-4">
                        <button class="btn btn-success w120p" v-on:click="join">
                            Solicitar
                        </button>
                        <button class="btn btn-secondary w120p" v-on:click="cancel_join">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" v-show="sended == 1">
            <div class="card-body">
                <h3 class="card-title">
                    <i class="fa fa-check text-success"></i>
                    Solicitud enviada
                </h3>
                <p>
                    La institución revisará su solicitud. Cuando responda usted recibirá una notificación
                    al correo electrónico con el que se registró.
                </p>
            </div>
        </div>
    </div>

    <!-- Modal Ir a Perfil -->
    <div class="modal fade" id="complete_profile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <i class="fa fa-user"></i>
                        Completa tu perfil
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-center">
                        Antes de crear o vincularte a una institución 
                        por favor completa los datos de tu perfil de usuario.
                    </p>
                </div>
                <div class="modal-footer">
                    <a href="<?= base_url("accounts/edit") ?>" class="btn btn-primary">
                        Ir a perfil
                    </a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
new Vue({
    el: '#joining_app',
    data: {
        roles: [{
                'id': '013',
                'title': 'Administrativo'
            },
            {
                'id': '015',
                'title': 'Profesor'
            },
            {
                'id': '021',
                'title': 'Padre/Madre de Familia'
            }
        ],
        role: '',
        type: 0,
        term: '',
        user_id: '<?= $this->session->userdata('
        user_id ') ?>',
        institution_id: 0,
        current_institution: [],
        list: [],
        search_num_rows: -1,
        sended: 0
    },
    methods: {
        set_type: function(type) {
            this.type = type;
        },
        send_form: function() {
            console.log('enviando form');
        },
        search_institutions: function() {
            var params = new FormData();
            params.append('q', this.term);

            axios.post(url_app + 'institutions/get/', params)
                .then(response => {
                    this.list = response.data.list;
                    this.search_num_rows = response.data.search_num_rows;
                    this.cancel_join();
                    console.log(response.data);
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        set_institution: function(key) {
            this.current_institution = this.list[key];
            this.institution_id = this.current_institution.id;
        },
        join: function() {
            var args = this.institution_id + '/' + this.user_id + '/' + this.role;
            axios.get(url_app + 'institutions/require_join/' + args)
                .then(response => {
                    if (response.data.status == 1) {
                        this.set_sended();
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        cancel_join: function() {
            this.institution_id = 0;
            this.current_institution = [];
        },
        set_sended: function() {
            window.location = url_app + 'institutions/joining';
        }
    }
});
</script>