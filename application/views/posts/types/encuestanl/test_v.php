<a href="<?= base_url("polls/build/{$row->id}") ?>" class="btn btn-light w120p">
    <i class="fa fa-arrow-left"></i>
    Construir
</a>

<div id="corpo_app" class=" center_box_750">
    <div class="card">
        <div class="card-body">
            <h2>{{ current.title }}</h2>
            <p>
                Respuesta: {{ answers }} &middot;
                Level: {{ level }}
            </p>
            <p>{{ current.description }}</p>
            <div class="d-flex justify-content-around mb-2">
                <button
                    class="btn btn-lg w150p"
                    v-bind:class="{'btn-light': ! scene.selected, 'btn-info': scene.selected }"
                    v-for="(scene, scene_key) in scenes"
                    v-show="scene.parent_id == current.id"
                    v-on:click="set_answer(scene.id, scene_key)"
                    >
                    {{ scene.title }}
                </button>
            </div>
            <img
                v-bind:src="current.url_image"
                class="w100pc"
                v-bind:alt="current.title"
                onerror="this.src='<?= URL_IMG ?>app/nd.png'"
                v-show="current.image_id > 0"
            >
        </div>
    </div>

    <div class="mt-2">
        <button class="btn btn-secondary w120p" v-show="current.parent_id > 0" v-on:click="set_scene(current.parent_id)">
            <i class="fa fa-arrow-left"></i> Atrás
        </button>
        <button class="btn btn-success w120p" v-show="level == max_level" v-on:click="save_answer">
            Guardar
        </button>
    </div>
</div>

<script>
    new Vue({
        el: '#corpo_app',
        created: function(){
            this.start();
        },
        data: {
            scenes: <?= $row->content_json ?>,
            current: {},
            answers: [0,0,0,0],
            level: 0,
            max_level: 4
        },
        methods: {
            start: function(){
                this.set_scene(1);
            },
            //Establercer respuesta en array answers
            set_answer: function(scene_id, scene_key){
                
                this.answers[this.level] = scene_id;
                this.clean_following();
                this.set_scene(scene_id);
                this.set_selected(scene_key);
            },
            //Establecer escena como la actual que se muestra
            set_scene: function(scene_id){
                this.current = this.scenes.find(scene => scene.id === scene_id);
                this.level = this.current.level;
            },
            //Marcar scene selected
            set_selected: function(scene_key){
                //Recorrer scenes
                this.scenes.forEach(element => {
                    //Desmarcar hermanos
                    if ( element.parent_id == this.current.parent_id ) { element.selected = false; }
                    //Desmarcar siguientes
                    if ( element.level > this.level ) { element.selected = false}
                });
                this.scenes[scene_key].selected = true;
            },
            //Borrar respuestas siguientes por ser dependientes
            clean_following: function(){
                for (let index = this.level + 1; index < this.answers.length; index++) {
                    this.answers[index] = 0;
                }
            },
            save_answer: function(){
                toastr['success']('Guardado');
            },
        }
    });
</script>