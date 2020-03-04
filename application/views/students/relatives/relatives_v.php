<?php $this->load->view('assets/jquery_autocomplete') ?>

<script>
    var user_id = '<?php echo $row->id ?>';
    var relatives_source = '<?php echo base_url("students/relatives_autocomplete/{$row->id}/{$row->institution_id}") ?>';

    $( function() {
      
        $("#field-user").autocomplete({
            source: relatives_source,
            minLength: 3,
            select: function( event, ui ) { add_relative(ui.item);}
        });
        
        function add_relative(item){
            $.ajax({
                type: 'POST',
                url: app_url + 'students/add_relative/' + user_id + '/' + item.id,
                success: function(response){
                    window.location = app_url + 'students/relatives/' + user_id;
                }
            });
        }
    } );
</script>

<div id="app_relatives">
    <div class="mb-2">
        <button class="btn btn-primary" v-show="!show_form" v-on:click="toggle_show_form">
            <i class="fa fa-plus"></i> Familiar
        </button>
        <button class="btn btn-warning" v-show="show_form" v-on:click="toggle_show_form">
            <i class="fa fa-arrow-left"></i> Ver familiares
        </button>
    </div>
    <table class="table bg-white" v-show="!show_form">
        <thead>
            <th width="50px"></th>
            <th>Nombre</th>
            <th></th>
            <th width="35px"></th>
        </thead>
        <tbody>
            <tr v-for="(relative, key) in list">
                <td>
                    <a v-bind:href="`<?php echo base_url('users/profile/') ?>` + relative.id">
                        <img
                            class="rounded"
                            v-bind:src="`<?php echo URL_UPLOADS ?>` + relative.src_thumbnail"
                            alt="Imagen estudiante"
                            style="width: 60px;"
                            onerror="this.src='<?php echo URL_IMG ?>users/sm_user.png'"
                            >
                    </a>
                </td>
                <td>
                    <a v-bind:href="`<?php echo base_url('users/profile/') ?>` + relative.id">
                        {{ relative.display_name }}
                    </a>
                    <br/>
                    <span class="text-muted">{{ relative.username }}</span>
                </td>
                <td>
                    {{ relative.relation_type }}
                    <br>
                    <span class="text-muted">
                        <i class="fa fa-envelope"></i>
                        {{ relative.email }}
                    </span>
                    &middot; 
                    <span class="text-muted">
                        <i class="fa fa-mobile-alt"></i>
                    </span>
                    {{ relative.phone_number }}
                </td>
                <td>
                    <button class="btn btn-light btn-sm" v-on:click="set_current(key)" data-toggle="modal" data-target="#remove_modal">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="card center_box_750 mb-2" v-show="show_form">
        <div class="card-body">
            <h3 class="card-title">Usuario existente</h3>
            <input id="field-user" class="form-control mt-2" placeholder="Agregar usuario existente..."> 
        </div>
    </div>

    <div class="card center_box_750" v-show="show_form">
        <div class="card-body">
            <h3 class="card-title">Nuevo usuario</h3>
            <?php $this->load->view('students/relatives/form_add_v') ?>
        </div>
    </div>

    <?php $this->load->view('students/relatives/modal_remove_v') ?>

</div>

<?php $this->load->view('students/relatives/vue_v') ?>