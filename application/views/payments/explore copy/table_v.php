<?php
    //Clases columnas
        $cl_col['id'] = '';
        $cl_col['info'] = '';
        $cl_col['charge'] = '';
        $cl_col['student'] = '';
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
        <th width="50px"  class="<?php echo $cl_col['id'] ?>">ID</th>
        <th width="35px"></th>

        <th class="<?php echo $cl_col['student'] ?>">Esudiante</th>
        <th>Cobro</th>
        <th class="<?php echo $cl_col['status'] ?>">Estado</th>
        
        <th class="<?php echo $cl_col['total_value'] ?>">Valor</th>
        <th class="<?php echo $cl_col['payed_value'] ?>">Pagado</th>
        
        <th width="35px"></th>
    </thead>

    <tbody>
        <?php foreach ($elements->result() as $row_element){ ?>
            <?php
                $student_name = $this->App_model->name_user($row_element->student_id);
                
                $cl_payed_value = ( $row_element->payed_value >= $row_element->total_value ) ? 'table-success' : 'table-warning';

                //Status
                $btn_status['class'] = 'btn-secondary';
                $btn_status['text'] = 'Sin pagar';
                if ( $row_element->payment_status == 1 )
                {
                    $btn_status['class'] = 'btn-success';
                    $btn_status['text'] = 'Pagado';
                }
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

                <td class="<?php echo $cl_col['info'] ?>">
                    <a href="<?php echo base_url("payments/info/{$row_element->id}") ?>" class="btn btn-light" title="Ir al pago">
                        <i class="fa fa-arrow-right"></i>
                    </a>
                </td>
                
                <td class="<?php echo $cl_col['student'] ?>">
                    <a href="<?php echo base_url("users/profile/{$row_element->student_id}") ?>" class="clase">
                        <?php echo $student_name ?>
                    </a>
                </td>

                <td class="<?php echo $cl_col['charge'] ?>">
                    <a href="#" onclick="load_cf('charges/info/<?php echo $row_element->charge_id ?>')">
                        <?php echo $row_element->title ?>
                    </a>
                </td>

                <td class="<?php echo $cl_col['status'] ?>">
                    <div class="dropdown">
                        <a class="btn dropdown-toggle w100p <?php echo $btn_status['class'] ?>" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo $btn_status['text'] ?>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a
                                class="dropdown-item btn_set_payed"
                                href="#"
                                data-charge_id="<?php echo $row_element->charge_id ?>"
                                data-payment_id="<?php echo $row_element->id ?>"
                                data-payment_status="1"
                                >
                                Pagado
                            </a>
                            <a
                                class="dropdown-item btn_set_payed"
                                href="#"
                                data-charge_id="<?php echo $row_element->charge_id ?>"
                                data-payment_id="<?php echo $row_element->id ?>"
                                data-payment_status="0"
                                >
                                Sin pagar
                            </a>
                        </div>
                    </div>
                </td>

                <td class="<?php echo $cl_col['total_value'] ?>">
                    <?php echo $this->pml->money($row_element->total_value); ?>
                </td>
                <td class="<?php echo $cl_col['payed_value'] ?> <?php echo $cl_payed_value ?>">
                    <?php echo $this->pml->money($row_element->payed_value); ?>
                </td>

                <td>
                    <button class="btn btn-light btn-sm btn_more" data-row_id="<?php echo $row_element->id ?>">
                        <i class="fa fa-info-circle"></i>
                    </button>
                </td>
            </tr>
            <tr class="collapse more" id="more_<?php echo $row_element->id ?>">
                <td colspan="8">
                    <span class="text-info">Pagado</span>
                    <span class="text-muted"><?php echo $this->pml->date_format($row_element->payed_at) ?></span>
                    &middot;
                    <span class="text-info">Notas</span>
                    <span class="text-muted"><?php echo $row_element->notes ?></span>
                    &middot;
                    <span class="text-info">Estadoo</span>
                    <span class="text-muted"><?php echo $arr_status[$row_element->payment_status] ?></span>
                </td>
            </tr>
        <?php } //foreach ?>
    </tbody>
</table>  