<script>
    new Vue({
        el: '#corpo_app',
        created: function(){
            this.start();
        },
        data: {
            post_id: <?= $row->id ?>,
            scenes: <?= $scenes ?>,
            current: {},
            level: 0,
            form_values:{title: ''},
            deleteable: {
                key: 0
            },
            edition_status: 'options'
        },
        methods: {
            start: function(){
                this.set_scene(1,0);
            },
            //Establecer una escena como la actual
            set_scene: function(scene_id, scene_key){
                this.current = this.scenes.find(scene => scene.id === scene_id);
                this.set_selected(scene_key);
            },
            //Marcar scene selected
            set_selected: function(scene_key){
                //Recorrer scenes
                this.scenes.forEach(element => {
                    element.selected = false;
                });
                this.scenes[scene_key].selected = true;
            },
            //Agregar una opción (escena hijo) a la escena actual
            send_child_form: function(){
                var new_scene = {
                    id: this.current.id * 10 + this.current.qty_childs + 1,
                    title: this.form_values.title,
                    description: '',
                    image_id: 0,
                    url_image: '',
                    url_thumbnail: '',
                    parent_id: this.current.id,
                    qty_childs: 0,
                    level: this.current.level + 1,
                    selected: false
                };
                this.scenes.push(new_scene);
                this.current.qty_childs += 1;
                this.clean_form();
            },
            clean_form: function(){
                this.form_values.title = '';
            },
            //Guardar cambios del registro de la encuesta en la tabla post
            save_poll: function(){
                const params = new URLSearchParams();
                params.append('content_json', JSON.stringify(this.scenes));
                axios.post(url_api + 'posts/update/' + this.post_id, params)
                .then(response => {
                    if ( response.data.status == 1) {
                        toastr['success']('Encuesta guardada');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
            //Pre - Marcar una escena como la eliminable, antes de confirmar
            set_deleteable: function(scene_key){
                this.deleteable = this.scenes[scene_key];
                this.deleteable.key = scene_key;
            },
            //Eliminar una escena
            delete_element: function(){
                //Longitud del ID del elemento a eliminar, para reconocer hijos
                var parent_length = this.deleteable.id.toString().length;
                //Eliminar descendientes
                var new_scenes = this.scenes.filter((item) => {
                    //Comparar raiz de parent_id
                    var parent_root = item.parent_id.toString().substr(0,parent_length);
                    return parent_root != this.deleteable.id;
                });

                //Eliminar elemento
                new_scenes = new_scenes.filter((item) => {
                    return item.id != this.deleteable.id;
                });

                //Establecer escenas ya filtradas
                this.scenes = new_scenes;

                //Restar qty_childs, a la escena padre de la escena eliminada
                var parent_key = this.scenes.findIndex(element => element.id == this.deleteable.parent_id);
                this.scenes[parent_key].qty_childs -= 1;

                toastr['info']('Rama eliminada');
            },
            //Establecer la sección de edición de la escena
            set_edition_status: function(edition_status){
                this.edition_status = edition_status;
            },
            //Enviar formulario de imagen
            send_file_form: function(){
                let form_data = new FormData();
                form_data.append('file_field', this.file);

                axios.post(url_api + 'files/upload/', form_data, {headers: {'Content-Type': 'multipart/form-data'}})
                .then(response => {
                    //Cargar imagen
                    if ( response.data.status == 1 )
                    {   //Se asigna la escena actual
                        this.current.image_id = response.data.row.id;
                        this.current.url_image = response.data.row.url;
                        this.current.url_thumbnail = response.data.row.url_thumbnail;

                        this.save_poll();
                    }
                    //Mostrar respuesta html, si existe
                    if ( response.data.html ) { $('#upload_response').html(response.data.html); }
                    //Limpiar formulario
                    $('#field-file').val(''); 
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            //Cargar imagen en variable this.file
            handle_file_upload(){
                this.file = this.$refs.file_field.files[0];
            },
            //Eliminar la imagen de una escena
            delete_image: function(){
                console.log('delete', this.current.image_id);
                axios.get(url_api + 'files/delete/' + this.current.image_id)
                .then(response => {
                    if ( response.data.qty_deleted > 0 )
                    {
                        this.current.image_id = 0;
                        this.current.url_image = '';
                        this.current.url_thumbnail = '';
                    }
                    this.save_poll();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>