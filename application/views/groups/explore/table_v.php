<?php
    //Clases columnas
        $cl_col['id'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['title'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['level'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['qty_students'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['teacher'] = 'd-none d-md-table-cell d-lg-table-cell';
?>

<div class="table-responsive">
    <table class="table table-hover bg-white">
        <thead>
            <th width="10px"><input type="checkbox" @change="select_all" v-model="all_selected"></th>
            <th class="w100p"></th>
            <th class="<?= $cl_col['level'] ?>">Nivel</th>
            <th class="<?= $cl_col['teacher'] ?>">
                Profesor
            </th>
            <th class="<?= $cl_col['qty_students'] ?>">
                Estudiantes
            </th>
            
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td><input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id"></td>
                
                <td class="<?= $cl_col['title'] ?>">
                    <a v-bind:href="`<?= base_url("groups/students/") ?>` + element.id" class="btn btn-primary w100p">
                        {{ element.name }}
                    </a>
                </td>

                <td class="<?= $cl_col['level'] ?>">
                    {{ element.level | level_name }}
                </td>

                <td class="<?= $cl_col['teacher'] ?>">
                    <a v-bind:href="`<?= base_url("users/profile/") ?>` + element.teacher_id" class="">
                        {{ element.teacher_name }}
                    </a>
                </td>

                <td class="<?= $cl_col['qty_students'] ?>">
                    {{ element.qty_students }}
                    <i class="fa fa-exclamation-triangle text-warning" v-show="element.qty_students <= 0"></i>
                </td>
                
                <td>
                    <button class="a4" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>