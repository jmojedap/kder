<?php    
    //Clases filters
        foreach ( $adv_filters as $filter )
        {
            $adv_filters_cl[$filter] = 'not_filtered';
            //if ( strlen($filters[$filter]) > 0 ) { $adv_filters_cl[$filter] = ''; }
        }
?>

<form accept-charset="utf-8" id="search_form" method="POST">
    <div class="form-horizontal">
        <div class="form-group row">
            <div class="col-md-9">
                <div class="input-group">
                    <input
                        type="text"
                        name="q"
                        class="form-control"
                        placeholder="Buscar evento"
                        autofocus
                        title="Buscar evento"
                        value="<?= $filters['q'] ?>"
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

        <div class="form-group row <?= $adv_filters_cl['type'] ?>">
            <div class="col-md-9">
                <?= form_dropdown('type', $options_type, $filters['type'], 'class="form-control" title="Filtrar por tipo de evento"'); ?>
            </div>
            <label for="type" class="col-md-3 control-label">Tipo</label>
        </div>
    </div>
</form>