<div id="post_image_app" class="center_box_450">
    <div class="card">
        <img
            v-bind:src="post.url_image"
            class="card-img-top"
            alt="post image"
            onerror="this.src='<?= URL_IMG ?>app/nd.png'"
        >
        <div class="card-body">
            <div v-show="post.image_id == 0">
                <?php $this->load->view('common/upload_file_form_v') ?>
            </div>
            <div v-show="post.image_id > 0">
                <a class="btn btn-light" id="btn_crop" href="<?= base_url("posts/cropping/{$row->id}") ?>">
                    <i class="fa fa-crop"></i>
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#delete_modal">
                    <i class="fa fa-trash"></i>
                </button>
            </div>

        </div>
    </div>
    <?php $this->load->view('common/modal_single_delete_v') ?>
</div>

<script>
    new Vue({
        el: '#post_image_app',
        created: function(){
            //this.get_list();
        },
        data: {
            post: {
                id: <?= $row->id ?>,
                image_id: <?= $row->image_id ?>,
                url_image: '<?= $row->url_image ?>'
            },
            default_image: '<?= URL_IMG ?>app/nd.png'
        },
        methods: {
            send_file_form: function(){
                let form_data = new FormData();
                form_data.append('file_field', this.file);

                axios.post(url_api + 'posts/set_image/' + this.post.id, form_data, {headers: {'Content-Type': 'multipart/form-data'}})
                .then(response => {
                    //Cargar imagen
                    if ( response.data.status == 1 )
                    { 
                        this.post.image_id = response.data.image_id;
                        this.post.url_image = response.data.url_image;
                        window.location = url_app + 'posts/cropping/'+ this.post.id;
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
            handle_file_upload(){
                this.file = this.$refs.file_field.files[0];
            },
            delete_element: function(){
                axios.get(url_api + 'posts/remove_image/' + this.post.id)
                .then(response => {
                    if ( response.data.status == 1 ) {
                        this.post.image_id = 0;
                        this.post.url_image = this.default_image;
                        toastr['info']('Imagen eliminada');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>