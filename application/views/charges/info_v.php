<div class="row">
    <div class="col col-lg-4 col-md-12 col-sm-12">
        
    </div>
    <div class="col col-lg-8 col-md-12 col-sm-12">
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td width="35%"><span class="text-muted">ID Cobro</span></td>
                    <td width="65%">
                        <?php echo $row->id ?>
                    </td>
                </tr>

                <tr>
                    <td class=""><span class="text-muted">Tipo</span></td>
                    <td><?php echo $this->Item_model->name(172, $row->charge_type_id) ?></td>
                </tr>

                <tr>
                    <td class=""><span class="text-muted">Título</span></td>
                    <td><?php echo $row->title ?></td>
                </tr>

                <tr>
                    <td><span class="text-muted">Año generación</span></td>
                    <td><?php echo $row->generation ?></td>
                </tr>

                <tr>
                    <td><span class="text-muted">Valor</span></td>
                    <td><?php echo $this->pml->money($row->charge_value); ?></td>
                </tr>

                <tr>
                    <td><span class="text-muted">Fecha máxima de pago</span></td>
                    <td>
                        <?php echo $this->pml->date_format($row->date_2, 'Y-M-d') ?> &middot;
                        <?php echo $this->pml->ago($row->date_2); ?>
                    </td>
                </tr>

                <tr>
                    <td class=""><span class="text-muted">Descripción</span></td>
                    <td><?php echo $row->excerpt ?></td>
                </tr>

                <tr>
                    <td class=""><span class="text-muted">Editado</span></td>
                    <td>
                        <?php echo $this->pml->date_format($row->edited_at, 'Y-m-d h:i') ?> por <?php echo $this->App_model->name_user($row->editor_id, 'du') ?>
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
    </div>
</div>