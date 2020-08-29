<script>
    var new_values = {
        subdomain: '',
        controller: '',
        function_name: '',
        description: '',
        roles: '',
        type_id: ''
    }
    
    new Vue({
        el: '#acl',
        created: function(){
            this.get_list();
        },
        data: {
            row_id: 0,
            subdomains: [
                { value: 'api', name: 'API'},
                { value: 'app', name: 'App'},
                { value: 'admin', name: 'Admin'}
            ],
            controllers: <?= json_encode($controllers->result()) ?>,
            form_values: {
                subdomain: '',
                controller: '',
                function_name: '',
                description: ''
            },
            list: [],
            controller: '<?= $controller ?>',
            subdomain: '<?= $subdomain ?>',
            config_form: {
                title: 'Nuevo proceso',
                button_text: 'Agregar',
                button_class: 'btn-primary'
            }
        },
        methods: {
            get_list: function (){
                axios.get(url_app + 'admin/acl_list/' + this.subdomain + '/' + this.controller)
                .then(response => {
                    this.list = response.data;
                    history.pushState(null, null, url_app + 'admin/acl/' + this.subdomain + '/' + this.controller);
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            change_subdomain: function() {
                this.clean_form();
                this.get_list();
            },
            change_controller: function() {
                this.clean_form();
                this.get_list();
            },
            load_form: function (key){
                this.row_id = this.list[key].id;
                this.form_values = this.list[key];
                this.config_form.title = 'Editar: ' + this.list[key].cf;
                this.config_form.button_text = 'Actualizar';
                this.config_form.button_class = 'btn-info';
                console.log('row_id: ' + this.row_id);
                //this.$refs.campo_id_interno.focus();
            },
            send_form: function(){
                axios.post(url_app + 'admin/acl_save/' + this.row_id, $('#acl_form').serialize())
                .then(response => {
                    console.log(response.data.status);
                    if ( response.data.status == 1 ) 
                    {
                        this.config_form.button_text = 'Guardado';
                        this.config_form.button_class = 'btn-success';
                        
                        if ( this.row_id > 0 ) {
                            toastr['success']('Cambios guardados');
                        } else {
                            toastr['success']('Elemento agregado');
                            this.row_id = response.data.row_id;
                            for ( key in this.form_values ) {
                                this.form_values[key] = '';
                            }
                        }
                        this.get_list();
                    }
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            current_element: function(key) {
                this.row_id = this.list[key].id;
                this.row_key = key;
                console.log(this.row_id);
            },
            delete_element: function() {
                axios.get(url_app + 'admin/acl_delete/' + this.row_id + '/' + this.controller)
                .then(response => {
                    console.log(response.data);
                    if ( response.data.status == 1 ){
                        this.list.splice(this.row_key, 1);
                        toastr['info']('Elemento eliminado');
                    }
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            clean_form: function() {
                this.row_id = 0;
                this.row_key = 0;
                this.config_form.title = 'Nueva función en: ' + this.controller;
                this.config_form.button_text = 'Agregar';
                this.config_form.button_class = 'btn-primary';
                this.form_values = new_values;
                //this.$refs.field_function_name.focus();
            }
        }
    });
</script>