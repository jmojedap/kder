<?php
    $att_img = $this->File_model->att_img($row->image_id);

    $cl_elements['image'] = 'd-none';
    $cl_elements['alert'] = '';

    if ( $row->image_id > 0 )
    {
        $cl_elements['image'] = '';
        $cl_elements['alert'] = 'd-none';
    }
?>
<?php $this->load->view('institutions/image/script_v') ?>

<div style="max-width: 600px; margin: 0 auto;">
    <div id="section_image" class="<?php echo $cl_elements['image'] ?>">
        <div class="card">
            <img
                id="element_image"
                class="card-img-top"
                width="100%"
                src="<?php echo $att_img['src'] ?>"
                alt="<?php echo $att_img['alt'] ?>"
                onerror="<?php echo $att_img['onerror'] ?>"
            >
            <div class="card-body">
                <a class="btn btn-light" id="btn_crop" href="<?php echo base_url($link_cropping) ?>">
                    <i class="fa fa-crop"></i>
                </a>
                <button class="btn btn-warning" id="btn_remove_image" title="Eliminar imagen">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    <div id="section_upload" class="<?php echo $cl_elements['alert'] ?>">
        <div class="alert alert-info">
            <i class="fa fa-info-circle"></i>
            La institución no tiene imagen asignada
        </div>
        <?php $this->load->view('institutions/image/form_v') ?>
    </div>
</div>