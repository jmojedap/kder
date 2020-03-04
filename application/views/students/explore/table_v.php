<?php
    $cl_col['image'] = '';
    $cl_col['title'] = '';
    $cl_col['group'] = 'd-none d-md-table-cell d-lg-table-cell';
    $cl_col['numbers'] = 'd-none d-md-table-cell d-lg-table-cell';
?>

<div class="table-responsive">
    <table class="table table-hover bg-white">
        <thead>
            <th width="46px">
                <div class="checkbox-custom checkbox-primary">
                    <input type="checkbox" @click="select_all" v-model="all_selected">
                    <label for="inputUnchecked"></label>
                </div>
            </th>
            <th class="<?php echo $cl_col['image'] ?>" width="50px"></th>
            <th class="<?php echo $cl_col['title'] ?>">Nombre</th>
            <th class="<?php echo $cl_col['group'] ?>">Grupo</th>
            <th class="<?php echo $cl_col['numbers'] ?>">Identificación</th>
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td>
                    <div class="checkbox-custom checkbox-primary">
                        <input type="checkbox" v-model="selected" v-bind:value="element.id">
                        <label for="inputUnchecked"></label>
                    </div>
                </td>
                <td class="<?php echo $cl_col['image'] ?>">
                    <a v-bind:href="`<?php echo base_url("users/profile/") ?>` + element.id" class="">
                        <img
                            v-bind:src="`<?php echo URL_UPLOADS ?>` + element.src_thumbnail"
                            class="rounded-circle"
                            v-bind:alt="element.id"
                            width="40px"
                            onerror="this.src='<?php echo URL_IMG ?>users/sm_user.png'"
                        >
                    </a>
                </td>
                <td class="<?php echo $cl_col['title'] ?>">
                    <a v-bind:href="`<?php echo base_url("users/profile/") ?>` + element.id + `/` + element.username">
                        {{ element.display_name }}
                    </a>
                </td>
                    
                </td>
                <td class="<?php echo $cl_col['group'] ?>">
                    <a v-bind:href="`<?php echo base_url("groups/students/") ?>` + element.group_id">
                        {{ element.group_title }}
                    </a>
                </td>

                <td class="<?php echo $cl_col['numbers'] ?>">
                    <span class="text-muted">No Documento</span>
                    <span class="text-info">{{ element.id_number }}</span>
                    &middot;
                    <span class="text-muted">Código</span>
                    <span class="text-info">{{ element.code }}</span>
                    <br>

                </td>

                <td>
                    <button class="btn btn-light btn-sm w27p" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>