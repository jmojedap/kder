<div id="app_students">
    <div class="mb-2">
        <button class="btn btn-primary" v-show="!show_form" v-on:click="toggle_show_form">
            <i class="fa fa-plus"></i> Estudiante
        </button>
        <button class="btn btn-warning" v-show="show_form" v-on:click="toggle_show_form">
            <i class="fa fa-arrow-left"></i> Listado
        </button>
    </div>
    <table class="table bg-white" v-show="!show_form">
        <thead>
            <th width="50px"></th>
            <th>Estudiante</th>
            <th></th>
            <th></th>
        </thead>
        <tbody>
            <tr v-for="(student, key) in list">
                <td>
                    <a v-bind:href="`<?php echo base_url('users/profile/') ?>` + student.id">
                        <img
                            class="rounded"
                            v-bind:src="`<?php echo URL_UPLOADS ?>` + student.src_thumbnail"
                            alt="Imagen estudiante"
                            style="width: 60px;"
                            onerror="this.src='<?php echo URL_IMG ?>users/sm_user.png'"
                            >
                    </a>
                </td>
                <td>
                    <a v-bind:href="`<?php echo base_url('users/profile/') ?>` + student.id">
                        {{ student.display_name }}
                    </a>
                    <br/>
                    <span class="text-muted">{{ student.username }}</span>
                </td>
                <td>
                    {{ student.username }}
                </td>
                <td width="35px">
                    <button class="btn btn-light btn-sm" v-on:click="set_current(key)" data-toggle="modal" data-target="#remove_modal">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="card center_box_750" v-show="show_form">
        <div class="card-body">
            <?php $this->load->view('groups/students/form_add_v') ?>
        </div>
    </div>

    <?php $this->load->view('groups/students/modal_remove_v') ?>

</div>

<?php $this->load->view('groups/students/vue_v') ?>