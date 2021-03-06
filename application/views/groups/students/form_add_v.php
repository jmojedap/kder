<?php
    $gender_options = $this->Item_model->options('category_id = 59 AND cod <= 2');
    $id_number_type_options = $this->Item_model->options('category_id = 53 AND filters LIKE "%menor%"', 'Tipo documento');
?>

<form id="add_form" accept-charset="utf-8" @submit.prevent="validate_send">
    <input type="hidden" name="institution_id" value="<?= $this->session->userdata('institution_id'); ?>">
    <input type="hidden" name="role" value="23">

    <div class="form-group row">
        <label for="first_name" class="col-md-4 col-form-label text-right">Nombres | Apellidos</label>
        <div class="col-md-4">
            <input
                id="field-first_name"
                name="first_name"
                class="form-control"
                placeholder="Nombres"
                title="Nombres del usuario"
                required
                autofocus
                v-model="form_values.first_name"
                >
        </div>
        <div class="col-md-4">
            <input
                id="field-last_name"
                name="last_name"
                class="form-control"
                placeholder="Apellidos"
                title="Apellidos del usuario"
                required
                accept=""v-model="form_values.last_name"
                >
        </div>
    </div>

    <div class="form-group row">
        <label for="display_name" class="col-md-4 col-form-label text-right">Mostrar como</label>
        <div class="col-md-8">
            <div class="input-group">
                <input
                    type="text"
                    id="field-display_name"
                    name="display_name"
                    required
                    class="form-control"
                    placeholder="Ej. Juan Pérez"
                    title="Nombre mostrar"
                    v-model="form_values.display_name"
                    v-on:focus="empty_generate_display_name"
                    >
                <div class="input-group-append">
                    <button type="button" class="btn btn-primary" title="Generar Mostrar Como" v-on:click="generate_display_name">
                        <i class="fa fa-magic"></i> Generar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="code" class="col-md-4 col-form-label text-right">Código estudiante</label>
        <div class="col-md-8">
            <input
                type="text"
                id="field-code"
                name="code"
                class="form-control"
                placeholder="Código estudiante"
                title="Código estudiante"
                v-model="form_values.code"
                >
        </div>
    </div>
    
    <div class="form-group row" id="form-group_id_number">
        <label for="id_number" class="col-md-4 col-form-label text-right">No. Documento</label>
        <div class="col-md-4">
            <input
                id="field-id_number"
                name="id_number"
                class="form-control"
                v-bind:class="{ 'is-invalid': ! validation.id_number_unique }"
                placeholder="Número de documento"
                title="Solo números, sin puntos, debe tener al menos 5 dígitos"
                required
                pattern=".{5,}[0-9]"
                v-model="form_values.id_number"
                v-on:change="validate_form"
                >
            <span class="invalid-feedback">
                El número de documento escrito ya fue registrado para otro usuario
            </span>
        </div>
        <div class="col-md-4">
            <?= form_dropdown('id_number_type', $id_number_type_options, '', 'class="form-control" required v-model="form_values.id_number_type"') ?>
        </div>
    </div>
    
    <div class="form-group row" id="form-group_username">
        <label for="username" class="col-md-4 col-form-label text-right">Username</label>
        <div class="col-md-8">
            <div class="input-group">
                <!-- /btn-group -->
                <input
                    id="field-username"
                    name="username"
                    class="form-control"
                    v-bind:class="{ 'is-invalid': ! validation.username_unique }"
                    placeholder="username"
                    title="Puede contener letras y números, entre 6 y 25 caractéres, no debe contener espacios ni caracteres especiales"
                    required
                    pattern="^[A-Za-z0-9_]{6,25}$"
                    v-model="form_values.username"
                    v-on:change="validate_form"
                >
                <div class="input-group-append">
                    <button type="button" class="btn btn-primary" title="Generar username" v-on:click="generate_username">
                        <i class="fa fa-magic"></i>
                        Generar
                    </button>
                </div>
                
                <span class="invalid-feedback">
                    El username escrito no está disponible, por favor elija otro
                </span>
            </div>
        </div>
    </div>
    
    <div class="form-group row">
        <label for="gender" class="col-md-4 col-form-label text-right">Sexo</label>
        <div class="col-md-8">
            <?= form_dropdown('gender', $gender_options, '', 'class="form-control" required v-model="form_values.gender"') ?>
        </div>
    </div>

    <div class="form-group row">
        <label for="birth_date" class="col-md-4 col-form-label text-right">Fecha de nacimiento</label>
        <div class="col-md-8">
            <input
                id="field-birth_date"
                name="birth_date"
                class="form-control bs_datepicker"
                v-model="form_values.birth_date"
                type="date"
                >
        </div>
    </div>

    <div class="form-group row">
        <div class="offset-4 col-md-8">
            <button class="btn btn-success w120p" type="submit">
                Crear
            </button>
            <button class="btn btn-secondary w120p" type="button" v-on:click="toggle_show_form">
                Cancelar
            </button>
        </div>
    </div>
</form>