<div id="app_students">
    <table class="table bg-white">
        <thead>
            <th width="50px"></th>
            <th>Estudiante</th>
        </thead>
        <tbody>
            <tr v-for="student in list" :key="student.id">
                <td>
                    <img class="rounded" v-bind:src="`<?php echo URL_UPLOADS ?>` + student.src_thumbnail" alt="Imagen estudiante" style="width: 60px;">
                </td>
                <td>
                    <a v-bind:href="`<?php echo base_url('users/profile/') ?>` + student.id">
                        {{ student.display_name }}
                    </a>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="card center_box_750">
        <div class="card-body">
            <?php $this->load->view('groups/students/form_add_v') ?>
        </div>
    </div>

</div>

<?php $this->load->view('groups/students/vue_v') ?>