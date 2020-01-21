<?php
    $cl_col['title'] = 'd-none d-md-table-cell d-lg-table-cell';
    $cl_col['status'] = 'd-none d-md-table-cell d-lg-table-cell';
    $cl_col['image'] = 'd-none d-md-table-cell d-lg-table-cell';
    $cl_col['role'] = 'd-none d-md-table-cell d-lg-table-cell';
    $cl_col['email'] = 'd-none d-md-table-cell d-lg-table-cell';
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
            <th class="<?php echo $cl_col['status'] ?>">Estado</th>
            <th class="<?php echo $cl_col['role'] ?>">Rol</th>
            <th class="<?php echo $cl_col['role'] ?>">Correo</th>
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
                    &middot;
                    <span class="text-muted">
                        {{ element.username }}
                    </span>
                </td>
                <td class="<?php echo $cl_col['status'] ?>" v-html="$options.filters.status_icon(element.status)">
                    
                </td>
                <td class="<?php echo $cl_col['role'] ?>">{{ element.role | role_name }}</td>
                <td class="<?php echo $cl_col['email'] ?>">{{ element.email }}</td>
                <td>
                    <button class="btn btn-light btn-sm w27p" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>