<?php 
    //Imagen
        //$att_img = $this->App_model->att_img_user($row);
        //$att_img['class'] = 'card-img-top';
?>

<div class="row">
    <div class="col col-md-4">
        <!-- Page Widget -->
        <div class="card text-center">
            <img
                src="<?php echo $att_img['src'] ?>"
                alt="Imagen de la institución"
                width="100%"
                onerror="this.src='<?php echo URL_IMG . 'app/institution.png' ?>'"
                >
            <div class="card-body">
                <h4 class="profile-user"><?php echo $row->name ?></h4>
            </div>
            <div class="card-footer">
                <div class="row no-space">
                    <div class="col-4">
                        
                    </div>
                    <div class="col-4">
                        <strong class="profile-stat-count">14</strong>
                        <span>Funcionarios</span>
                    </div>
                    <div class="col-4">
                        <strong class="profile-stat-count">78</strong>
                        <span>Estudiantes</span>
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
                    <td class="" width="35%"><span class="text-muted">No. Identificación</span></td>
                    <td width="65%">
                        <?php echo $row->id_number ?>
                    </td>
                </tr>

                <tr>
                    <td class=""><span class="text-muted">Nombre / Marca Comercial</span></td>
                    <td><?php echo $row->name ?></td>
                </tr>
                <tr>
                    <td class=""><span class="text-muted">Nombre Completo / Razón Social</span></td>
                    <td><?php echo $row->full_name ?></td>
                </tr>

                <tr>
                    <td class=""><span class="text-muted">Usuario Propietario</span></td>
                    <td><?php echo $row->creator_id ?></td>
                </tr>

                <tr>
                    <td class=""><span class="text-muted">Correo electrónico</span></td>
                    <td><?php echo $row->email ?></td>
                </tr>

                <tr>
                    <td class=""><span class="text-muted">Página Web</span></td>
                    <td><?php echo $row->webpage ?></td>
                </tr>

                <tr>
                    <td class=""><span class="text-muted">Teléfono</span></td>
                    <td><?php echo $row->phone_number ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>