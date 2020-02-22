<?php
    //Clases columnas
        $cl_col['id'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['title'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['type'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['excerpt'] = 'd-none d-md-table-cell d-lg-table-cell';
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
            <th>Nombre</th>
            <th class="<?php echo $cl_col['type'] ?>">Tipo</th>
            <th class="<?php echo $cl_col['excerpt'] ?>">Resumen</th>
            
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
                
                <td class="<?php echo $cl_col['title'] ?>">
                    <a v-bind:href="`<?php echo base_url("posts/info/") ?>` + element.id">
                        {{ element.post_name }}
                    </a>
                </td>
                <td class="<?php echo $cl_col['type'] ?>">
                    {{ element.type_id | type_name  }}
                </td>
                <td class="<?php echo $cl_col['excerpt'] ?>">
                    {{ element.excerpt }}
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