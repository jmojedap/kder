<?php
    $filters_style = ( strlen($str_filters) > 0 ) ? '' : 'display: none;' ;
?>

<form accept-charset="utf-8" method="POST" id="search_form" @submit.prevent="get_list">
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
                    v-model="filters.q"
                    v-on:change="get_list"
                    >
                <div class="input-group-append" title="Buscar">
                    <button type="button" class="btn btn-secondary btn-block" v-on:click="toggle_filters" title="Búsqueda avanzada">
                        <i class="fa fa-chevron-up" v-show="showing_filters"></i>
                        <i class="fa fa-chevron-down" v-show="!showing_filters"></i>
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
    <div id="adv_filters" style="<?php echo $filters_style ?>">
        <div class="form-group row">
            <div class="col-md-9">
                <?php echo form_dropdown('level', $options_level, $filters['level'], 'class="form-control" title="Filtrar por nivel"'); ?>
            </div>
            <label for="a" class="col-md-3 control-label">Nivel</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <?php echo form_dropdown('u', $options_teacher, $filters['u'], 'class="form-control" title="Filtrar por asignado"'); ?>
            </div>
            <label for="u" class="col-md-3 control-label">Asignado a</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <?php echo form_dropdown('y', $options_generation, $filters['y'], 'class="form-control" title="Filtrar por año generación"'); ?>
            </div>
            <label for="y" class="col-md-3 control-label">Año generación</label>
        </div>
    </div>
</form>