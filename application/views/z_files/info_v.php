<?php $arr_meta = json_decode($row->meta); ?>

<div class="row">
    <div class="col-md-4">
        <?php if ( $row->is_image ) { ?>
            <img class="rounded mb-2" alt="imagen archivo" src="<?= URL_UPLOADS . $row->folder . $row->file_name ?>" style="max-width: 100%;">   
        <?php } ?>

        

        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>updater_id</td>
                    <td><?= $row->updater_id ?></td>
                </tr>
                <tr>
                    <td>updated_at</td>
                    <td><?= $row->updated_at ?></td>
                </tr>
                <tr>
                    <td>creator_id</td>
                    <td><?= $row->creator_id ?></td>
                </tr>
                <tr>
                    <td>created_at</td>
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
                    <td>type_id</td>
                    <td><?= $row->type_id ?></td>
                </tr>
                <tr>
                    <td>file name</td>
                    <td><?= $row->file_name ?></td>
                </tr>
                <tr>
                    <td>folder</td>
                    <td><?= $row->folder ?></td>
                </tr>
                <tr>
                    <td>is image</td>
                    <td><?= $row->is_image ?></td>
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
                <div class="row">
                    <div class="col-md-4 text-right">
                        link
                    </div>
                    <div class="col-md-8">
                        <?= $row->link ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <table class="table bg-white">
            <thead>
                <th>Dato</th>
                <th>Valor</th>
            </thead>
            <tbody>
                <?php foreach ( $arr_meta as $meta_field => $meta_value ) { ?>
                    <tr>
                        <td><?= $meta_field ?></td>
                        <td><?= $meta_value ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>