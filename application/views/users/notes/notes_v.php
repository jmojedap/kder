<script src="<?= URL_RESOURCES . 'js/pcrn.js' ?>"></script>
<?php $this->load->view('assets/momentjs') ?>

<div id="app_notes">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            

            <form accept-charset="utf-8" method="POST" id="search_form" @submit.prevent="get_list">
                <input type="hidden" name="u" value="<?= $row->id ?>">
                <div class="input-group">
                    <input
                        type="text"
                        id="field-q"
                        name="q"
                        required
                        class="form-control"
                        placeholder="Buscar"
                        title="Buscar"
                        v-model="search.q"
                        >
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>
            
        </div>
        <div class="col-lg-9 col-md-7">
            <div class="mw750p">
                <div class="row mb-1">
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary w120p" v-on:click="set_show_add(true)" v-show="!show_add">
                            <i class="fa fa-plus"></i>
                            Nueva
                        </button>
                    </div>
                    <div class="col-md-10">
                        <?php $this->load->view('common/vue_range_pagination_v') ?>
                        
                    </div>
                </div>
            </div>
            <div class="card mb-1 mw750p" v-show="show_add">
                <div class="card-header">Nueva anotación</div>
                <div class="card-body" id="new_note">
                    <?php $this->load->view('users/notes/form_v') ?>
                </div>
            </div>

            <div class="card mw750p mb-1" v-for="(note, key) in list" v-bind:id="`note_` + note.id" v-bind:class="{'border-info': row_id == note.id }">
                <div class="card-body" v-bind:id="`note_content_` + note.id" v-show="edition_id != note.id">
                    <div class="media">
                        <img
                            v-bind:src="`<?= URL_UPLOADS ?>` + note.creator_url_thumbnail"
                            class="mr-3 rounded-circle w50p"
                            v-bind:alt="note.creator_display_name"
                        >
                        <div class="media-body">
                            <div class="dropdown float-right">
                                <button class="btn btn-light btn-sm" type="button" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" v-on:click="set_form(key)"><i class="fa fa-pencil-alt"></i> Editar</a>
                                    <a class="dropdown-item" v-on:click="set_current(key)" data-toggle="modal" data-target="#delete_modal"><i class="fa fa-trash"></i> Eliminar</a>
                                </div>
                            </div>
                            <p>
                                <a href="hola">{{ note.creator_display_name }}</a>
                                &middot;
                                <span class="text-muted" v-bind:title="note.created_at">{{ note.created_at | ago }}</span>
                            </p>
                            <p style="font-size: 1.1em;"><b>{{ note.post_name }}</b></p>
                            <p>
                                {{ note.excerpt }}
                            </p>
                            <p>
                                <span class="text-muted">Tipo</span>
                                <span class="text-info">{{ note.cat_1 | type_name }}</span>
                            </p>
                        </div>
                    </div>             
                </div>
                <div v-bind:id="`edition_note_` + note.id" class="card-body" v-show="edition_id == note.id"></div>
            </div>
        </div>
    </div>
    <?php $this->load->view('common/modal_single_delete_v') ?>
</div>

<?php $this->load->view('users/notes/vue_v') ?>