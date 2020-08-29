<?php $arr_meta = json_decode($row->meta); ?>

<div class="row">
    <div class="col-md-4">
        <?php if ( $row->is_image ) { ?>
            <img class="rounded mb-2" alt="imagen archivo" src="<?= $row->url_thumbnail ?>" style="max-width: 100%;">   
        <?php } ?>

        

        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>Updated by</td>
                    <td><?= $this->App_model->name_user($row->updater_id) ?></td>
                </tr>
                <tr>
                    <td>Updated at</td>
                    <td><?= $row->updated_at ?></td>
                </tr>
                <tr>
                    <td>Creator</td>
                    <td><?= $this->App_model->name_user($row->creator_id) ?></td>
                </tr>
                <tr>
                    <td>Created at</td>
                    <td><?= $row->created_at ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-4">
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>ID</td>
                    <td><?= $row->id ?></td>
                </tr>
                <tr>
                    <td>Type</td>
                    <td><?= $row->type_id ?></td>
                </tr>
                <tr>
                    <td>Folder</td>
                    <td><?= $row->folder ?></td>
                </tr>
                <tr>
                    <td>File name</td>
                    <td><?= $row->file_name ?></td>
                </tr>
                <tr>
                    <td>Is image</td>
                    <td><?= $row->is_image ?></td>
                </tr>
                <tr>
                    <td>Size</td>
                    <td><?= $row->size ?> KB</td>
                </tr>
                <tr>
                    <td>Dimensions</td>
                    <td><?= $row->width ?> x <?= $row->height ?></td>
                </tr>
            </tbody>
        </table>

        <div class="card">
            <div class="card-body">
                <h3 class="card-title"><?= $row->title ?></h3>
                <div class="row">
                    <div class="col-md-4 text-right">
                        subtitle
                    </div>
                    <div class="col-md-8">
                        <?= $row->subtitle ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 text-right">
                        description
                    </div>
                    <div class="col-md-8">
                        <?= $row->description ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 text-right">
                        keywords
                    </div>
                    <div class="col-md-8">
                        <?= $row->keywords ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <table class="table bg-white">
            <thead>
                <th>Variable</th>
                <th>Value</th>
            </thead>
            <tbody>
                <?php if ( ! is_null($arr_meta) ) { ?>    
                    <?php foreach ( $arr_meta as $meta_field => $meta_value ) { ?>
                        <tr>
                            <td><?= $meta_field ?></td>
                            <td><?= $meta_value ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>