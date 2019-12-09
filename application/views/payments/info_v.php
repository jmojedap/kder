<?php 
    //Imagen
        $att_img = $this->App_model->att_img_user($row_teacher);
        $att_img['class'] = 'card-img-top';
?>

<div class="row">
    <div class="col col-lg-4 col-md-12 col-sm-12">
        <!-- Page Widget -->
        <div class="card text-center">
            <img
                src="<?php echo $att_img['src'] ?>"
                class="card-img-top"
                alt="Imagen de la institución"
                width="100%"
                onerror="this.src='<?php echo URL_IMG . 'app/institution.png' ?>'"
                >
            <div class="card-body">
                <h4 class="profile-user"><?php echo $row_teacher->display_name ?></h4>
                <p>Asignada del grupo</p>
            </div>
            <div class="card-footer">
                <div class="row no-space">
                    <div class="col-4">
                        
                    </div>
                    <div class="col-4">
                        <strong class="profile-stat-count">14</strong>
                        <span>Estudiantes</span>
                    </div>
                    <div class="col-4">
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Widget -->
    </div>
    <div class="col col-lg-8 col-md-12 col-sm-12">
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td width="35%"><span class="text-muted">ID Grupo</span></td>
                    <td width="65%">
                        <?php echo $row->id ?>
                    </td>
                </tr>

                <tr>
                    <td class=""><span class="text-muted">Nombre</span></td>
                    <td><?php echo $row->name ?></td>
                </tr>
                <tr>
                    <td class=""><span class="text-muted">Título</span></td>
                    <td><?php echo $row->title ?></td>
                </tr>

                <tr>
                    <td><span class="text-muted">Nivel</span></td>
                    <td><?php echo $this->Item_model->name(3, $row->level) ?></td>
                </tr>

                <tr>
                    <td><span class="text-muted">Año generación</span></td>
                    <td><?php echo $row->generation ?></td>
                </tr>

                <tr>
                    <td class=""><span class="text-muted">Descripción</span></td>
                    <td><?php echo $row->description ?></td>
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