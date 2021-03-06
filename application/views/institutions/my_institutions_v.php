<?php foreach ( $institutions->result() as $row_institution ) { ?>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-1">
                    <?= $row_institution->id ?>
                </div>
                <div class="col-md-4">
                    <a href="<?= base_url("institutions/info/{$row_institution->id}") ?>">
                        <?= $row_institution->name ?>
                    </a>
                </div>
                <div class="col-md-6">
                    <?= $row_institution->email ?>
                </div>
                <div class="col-md-1">
                    <a href="<?= base_url("institutions/edit/{$row_institution->id}") ?>" class="btn btn-light btn-sm">
                        <i class="fa fa-edit"></i> Editar
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php } ?>