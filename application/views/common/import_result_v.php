<?php
    $status_class = 'table-danger';
    if ( $status == 1 ) { $status_class = 'table-success'; }
    
    $str_not_imported = implode(', ', $not_imported);
    
    $class_not_imported = '';
    if ( count($not_imported) > 0 ) { $class_not_imported = 'table-warning'; }
    
    $quan_imported = count($arr_sheet) - count($not_imported);
?>

<table class="table table-default bg-white">
    <tbody>
        <tr class="<?php echo $status_class ?>">
            <td width="25%">Resultado</td>
            <td width="20px">
                <?php if ( $status == 1 ) { ?>
                    <i class="fa fa-check"></i>
                <?php } else { ?>
                    <i class="fa fa-times"></i>
                <?php } ?>
                
            </td>
            <td><?php echo $message ?></td>
        </tr>
        <tr>
            <td>Nombre hoja cálculo</td>
            <td></td>
            <td><?php echo $sheet_name ?></td>
        </tr>
        <tr>
            <td>Filas encontradas</td>
            <td></td>
            <td><?php echo count($arr_sheet) ?></td>
        </tr>
        <tr>
            <td>Filas importadas</td>
            <td></td>
            <td><?php echo $quan_imported ?></td>
        </tr>
        <tr class="<?php echo $class_not_imported ?>">
            <td>Filas no importadas</td>
            <td></td>
            <td>
                <?php echo count($not_imported) ?> 
                <?php if ( count($not_imported) > 0 ){ ?>
                    <button style="margin-left: 10px;" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#str_not_imported" aria-expanded="false" aria-controls="str_not_imported">
                        Ver detalle
                        <i class="fa fa-ellipsis"></i>
                    </button>
                <?php } ?>
            </td>
        </tr>
        <tr class="collapse table-warning" id="str_not_imported">
            <td>Números de las filas no importadas</td>
            <td></td>
            <td>
                <?php echo $str_not_imported ?>
            </td>
        </tr>
    </tbody>
</table>

<a href="<?php echo base_url($back_destination) ?>" class="btn btn-secondary">
    <i class="fa fa-arrow-circle-left"></i>
    Volver
</a>



