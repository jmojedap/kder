<?php $this->load->view('assets/momentjs') ?>

<div id="groups_app">
    <div class="row">
        <div class="col-md-4">
            <table class="table bg-white" v-show="!show_detail">
                <thead>
                    <th>Grupo</th>
                    <th width="30px">Cobro aplicado</th>
                    <th width="30px"></th>
                </thead>
                <tbody>
                    <tr v-for="(group, key) in list" v-bind:class="{'table-primary': group_id == group.id}">
                        <td>
                            <a href="#" v-bind:onclick="`load_cf('groups/students/` + group.id + `')`">
                                {{ group.title }}
                            </a>
                        </td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle w100p"
                                    href="#"
                                    role="button"
                                    id="dropdownMenuLink"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    v-bind:class="{'btn-success': group.meta_id > 0}"
                                    >
                                    <span v-show="group.meta_id > 0">Sí</span>
                                    <span v-show="group.meta_id == 0">No</span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="#" v-on:click="set_group(key)" v-show="group.meta_id == 0">Aplicar cobro</a>
                                    <a class="dropdown-item" href="#" v-on:click="set_current(key)" v-show="group.meta_id > 0"
                                        data-toggle="modal" data-target="#modal_unset_group">
                                        Retirar cobro
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-primary" v-show="group.meta_id > 0" v-on:click="show_students(key)">
                                <i class="fas fa-users"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-8">
            <div v-show="students.length > 0">
                <h3>{{ group.title }}</h3>
                <table class="table bg-white">
                    <thead>
                        <th>Estudiante</th>
                        <th>Pagado</th>
                        <th>Actualizado</th>
                    </thead>
                    <tbody>
                        <tr v-for="(student, key_student) in students">
                            <td>{{ student.display_name }}</td>
                            <td>
                                <div class="dropdown">
                                    <button
                                        class="btn btn-secondary dropdown-toggle w100p"
                                        type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                        v-bind:class="{'btn-success': student.payment_status == 1}"
                                        >
                                        {{ student.payment_status | status_format }}
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#" v-on:click="set_payed(key_student,1)" v-show="student.payment_status == 0">Sí</a>
                                        <a class="dropdown-item" href="#" v-on:click="set_payed(key_student,0)" v-show="student.payment_status > 0">No</a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ student.edited_at | date_format }}
                                <small>{{ student.edited_at | ago }}</small>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Unset Group -->
    <div class="modal fade" id="modal_unset_group" tabindex="-1" role="dialog" aria-labelledby="modal_unset_group" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Retirar cobro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Al retirar el cobro se eliminarán los datos existentes de pagos
                de estudiantes de este grupo.
                <br>
                ¿Desea continuar?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" v-on:click="unset_group">Continuar</button>
            </div>
            </div>
        </div>
    </div>
    
</div>

<?php $this->load->view('charges/groups/vue_v') ?>