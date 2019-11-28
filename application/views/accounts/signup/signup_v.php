<div id="signup_app">
    <div v-show="type == 0">
        <button class="btn btn-primary btn-block btn-lg" @click="update_type(1)">
            Tengo un jardín
        </button>

        <button class="btn btn-primary btn-block btn-lg">
            Trabajo en un jardín
        </button>
        <button class="btn btn-primary btn-block btn-lg">
            Soy Padre/Madre
        </button>
    </div>

    <div v-show="type > 0">
        <button class="btn btn-secondary btn-block mb-2" @click="update_type(0)">
            <i class="fa fa-arrow-left"></i> Atrás
        </button>
        <?php $this->load->view('accounts/signup/form_v') ?>
    </div>
</div>

<?php $this->load->view('accounts/signup/vue_v') ?>