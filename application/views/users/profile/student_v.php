<script>
// Variables
//-----------------------------------------------------------------------------
    user_id = '<?php echo $row->id ?>';

// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function(){
        $('#btn_set_activation_key').click(function(){
            set_activation_key();
        });
    });

// Functions
//-----------------------------------------------------------------------------

    function set_activation_key(){
        $.ajax({        
            type: 'POST',
            url: app_url + 'users/set_activation_key/' + user_id,
            success: function(response){
                $('#activation_key').html(app_url + 'accounts/activation/' + response);
                toastr['success']('Se actualizó la clave de activación');
            }
        });
    }
</script>

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
            <img src="<?php echo $att_img['src'] ?>" alt="Imagen del usuario" width="100%">
            <div class="card-body">
                <h4 class="profile-user"><?php echo $this->Item_model->name(58, $row->role) ?></h4>

                <?php if ($this->session->userdata('rol_id') <= 1) { ?>
                    <a href="<?php echo base_url("admin/ml/{$row->id}") ?>" role="button" class="btn btn-primary" title="Ingresar como este usuario">
                        <i class="fa fa-sign-in"></i>
                        Acceder
                    </a>
                <?php } ?>
                
                <?php if ($this->session->userdata('rol_id') != $this->session->userdata('user_id') ) { ?>
                    <a href="<?php echo base_url("messages/create_conversation/{$row->id}") ?>" role="button" class="btn btn-primary" title="Enviar mensaje a este usuario">
                        <i class="fa fa-envelope"></i>
                        Mensaje
                    </a>
                <?php } ?>

            </div>
            <div class="card-footer">
                <div class="row no-space">
                    <div class="col-4">
                        <?php if ( strlen($row->birth_date) > 0 ) { ?>
                            <strong class="profile-stat-count"><?php echo $this->pml->age($row->birth_date); ?></strong>
                            <span>Años</span>
                        <?php } ?>
                    </div>
                    <div class="col-4">
                        <strong class="profile-stat-count">180</strong>
                        <span>Following</span>
                    </div>
                    <div class="col-4">
                        <strong class="profile-stat-count"><?php echo $qty_login ?></strong>
                        <span>Sesiones</span>
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
                        <?php echo $row->id_number ?>
                        <?php echo $this->Item_model->name(53, $row->id_number_type); ?>
                    </td>
                </tr>

                <tr>
                    <td class="text-right"><span class="text-muted">Nombre</span></td>
                    <td><?php echo $row->first_name ?></td>
                </tr>
                <tr>
                    <td class="text-right"><span class="text-muted">Apellidos</span></td>
                    <td><?php echo $row->last_name ?></td>
                </tr>

                <tr>
                    <td class="text-right"><span class="text-muted">Nombre de usuario</span></td>
                    <td><?php echo $row->username ?></td>
                </tr>

                <tr>
                    <td class="text-right"><span class="text-muted">Sexo</span></td>
                    <td><?php echo $this->Item_model->name(59, $row->gender) ?></td>
                </tr>

                <tr>
                    <td class="text-right"><span class="text-muted">Rol de usuario</span></td>
                    <td><?php echo $this->Item_model->name(58, $row->role) ?></td>
                </tr>

                <tr>
                    <td class="text-right"><span class="text-muted">Fecha de nacimiento</span></td>
                    <td><?php echo $this->pml->date_format($row->birth_date, 'Y-M-d') ?></td>
                </tr>
                <tr>
                    <td class="text-right"><span class="text-muted">Código</span></td>
                    <td><?php echo $row->code ?></td>
                </tr>
                <tr>
                    <td class="text-right"><span class="text-muted">Editado</span></td>
                    <td>
                        <?php echo $this->pml->date_format($row->edited_at, 'Y-m-d h:i') ?> por <?php echo $this->App_model->name_user($row->editor_id, 'du') ?>
                    </td>
                </tr>
                <tr>
                    <td class="text-right"><span class="text-muted">Creado</span></td>
                    <td>
                        <?php echo $this->pml->date_format($row->created_at, 'Y-m-d H:i') ?> por <?php echo $this->App_model->name_user($row->creator_id, 'du') ?>
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