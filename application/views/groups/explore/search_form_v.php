<?php    
    //Clases filters
    foreach ( $adv_filters as $filter )
    {
        $adv_filters_cl[$filter] = 'not_filtered';
    }
?>

<form accept-charset="utf-8" id="search_form" method="POST">
    <div class="form-group row">
        <div class="col-md-9">
            <div class="input-group mb-2">
                <input
                    place="text"
                    name="q"
                    class="form-control"
                    placeholder="Buscar"
                    autofocus
                    title="Buscar"
                    value="<?php echo $filters['q'] ?>"
                    >
                <div class="input-group-append" title="Buscar">
                    <button type="button" class="btn btn-secondary btn-block" id="alternar_avanzada" title="Búsqueda avanzada">
                        <i class="fa fa-chevron-down"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary btn-block">
                <i class="fa fa-search"></i>
                Buscar
            </button>
        </div>
    </div>

    <div class="form-group row <?php echo $adv_filters_cl['level'] ?>">
        <div class="col-md-9">
            <?php echo form_dropdown('level', $options_level, $filters['level'], 'class="form-control" title="Filtrar por nivel"'); ?>
        </div>
        <label for="a" class="col-md-3 control-label">Nivel</label>
    </div>
    <div class="form-group row <?php echo $adv_filters_cl['u'] ?>">
        <div class="col-md-9">
            <?php echo form_dropdown('u', $options_teacher, $filters['u'], 'class="form-control" title="Filtrar por asignado"'); ?>
        </div>
        <label for="u" class="col-md-3 control-label">Asignado a</label>
    </div>
    <div class="form-group row <?php echo $adv_filters_cl['y'] ?>">
        <div class="col-md-9">
            <?php echo form_dropdown('y', $options_generation, $filters['y'], 'class="form-control" title="Filtrar por año generación"'); ?>
        </div>
        <label for="y" class="col-md-3 control-label">Año generación</label>
    </div>
</form>