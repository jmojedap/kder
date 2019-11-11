<form accept-charset="utf-8" id="search_form" @submit.prevent="search">
    
    <div class="form-group row">
        <label for="q" class="col-md-4 control-label">
            <button class="btn btn-default btn-block" type="button" v-on:click="toggle_filters">
                Filtros
                <i class="fa fa-caret-down"></i>
            </button>
        </label>
        <div class="col-md-8">
            <div class="input-group">
                <input
                    name="q"
                    class="form-control"
                    placeholder="Buscar comentario..."
                    title="Buscar comentario"
                    autofocus
                    >
                <div class="input-group-append">
                    <button class="btn btn-primary input-group-addon">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="form-group row" v-show="show_filters">
        <label for="rol" class="col-md-4 control-label text-right">
            Tipo
        </label>
        <div class="col-md-8">
            <?php //echo form_dropdown('type', $type_options, '', 'class="form-control" title="Filtrar por tipo de comentario"'); ?>
        </div>
    </div>
    
</form>