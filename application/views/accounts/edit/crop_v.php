<?php $this->load->view('assets/cropper'); ?>

<?php
    $att_submit = array(
        'class' => 'btn btn-success btn-block',
        'value' => 'Recortar'
    );
?>

<?php if ( isset($vista_menu) ) { ?>
    <?php $this->load->view($vista_menu); ?>
<?php } ?>

<div class="row">
    <div class="col-md-9">
        <div class="img-container">
            <img id="image" src="<?= $url_image ?>" alt="Picture">
        </div>
    </div>
    <div class="col-md-3">
        
        <div class="docs-preview clearfix">
            <div class="img-preview preview-lg"></div>
            <div class="img-preview preview-md"></div>
            <div class="img-preview preview-sm"></div>
            <div class="img-preview preview-xs"></div>
        </div>

        <?= form_open("usuarios/recortar_imagen_e/{$row->id}/{$row->imagen_id}") ?>
            <div class="docs-data d-none">
                <div class="input-group input-group-sm">
                    <label class="input-group-addon" for="dataX">X</label>
                    <input type="text" class="form-control" id="dataX" placeholder="x" name="x_axis">
                </div>
                <div class="input-group input-group-sm">
                    <label class="input-group-addon" for="dataY">Y</label>
                    <input type="text" class="form-control" id="dataY" placeholder="y" name="y_axis">
                </div>
                <div class="input-group input-group-sm">
                    <label class="input-group-addon" for="dataWidth">Width</label>
                    <input type="text" class="form-control" id="dataWidth" placeholder="width" name="width">
                </div>
                <div class="input-group input-group-sm">
                    <label class="input-group-addon" for="dataHeight">Height</label>
                    <input type="text" class="form-control" id="dataHeight" placeholder="height" name="height">
                </div>
            </div>
            <?= form_submit($att_submit) ?>
        
        <br/>
        
        <div class="docs-toggles d-none">
            <div class="btn-group btn-group-justified" data-toggle="buttons">
                <label class="btn btn-default">
                    <input type="radio" class="sr-only" id="aspectRatio0" name="aspectRatio" value="1.7777777777777777">
                    <span class="docs-tooltip" data-toggle="tooltip" title="Proporción: 16 / 9">
                        16:9
                    </span>
                </label>
                <label class="btn btn-default">
                    <input type="radio" class="sr-only" id="aspectRatio1" name="aspectRatio" value="1.3333333333333333">
                    <span class="docs-tooltip" data-toggle="tooltip" title="Proporción: 4 / 3">
                        4:3
                    </span>
                </label>
                <label class="btn btn-default active">
                    <input type="radio" class="sr-only" id="aspectRatio2" name="aspectRatio" value="1">
                    <span class="docs-tooltip" data-toggle="tooltip" title="Proporción: 1 / 1">
                        1:1
                    </span>
                </label>
                <label class="btn btn-default">
                    <input type="radio" class="sr-only" id="aspectRatio3" name="aspectRatio" value="0.6666666666666666">
                    <span class="docs-tooltip" data-toggle="tooltip" title="Proporción: 2 / 3">
                        2:3
                    </span>
                </label>
                <label class="btn btn-default">
                    <input type="radio" class="sr-only" id="aspectRatio4" name="aspectRatio" value="NaN">
                    <span class="docs-tooltip" data-toggle="tooltip" title="Proporción: Manual">
                        Manual
                    </span>
                </label>
            </div>
    
        </div>
        
        <?= form_close('') ?>
    </div>
</div>