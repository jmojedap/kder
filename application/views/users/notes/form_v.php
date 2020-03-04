<form accept-charset="utf-8" method="POST" id="note_form" @submit.prevent="send_form">
    <input type="hidden" value="<?php echo $row->id ?>" name="related_1">
    <div class="form-group row">
        <label class="col-md-2 text-right col-form-label" for="post_name">Asunto</label>
        <div class="col-md-10">
            <input
                type="text"
                id="field_post_name"
                name="post_name"
                required
                class="form-control"
                placeholder=""
                title="Asunto"
                maxlength="70"
                v-bind:value="form_values.post_name"
                >
        </div>
    </div>

    <div class="form-group row">
        <label for="excerpt" class="col-md-2 text-right col-form-label">Anotación</label>
        <div class="col-md-10">
            <textarea
                id="field-excerpt"
                name="excerpt"
                required
                class="form-control"
                placeholder=""
                title="Texto de la anotación"
                rows="5"
                v-bind:value="form_values.excerpt"
                maxlength="280"
                ></textarea>
        </div>
    </div>

    <div class="form-group row">
        <label for="cat_1" class="col-md-2 col-form-label text-right">Tipo</label>
        <div class="col-md-10">
            <?php echo form_dropdown('cat_1', $options_type, '', 'class="form-control" required v-bind:value="form_values.cat_1"') ?>
        </div>
    </div>
    
    <div class="form-group row">
        <div class="col-md-10 offset-md-2">
            <button type="submit" class="btn btn-success w120p" v-show="edition_id == 0">Agregar</button>
            <button type="button" class="btn btn-secondary w120p" v-show="edition_id == 0" v-on:click="set_show_add(false)">Cancelar</button>

            <button type="submit" class="btn btn-primary w120p" v-show="edition_id > 0">Actualizar</button>
            <button type="button" class="btn btn-secondary w120p" v-show="edition_id > 0" v-on:click="cancel_edition">Cancelar</button>
        </div>
    </div>
</form>