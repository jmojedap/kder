<div id="app_calendars">

    <table class="table bg-white">
        <thead>
            <th>Calendario</th>
            <th width="80px"></th>
        </thead>
        <tbody>
            <tr v-for="(calendar, key) in list">
                <td>{{ calendar.post_name }}</td>
                <td>
                    <button class="btn btn-ligth btn-sm" v-on:click="set_current(key)" data-toggle="modal" data-target="#form_modal">
                        <i class="fa fa-pencil-alt"></i>
                    </button>
                    <button class="btn btn-warning btn-sm" v-on:click="set_current(key)" data-toggle="modal" data-target="#delete_modal">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>

    <button type="button" class="btn btn-floating btn-success float-right" data-toggle="modal" data-target="#form_modal" v-on:click="clean_form">
        <i class="icon wb-plus" aria-hidden="true"></i>
    </button>

    <!-- Modal Form -->
    <div class="modal fade" id="form_modal" tabindex="-1" role="dialog" aria-labelledby="form_modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form accept-charset="utf-8" method="POST" id="calendar_form" @submit.prevent="send_form">
                    <input type="hidden" name="type_id" value="4021">
                    <input type="hidden" name="related_1" value="<?= $row->id ?>">
                    <div class="modal-header">
                        <h5 class="modal-title" id="form_modalTitle">Calendario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="post_name" class="col-md-4 col-form-label">Nombre</label>
                            <div class="col-md-8">
                                <input
                                    type="text"
                                    id="field-post_name"
                                    name="post_name"
                                    required
                                    class="form-control"
                                    placeholder="Ej. Calendario 2020"
                                    title=""
                                    v-bind:value="form_values.post_name"
                                    >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="date_1" class="col-md-4 col-form-label">Fecha inicial</label>
                            <div class="col-md-8">
                                <input
                                    type="date"
                                    id="field-date_1"
                                    name="date_1"
                                    required
                                    class="form-control"
                                    v-bind:value="form_values.date_1"
                                    >
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary w120p">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php $this->load->view('common/modal_single_delete_v') ?>
</div>

<script>
var form_values = {
    id: '0',
    post_name: '',
    date_1: '2020-01-01'
}

new Vue({
    el: '#app_calendars',
    created: function() {
        this.get_list();
    },
    data: {
        institution_id: '<?= $row->id ?>',
        calendar_id: 0,
        list: [],
        form_values: form_values
    },
    methods: {
        get_list: function() {
            axios.get(url_app + 'institutions/get_calendars/' + this.institution_id)
            .then(response => {
                this.list = response.data.list;
            })
            .catch(function(error) {
                console.log(error);
            });
        },
        send_form: function(){
            axios.post(url_app + 'posts/save/' + this.calendar_id, $('#calendar_form').serialize())
            .then(response => {
                type = 'warning';
                if (response.data.status) {
                    this.get_list();
                    type = 'success';
                    $('#form_modal').modal('hide');
                }
                toastr[type](response.data.message);
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        set_current: function(key){
            this.form_values = this.list[key];
            this.calendar_id = this.form_values.id;
            console.log(this.calendar_id);
        },
        delete_element: function(){
            axios.get(url_app + 'posts/delete/' + this.calendar_id)
            .then(response => {
                if ( response.data.status == 1 ) {
                    toastr['info']('El Calendario fue eliminado');
                    this.get_list();
                }
            })
            .catch(function (error) {
                console.log(error);
            });  
        },
        clean_form: function(){
            this.calendar_id = 0;
            this.form_values = form_values;  
        },
    }
});
</script>