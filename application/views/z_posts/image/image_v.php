<?php $this->load->view('posts/image/script_v') ?>

<?php
    $style_image_section = '';
    if ( $row->image_id == 0 ) { $style_image_section = 'display: none;';}
?>

<div class="card center_box_750" id="image_section" style="<?= $style_image_section ?>">
    <img
        id="post_image"
        class="card-img-top"
        width="100%"
        src="<?= $att_img['src'] ?>"
        alt="<?= $att_img['alt'] ?>"
        onerror="<?= $att_img['onerror'] ?>"
    >
    <div class="card-body">
        

        <a class="btn btn-info" id="btn_crop" href="<?= base_url("posts/cropping/{$row->id}") ?>">
            <i class="fa fa-crop"></i> Recortar
        </a>
        <button class="btn btn-warning" id="btn_remove_image">
            <i class="fa fa-trash"></i> Eliminar
        </button>
    </div>
</div>

<?php $this->load->view('posts/image/form_v') ?>