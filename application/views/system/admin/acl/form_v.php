<form id="acl_form" @submit.prevent="send_form" accept-charset="utf-8">
    <input type="hidden" name="subdomain" v-model="subdomain">
    <input type="hidden" name="controller" v-model="controller">

    <div class="form-group row">
        <label for="function_name" class="col-md-4 control-label">
            <span class="float-right">Función</span>
        </label>
        <div class="col-md-8">
            <input
                id="field-function_name"
                name="function_name"
                class="form-control"
                placeholder="función"
                title="función"
                v-model="form_values.function_name"
                ref="field_function_name"
                required
                >
        </div>
    </div>

    <div class="form-group row">
        <label for="description" class="col-md-4 control-label">
            <span class="float-right">Descripción</span>
        </label>
        <div class="col-md-8">
            <textarea
                id="field-description"
                name="description"
                class="form-control"
                placeholder="Descripción"
                title="Descripción"
                rows="4"
                v-model="form_values.description"
                required
                >
            </textarea>
        </div>
    </div>
    
    <div class="form-group row">
        <label for="type_id" class="col-md-4 control-label">
            <span class="float-right">Tipo</span>
        </label>
        <div class="col-md-8">
            <?php echo form_dropdown('type_id', $options_type_id, '', 'class="form-control" required v-model="form_values.type_id"') ?>
        </div>
    </div>
    
    <div class="form-group row">
        <label for="roles" class="col-md-4 control-label">
            <span class="float-right">Roles autorizados</span>
        </label>
        <div class="col-md-8">
            <input
                id="field-roles"
                name="roles"
                class="form-control"
                placeholder="roles autorizados"
                title="roles autorizados"
                required
                v-model="form_values.roles"
                >
        </div>
    </div>
    
    <div class="form-group row">
        <div class="offset-md-4 col-sm-4">
            <button class="btn btn-block" type="button" data-dismiss="modal">
                Cerrar
            </button>
        </div>
        <div class="col-sm-4">
            <button class="btn btn-block" v-bind:class="config_form.button_class" type="submit">
                {{ config_form.button_text }}
            </button>
        </div>
    </div>
    
</form>