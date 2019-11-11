<?php
    //Clases columnas
        $col_classes['type_id'] = 'd-none d-md-table-cell d-lg-table-cell';
        $col_classes['element_id'] = 'd-none d-md-table-cell d-lg-table-cell';
        $col_classes['user'] = 'd-none d-md-table-cell d-lg-table-cell';
?>

<table class="table bg-white" cellspacing="0">
    <thead>
            <tr class="">
                <th width="10px">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="check_all" name="check_all">
                        <label class="custom-control-label" for="check_all">
                            <span class="text-hide">-</span>
                        </label>
                    </div>
                </th>
                <th width="50px;">ID</th>
                <th>Inicio</th>
                <th>Hace</th>
                
                <th class="<?php echo $col_classes['type_id'] ?>">Tipo</th>
                <th class="<?php echo $col_classes['element_id'] ?>">Elemento</th>
                <th class="<?php echo $col_classes['user'] ?>">Usuario</th>
            </tr>
        </thead>
    <tbody>
        <?php foreach ($elements->result() as $row_element){ ?>

            <tr id="row_<?php echo $row_element->id ?>">
                <td>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input check_row" data-id="<?php echo $row_element->id ?>" id="check_<?php echo $row_element->id ?>">
                        <label class="custom-control-label" for="check_<?php echo $row_element->id ?>">
                            <span class="text-hide">-</span>
                        </label>
                    </div>
                </td>
                
                <td><?php echo $row_element->id ?></td>
                
                <td>
                    <?php echo $this->pml->date_format($row_element->start) ?>
                </td>

                <td>
                    <?php echo $this->pml->ago($row_element->start, FALSE);  ?>
                </td>
                
                <td class="<?php echo $col_classes['type_id'] ?>">
                    <?php echo $arr_types[$row_element->type_id] ?>
                </td>
                <td class="<?php echo $col_classes['element_id'] ?>">
                    <?php echo $row_element->element_id ?>
                </td>
                <td class="<?php echo $col_classes['user'] ?>">
                    <a href="<?php echo base_url("events/explore/1/?u={$row_element->user_id}") ?>">
                        <?php echo $row_element->display_name ?>
                    </a>
                </td>
            </tr>
        <?php } //foreach ?>
    </tbody>
</table>  