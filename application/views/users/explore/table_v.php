<?php
    //Clases columnas
        $cl_col['id'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['role'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['status'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['email'] = 'd-none d-md-table-cell d-lg-table-cell';

    //Status
        $arr_status[0] = '<i class="far fa-circle text-danger"></i>';
        $arr_status[1] = '<i class="fa fa-check-circle text-success"></i>';
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
        <th width="50px;"></th>
        <th>Nombre</th>
        
        <th class="<?php echo $cl_col['status'] ?>">Estado</th>
        <th class="<?php echo $cl_col['role'] ?>">Rol</th>
        <th class="<?php echo $cl_col['email'] ?>" style="min-width: 200px;">Correo electrónico</th>
        <th width="35px"></th>
    </thead>

    <tbody>
        <?php foreach ($elements->result() as $row_element){ ?>
            <?php
                $att_img = $this->App_model->att_img_user($row_element, 'sm_');
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
                    <a href="<?php echo base_url("users/profile/{$row_element->id}") ?>">
                        <img
                            alt="<?php echo $att_img['alt'] ?>"
                            src="<?php echo $att_img['src'] ?>"
                            onerror="<?php echo $att_img['onerror'] ?>"
                            width="50px"
                            class="rounded-circle"
                            >
                    </a>
                </td>
                
                <td>
                    <a href="#" onclick="load_cf('users/profile/<?php echo $row_element->id ?>')">
                        <?php echo $row_element->display_name ?>
                    </a>
                    &middot;
                    <span class="text-muted"><?php echo $row_element->username ?></span>
                    <br/>
                    <?php echo $arr_roles[$row_element->role] ?>
                </td>

                <td class="<?php echo $cl_col['status'] ?>">
                    <?php echo $arr_status[$row_element->status] ?>
                </td>
                
                <td class="<?php echo $cl_col['role'] ?>">
                    <?php echo $arr_roles[$row_element->role] ?>
                </td>

                <td class="<?php echo $cl_col['email'] ?>">
                    <?php echo $row_element->email ?>
                </td>
                <td>
                    <button class="btn btn-light btn-sm btn_more" data-row_id="<?php echo $row_element->id ?>">
                        <i class="fa fa-info-circle"></i>
                    </button>
                </td>
            </tr>
            <tr class="collapse more" id="more_<?php echo $row_element->id ?>">
                <td colspan="7">
                    aquí más información del usuario
                </td>
            </tr>
        <?php } //foreach ?>
    </tbody>
</table>  