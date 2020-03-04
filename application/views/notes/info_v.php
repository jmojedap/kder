<?php
    $att_img_user = $this->App_model->att_img_user($row_user, 'sm_');
?>

<div class="card center_box_750">
    <div class="card-body">
        <div class="media">
            <img src="<?php echo $att_img_user['src'] ?>" class="mr-3" alt="Imagen usuario anotación">
            <div class="media-body">
                <a href="<?php echo base_url("users/notes/{$row_user->id}/{$row_user->username}") ?>">
                    <?php echo $row_user->display_name ?>
                </a>
            </div>
        </div>

        <hr>

        <h3 class="card-title"><?php echo $row->post_name ?></h3>
        <p><?php echo $row->excerpt ?></p>
        <?php if ( $row->content ) { ?>
            <h4>Detalle</h4>
            <?php echo $row->content ?>
        <?php } ?>

        <p>
            <span class="text-muted">Tipo:</span>
            <?php echo $this->Item_model->name(191, $row->cat_1); ?>
        </p>

        <hr>

        
        <p>
            <span class="text-muted">Creada</span>
            <span title="<?php echo $this->pml->date_format($row->created_at); ?>">
                <?php echo $this->pml->date_format($row->created_at, 'M-d'); ?>
                (<?php echo $this->pml->ago($row->created_at); ?>)
            </span>
            <span class="text-muted">Por</span>
            <span><?php echo $this->App_model->name_user($row->creator_id, 'du'); ?></span>
            &middot;
            <span class="text-muted">Editada</span>
            <span title="<?php echo $this->pml->date_format($row->edited_at); ?>">
                <?php echo $this->pml->date_format($row->edited_at, 'M-d'); ?>
                (<?php echo $this->pml->ago($row->edited_at); ?>)
            </span>
            <span class="text-muted">Por</span>
            <span><?php echo $this->App_model->name_user($row->creator_id, 'du'); ?></span>
        </p>
    </div>
</div>