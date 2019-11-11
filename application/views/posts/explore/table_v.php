<?php
    //Clases columnas
        $col_cl['status'] = 'd-none d-md-table-cell d-lg-table-cell';
        $col_cl['type'] = 'd-none d-md-table-cell d-lg-table-cell';
        $col_cl['description'] = 'd-none d-md-table-cell d-lg-table-cell';
        $col_cl['edited'] = 'd-none d-lg-table-cell d-xl-table-cell';
        $col_cl['editor'] = 'd-none d-lg-table-cell d-xl-table-cell';

?>

<table class="table bg-white" cellspacing="0">
    <thead>
            <tr class="">
                <th width="10px" class="">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="check_all" name="check_all">
                        <label class="custom-control-label" for="check_all">
                            <span class="text-hide">-</span>
                        </label>
                    </div>
                </th>
                <th width="50px;">ID</th>
                <th width="250px">Post</th>
                <th class="<?php echo $col_cl['type'] ?>">Tipo</th>
            </tr>
        </thead>
    <tbody>
        <?php foreach ($elements->result() as $row_element){ ?>
            <?php
                
            ?>

            <tr id="row_<?php echo $row_element->id ?>">
                <td class="">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input check_row" data-id="<?php echo $row_element->id ?>" id="check_<?php echo $row_element->id ?>">
                        <label class="custom-control-label" for="check_<?php echo $row_element->id ?>">
                            <span class="text-hide">-</span>
                        </label>
                    </div>
                </td>
                
                <td><?php echo $row_element->id ?></td>
                
                <td>
                    <a href="#" onclick="load_cf('posts/info/<?php echo $row_element->id ?>')">
                        <?php echo $row_element->post_name ?>
                    </a>
                </td>

                <td class="<?php echo $col_cl['type'] ?>">
                    <?php echo $arr_types[$row_element->type_id] ?>
                </td>
            </tr>
        <?php } //foreach ?>
    </tbody>
</table>  