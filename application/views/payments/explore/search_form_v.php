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
    <div class="form-group row">
        <div class="col-md-9">
            <?php echo form_dropdown('type', $options_type, $filters['type'], 'class="form-control" title="Filtrar por tipo de cobro" v-model="filters.type"'); ?>
        </div>
        <label for="type" class="col-md-3 control-label align-middle">Tipo cobro</label>
    </div>
</form>