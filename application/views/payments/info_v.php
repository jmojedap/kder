<?php
    $status['icon'] = 'fa fa-clock';
    $status['class'] = 'text-warning';
    if ( $row->status == 1 )
    {
        $status['icon'] = 'fa fa-check';
        $status['class'] = 'text-success';
    }
?>

<table class="table bg-white">
    <tbody>
        <tr>
            <td width="35%"><span class="text-muted">ID Pago</span></td>
            <td width="65%">
                <?php echo $row->id ?>
            </td>
        </tr>

        <tr>
            <td>Estado</td>
            <td class="<?php echo $status['class'] ?>">
                <i class="<?php echo $status['icon'] ?>"></i>
                <?php echo $this->Item_model->name(174, $row->status); ?>
            </td>
        </tr>

        <tr>
            <td>Valor pagado</td>
            <td class="<?php echo $status['class'] ?>">
                <?php echo $this->pml->money($row->payed_value); ?>
            </td>
        </tr>

        <tr>
            <td class=""><span class="text-muted">Estudiante</span></td>
            <td>
                <a href="<?php echo base_url("users/profile/{$row->student_id}") ?>">
                    <?php echo $this->App_model->name_user($row->student_id); ?>
                </a>
            </td>
        </tr>

        <tr>
            <td class=""><span class="text-muted">Pagado por</span></td>
            <td><?php echo $this->App_model->name_user($row->payer_id); ?></td>
        </tr>

        

        <tr>
            <td class=""><span class="text-muted">Pagado en</span></td>
            <td>
                <?php echo $this->pml->date_format($row->payed_at, 'Y-m-d h:i') ?>
            </td>
        </tr>

        <tr>
            <td>Notas</td>
            <td><?php echo $row->notes; ?></td>
        </tr>

        <tr>
            <td class=""><span class="text-muted">Editado</span></td>
            <td>
                <?php echo $this->pml->date_format($row->edited_at, 'Y-m-d h:i') ?> por 
                <?php echo $this->App_model->name_user($row->editor_id, 'du') ?>
            </td>
        </tr>
        <tr>
            <td class=""><span class="text-muted">Creado</span></td>
            <td>
                <?php echo $this->pml->date_format($row->created_at, 'Y-m-d H:i') ?> por <?php echo $this->App_model->name_user($row->creator_id, 'du') ?>
            </td>
        </tr>
    </tbody>
</table>

<table class="table bg-white">
    <tbody>
        <tr>
            <td width="35%"><span class="text-muted">ID Cobro</span></td>
            <td width="65%">
                <?php echo $row->charge_id ?>
            </td>
        </tr>
        <tr>
            <td>Cobro</td>
            <td>
                <a href="<?php echo base_url("charges/info/{$row->charge_id}") ?>">
                    <?php echo $row_charge->title ?>
                </a>
            </td>
        </tr>
        <tr>
            <td>Valor</td>
            <td><?php echo $this->pml->money($row_charge->charge_value); ?></td>
        </tr>
        <tr>
            <td>Descripción</td>
            <td><?php echo $row_charge->excerpt ?></td>
        </tr>
    </tbody>
</table>