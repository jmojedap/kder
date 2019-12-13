<?php
    //Clases columnas
        $cl_col['id'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['charge'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['student'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['total_value'] = 'd-none d-md-table-cell d-lg-table-cell text-right';
        $cl_col['payed_value'] = 'd-none d-md-table-cell d-lg-table-cell text-right';
        $cl_col['status'] = 'd-none d-md-table-cell d-lg-table-cell';
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
        
        <th>Cobro</th>
        <th class="<?php echo $cl_col['student'] ?>">Esudiante</th>
        
        <th class="<?php echo $cl_col['total_value'] ?>">Valor</th>
        <th class="<?php echo $cl_col['payed_value'] ?>">Pagado</th>
        <th class="<?php echo $cl_col['status'] ?>">Estado</th>

        <th width="35px"></th>
    </thead>

    <tbody>
        <?php foreach ($elements->result() as $row_element){ ?>
            <?php
                $student_name = $this->App_model->name_user($row_element->student_id);
                $cl_status = ( $row_element->payment_status == 1 ) ? 'table-success' : 'table-warning';
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
                    <a href="#" onclick="load_cf('payments/info/<?php echo $row_element->id ?>')">
                        <?php echo $row_element->title ?>
                    </a>
                </td>

                <td class="<?php echo $cl_col['student'] ?>">
                    <a href="<?php echo base_url("users/profile/{$row_element->student_id}") ?>" class="clase">
                        <?php echo $student_name ?>
                    </a>
                </td>

                <td class="<?php echo $cl_col['total_value'] ?>">
                    <?php echo $this->pml->money($row_element->total_value); ?>
                </td>
                <td class="<?php echo $cl_col['payed_value'] ?>">
                    <?php echo $this->pml->money($row_element->payed_value); ?>
                </td>
                <td class="<?php echo $cl_col['status'] ?> <?php echo $cl_status ?>">
                    <?php echo $arr_status[$row_element->payment_status]; ?>
                </td>

                <td>
                    <button class="btn btn-light btn-sm btn_more" data-row_id="<?php echo $row_element->id ?>">
                        <i class="fa fa-info-circle"></i>
                    </button>
                </td>
            </tr>
            <tr class="collapse more" id="more_<?php echo $row_element->id ?>">
                <td colspan="7">
                    aquí más información del pago
                </td>
            </tr>
        <?php } //foreach ?>
    </tbody>
</table>  