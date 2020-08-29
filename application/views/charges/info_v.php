<div class="row">
    <div class="col col-lg-4 col-md-12 col-sm-12">
        
    </div>
    <div class="col col-lg-8 col-md-12 col-sm-12">
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td width="35%"><span class="text-muted">ID Cobro</span></td>
                    <td width="65%">
                        <?= $row->id ?>
                    </td>
                </tr>

                <tr>
                    <td class=""><span class="text-muted">Tipo</span></td>
                    <td><?= $this->Item_model->name(172, $row->charge_type_id) ?></td>
                </tr>

                <tr>
                    <td class=""><span class="text-muted">Título</span></td>
                    <td><?= $row->title ?></td>
                </tr>

                <tr>
                    <td><span class="text-muted">Año generación</span></td>
                    <td><?= $row->generation ?></td>
                </tr>

                <tr>
                    <td><span class="text-muted">Valor</span></td>
                    <td><?= $this->pml->money($row->charge_value); ?></td>
                </tr>

                <tr>
                    <td><span class="text-muted">Fecha máxima de pago</span></td>
                    <td>
                        <?= $this->pml->date_format($row->date_2, 'Y-M-d') ?> &middot;
                        <?= $this->pml->ago($row->date_2); ?>
                    </td>
                </tr>

                <tr>
                    <td class=""><span class="text-muted">Descripción</span></td>
                    <td><?= $row->excerpt ?></td>
                </tr>

                <tr>
                    <td class=""><span class="text-muted">Editado</span></td>
                    <td>
                        <?= $this->pml->date_format($row->updated_at, 'Y-m-d h:i') ?> por <?= $this->App_model->name_user($row->updater_id, 'du') ?>
                    </td>
                </tr>
                <tr>
                    <td class=""><span class="text-muted">Creado</span></td>
                    <td>
                        <?= $this->pml->date_format($row->created_at, 'Y-m-d H:i') ?> por <?= $this->App_model->name_user($row->creator_id, 'du') ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>