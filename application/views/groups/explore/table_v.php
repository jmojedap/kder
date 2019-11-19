<?php
    //Clases columnas
        $cl_col['id'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['title'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['level'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['qty_students'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['teacher'] = 'd-none d-md-table-cell d-lg-table-cell';
?>

<table class="table bg-white" cellspacing="0">

    <thead>
        <th width="10px">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="check_all" name="check_all">
                <label class="custom-control-label" for="check_all">
                    <span class="text-hide">-</span>
                </label>
            </div>
        </th>
        <th width="50px;"  class="<?php echo $cl_col['id'] ?>">ID</th>
        
        <th class="w100p"></th>
        
        <th class="<?php echo $cl_col['title'] ?>">Título</th>
        <th class="<?php echo $cl_col['level'] ?>">Nivel</th>
        <th class="<?php echo $cl_col['qty_students'] ?>">Estudiantes</th>
        <th class="<?php echo $cl_col['teacher'] ?>">Asignado a</th>

        <th width="35px"></th>
    </thead>

    <tbody>
        <?php foreach ($elements->result() as $row_element){ ?>
            <?php
                $qty_students = $this->Db_model->num_rows('group_user', "group_id = {$row_element->id}");
                $teacher_name = $this->App_model->name_user($row_element->teacher_id);
            ?>
            <tr id="row_<?php echo $row_element->id ?>">
                <td>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input check_row" data-id="<?php echo $row_element->id ?>" id="check_<?php echo $row_element->id ?>">
                        <label class="custom-control-label" for="check_<?php echo $row_element->id ?>">
                            <span class="text-hide">-</span>
                        </label>
                    </div>
                </td>
                
                <td class="<?php echo $cl_col['id'] ?>"><?php echo $row_element->id ?></td>
                
                <td>
                    <a href="#" onclick="load_cf('groups/students/<?php echo $row_element->id ?>')" class="btn btn-primary w100p">
                        <?php echo $row_element->name ?>
                    </a>
                </td>

                <td class="<?php echo $cl_col['title'] ?>">
                    <?php echo $row_element->title ?>
                </td>

                <td class="<?php echo $cl_col['level'] ?>">
                    <?php echo $arr_levels[$row_element->level] ?>
                </td>

                <td class="<?php echo $cl_col['qty_students'] ?>">
                    <?php echo $qty_students ?>
                </td>

                <td class="<?php echo $cl_col['teacher'] ?>">
                    <?php echo $teacher_name ?>
                </td>

                <td>
                    <button class="btn btn-light btn-sm btn_more" data-row_id="<?php echo $row_element->id ?>">
                        <i class="fa fa-info-circle"></i>
                    </button>
                </td>
            </tr>
            <tr class="collapse more" id="more_<?php echo $row_element->id ?>">
                <td colspan="7">
                    aquí más información del grupo
                </td>
            </tr>
        <?php } //foreach ?>
    </tbody>
</table>  