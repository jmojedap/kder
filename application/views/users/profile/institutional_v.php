<?php $this->load->view('users/profile/activation_script_v') ?>

<?php 
    //Imagen
        $att_img = $this->App_model->att_img_user($row);
        $att_img['class'] = 'card-img-top';

    $qty_login = $this->Db_model->num_rows('event', "user_id = {$row->id} AND type_id = 101");
?>

<div class="row">
    <div class="col col-md-4">
        <!-- Page Widget -->
        <div class="card text-center">
            <img src="<?= $row->url_image ?>" alt="Imagen del usuario" class="w100pc" onerror="this.src='<?php echo URL_IMG ?>app/user.png'">
            <div class="card-body">
                <h4 class="profile-user"><?= $this->Item_model->name(58, $row->role) ?></h4>

                <?php if ($this->session->userdata('rol_id') <= 1) { ?>
                    <a href="<?= base_url("admin/ml/{$row->id}") ?>" role="button" class="btn btn-primary" title="Ingresar como este usuario">
                        <i class="fa fa-sign-in"></i>
                        Acceder
                    </a>
                <?php } ?>

            </div>
            <div class="card-footer">
                <div class="row no-space">
                    <div class="col-12">
                        <?php if ( strlen($row->birth_date) > 0 ) { ?>
                            <strong class="profile-stat-count"><?= $this->pml->age($row->birth_date); ?></strong>
                            <span>Años</span>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Widget -->
    </div>
    <div class="col col-md-8">
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td class="text-right" width="25%"><span class="text-muted">No. Documento</span></td>
                    <td>
                        <?= $row->id_number ?>
                        <?= $this->Item_model->name(53, $row->id_number_type); ?>
                    </td>
                </tr>

                <tr>
                    <td class="text-right"><span class="text-muted">Nombre</span></td>
                    <td><?= $row->first_name ?></td>
                </tr>
                <tr>
                    <td class="text-right"><span class="text-muted">Apellidos</span></td>
                    <td><?= $row->last_name ?></td>
                </tr>

                <tr>
                    <td class="text-right"><span class="text-muted">Nombre de usuario</span></td>
                    <td><?= $row->username ?></td>
                </tr>

                <tr>
                    <td class="text-right"><span class="text-muted">Correo electrónico</span></td>
                    <td><?= $row->email ?></td>
                </tr>

                <tr>
                    <td class="text-right"><span class="text-muted">Sexo</span></td>
                    <td><?= $this->Item_model->name(59, $row->gender) ?></td>
                </tr>

                <tr>
                    <td class="text-right">Institución</td>
                    <td>
                        <a href="<?= base_url("institutions/info/{$row->institution_id}") ?>">
                            <?= $this->App_model->name_institution($row->institution_id); ?>
                        </a>
                    </td>
                </tr>

                <tr>
                    <td class="text-right"><span class="text-muted">Rol de usuario</span></td>
                    <td><?= $this->Item_model->name(58, $row->role) ?></td>
                </tr>

                <tr>
                    <td class="text-right"><span class="text-muted">Fecha de nacimiento</span></td>
                    <td><?= $this->pml->date_format($row->birth_date, 'Y-M-d') ?></td>
                </tr>
                <tr>
                    <td class="text-right"><span class="text-muted">Editado</span></td>
                    <td>
                        <?= $this->pml->date_format($row->updated_at, 'Y-m-d h:i') ?> por <?= $this->App_model->name_user($row->updater_id, 'du') ?>
                    </td>
                </tr>
                <tr>
                    <td class="text-right"><span class="text-muted">Creado</span></td>
                    <td>
                        <?= $this->pml->date_format($row->created_at, 'Y-m-d H:i') ?> por <?= $this->App_model->name_user($row->creator_id, 'du') ?>
                    </td>
                </tr>
                <?php if ( $this->session->userdata('role') <= 2  ) { ?>
                    <tr>
                        <td class="text-right">
                            <button class="btn btn-primary btn-sm" id="btn_set_activation_key">
                                <i class="fa fa-redo-alt"></i>
                            </button>
                            <span class="text-muted">Activación</span>
                        </td>
                        <td>
                            <span id="activation_key"></span>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>