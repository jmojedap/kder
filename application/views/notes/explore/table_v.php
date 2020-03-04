<?php
    $cl_col['thumbnail'] = '';
    $cl_col['client'] = '';
    $cl_col['type'] = 'd-none d-md-table-cell d-lg-table-cell';
    $cl_col['excerpt'] = 'd-none d-md-table-cell d-lg-table-cell';
    $cl_col['created'] = 'd-none d-md-table-cell d-lg-table-cell';
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
            <th class="<?php echo $cl_col['thumbnail'] ?>"></th>
            <th class="<?php echo $cl_col['client'] ?>">Nombre</th>
            <th class="<?php echo $cl_col['type'] ?>">Tipo</th>
            <th class="<?php echo $cl_col['excerpt'] ?>">Anotación</th>
            <th class="<?php echo $cl_col['created'] ?>">Creado por</th>
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
                <td class="<?php echo $cl_col['thumbnail'] ?>">
                    <a v-bind:href="`<?php echo base_url("users/notes/") ?>` + element.client_id">    
                        <img
                            v-bind:src="`<?php echo URL_UPLOADS ?>` + element.client_src_thumbnail"
                            class="rounded rounded-circle w50p"
                            alt="imagen cliente"
                            onerror="this.src='<?php echo URL_IMG ?>users/sm_user.png'"
                        >
                    </a>
                </td>
                <td class="<?php echo $cl_col['client'] ?>">
                    <a v-bind:href="`<?php echo base_url("users/notes/") ?>` + element.client_id">
                        {{ element.client_display_name }}
                    </a>
                </td>
                <td class="<?php echo $cl_col['type'] ?>">
                    {{ element.cat_1 | type_name }}
                </td>

                <td class="<?php echo $cl_col['excerpt'] ?>">
                    <a v-bind:href="`<?php echo base_url("notes/info/") ?>` + element.id">
                        {{ element.post_name }}
                    </a>
                    <br>
                    {{ element.excerpt }}
                </td>

                <td class="<?php echo $cl_col['created'] ?>">
                    {{ element.creator_display_name }}
                    <br>
                    <span class="text-muted">{{ element.created_at | ago }}</span>
                </td>
                
                <td>
                    <button class="btn btn-light btn-sm btn-sm-sqr" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>