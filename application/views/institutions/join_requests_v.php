<?php $request = $requests->row(); ?>

<div class="card center_box_750">
    <div class="card-body">
        <h3 class="card-title">
            <i class="fa fa-check text-success"></i>
            Solicitud enviada
        </h3>
        <p>
            La institución revisará su solicitud. Cuando responda usted recibirá una notificación
            al correo electrónico con el que se registró.
        </p>
        <dl class="row">
            <dt class="col-md-3">Institución</dt>
            <dd class="col-md-9"><?php echo $this->Db_model->field_id('institution', $request->related_1, 'name') ?></dd>

            <dt class="col-md-3">Vincular como</dt>
            <dd class="col-md-9"><?php echo $this->Item_model->name(58, $request->cat_1) ?></dd>

            <dt class="col-md-3">Solicitud creada</dt>
            <dd class="col-md-9"><?php echo $this->pml->date_format($request->created_at, 'Y-m-d') ?></dd>

            <dt class="col-md-3">Hace</dt>
            <dd class="col-md-9"><?php echo $this->pml->ago($request->created_at) ?></dd>
        </dl>
    </div>
</div>
