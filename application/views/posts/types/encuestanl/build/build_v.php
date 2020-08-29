<?php
    $json_scenes = "[{id: 1, title: 'Escena inicial', description: 'Descripición inicial', image_id: 0, url_image: '', url_thumbnail: '', parent_id: 0, qty_childs: 0, level: 0, selected: true}]";
    if ( strlen($row->content_json) ) { $json_scenes = $row->content_json; }
?>

<div id="corpo_app">
    <div class="mb-2">
        <button class="btn btn-success w120p" v-on:click="save_poll">
            Guardar
        </button>
        <a href="<?= base_url("polls/test/{$row->id}") ?>" class="btn btn-light w120p">
            Responder
        </a>
    </div>
    <div class="row">
        <div class="col-md-5">
            <table class="table bg-white">
                <thead>
                    <th>ID</th>
                    <th>Escena/Opción</th>
                    <th>Level</th>
                    <th>Parent ID</th>
                    <th>Qty Childs</th>
                    <th width="10px"></th>
                </thead>
                <tbody>
                    <tr v-for="(scene, scene_key) in scenes" v-bind:class="{'table-info': scene.selected }">
                        <td>{{ scene.id }}</td>
                        <td>{{ scene.title }}</td>
                        <td>{{ scene.level }}</td>
                        <td>{{ scene.parent_id }}</td>
                        <td>{{ scene.qty_childs }}</td>
                        <td>
                            <button class="a4" v-on:click="set_scene(scene.id, scene_key)">
                                <i class="fa fa-arrow-right"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <h2>{{ current.title }} <small class="text-muted ml-3">{{ current.id }}</small></h2>
                    <ul class="nav nav-tabs mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="#" v-bind:class="{'active': edition_status == 'options' }" v-on:click="set_edition_status('options')">Opciones</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" v-bind:class="{'active': edition_status == 'main' }" v-on:click="set_edition_status('main')" href="#">Información</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" v-bind:class="{'active': edition_status == 'image' }" v-on:click="set_edition_status('image')" href="#">Imagen</a>
                        </li>
                    </ul>

                    <!-- Formulario escena actual -->
                    <div v-show="edition_status == 'main'">
                        <form accept-charset="utf-8" method="POST" @submit.prevent="send_scene_form">
                            <div class="form-group row">
                                <label for="title" class="col-md-4 col-form-label text-right">Título</label>
                                <div class="col-md-8">
                                    <input
                                        name="title" type="text" class="form-control" required
                                        v-model="current.title"
                                    >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label text-right">Descripción</label>
                                <div class="col-md-8">
                                    <textarea
                                        name="description" type="text" class="form-control" rows="5"
                                        title="Descripción"
                                        v-model="current.description"
                                    ></textarea>
                                </div>
                            </div>
                            <div class="form-group row d-none">
                                <div class="col-md-8 offset-md-4">
                                    <button class="btn btn-success w120p"> Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Opciones de la escena -->
                    <?php $this->load->view('posts/types/encuestanl/build/options_v') ?>

                    <!-- Imagen de la escena -->
                    <?php $this->load->view('posts/types/encuestanl/build/image_v') ?>

                </div>
            </div>

            <button class="btn btn-secondary mt-2" v-show="current.parent_id > 0" v-on:click="set_scene(current.parent_id)">
                <i class="fa fa-arrow-left"></i> Volver
            </button>
        </div>
    </div>
    <?php $this->load->view('common/modal_single_delete_v') ?>
</div>
<?php $this->load->view('posts/types/encuestanl/build/vue_v') ?>