<?php
    //Clases columnas
        $cl_col['id'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['email'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['city'] = 'd-none d-md-table-cell d-lg-table-cell';
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
        
        <th>Nombre</th>
        
        <th class="<?php echo $cl_col['city'] ?>">Ciudad</th>

        <th class="<?php echo $cl_col['email'] ?>" style="min-width: 200px;">Correo electrónico</th>
        <th width="35px"></th>
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
                
                <td class="<?php echo $cl_col['id'] ?>"><?php echo $row_element->id ?></td>
                
                <td>
                    <a href="#" onclick="load_cf('institutions/info/<?php echo $row_element->id ?>')">
                        <?php echo $row_element->name ?>
                    </a>
                </td>

                <td class="<?php echo $cl_col['city'] ?>">
                    <?php echo $this->App_model->place_name($row_element->city_id) ?>
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