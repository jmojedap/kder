<?php $this->load->view('posts/image/script_v') ?>

<?php
    $style_image_section = '';
    if ( $row->image_id == 0 ) { $style_image_section = 'display: none;';}
?>

<div class="row mb-2">
    <div class="col-md-6">
        <?php $this->load->view('posts/image/form_v') ?>
    </div>
    <div class="col-md-6">
        <div id="image_section" style="<?php echo $style_image_section ?>">
            <div class="card mb-2">
                <div class="card-body">
                    <a class="btn btn-light" id="btn_crop" href="<?php echo base_url("posts/cropping/{$row->id}") ?>">
                        <i class="fa fa-crop"></i>
                    </a>
                    <button class="btn btn-warning" id="btn_remove_image">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
            <img
                id="post_image"
                class="img-fluid rounded"
                width="100%"
                src="<?php echo $att_image['src'] ?>"
                alt="<?php echo $att_image['alt'] ?>"
                onerror="<?php echo $att_image['onerror'] ?>"
            >
        </div>
    </div>
</div>