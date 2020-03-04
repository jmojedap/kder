<script>
    /*var random = '16073' + Math.floor(Math.random() * 100000);
    var form_values = {};
    var form_values = {
        first_name: 'Nancy',
        last_name: 'Suárez',
        display_name: 'Nancy Suárez',
        id_number: random,
        id_number_type: '01',
        username: 'nancy' + random,
        //username: '',
        birth_date: '1990-06-13',
        gender: '01'
    };*/
    
    var form_values = {
        first_name: '',
        last_name: '',
        display_name: '',
        id_number: '',
        id_number_type: '01',
        email: '',
        username: '',
        password: '',
        city_id: '',
        birth_date: '1990-01-01',
        gender: '01'
    };
    new Vue({
        el: '#app_relatives',
        created: function(){
            this.get_list();
        },
        data: {
            user_id: '<?php echo $row->id ?>',
            key: 0,
            gu_id: 0,
            list: [],
            show_form: false,
            form_values: form_values,
            relation_type: 1,
            validation: {
                id_number_is_unique: true,
                username_is_unique: true,
                email_is_unique: true
            },
        },
        methods: {
            get_list: function(){
                axios.get(app_url + 'students/get_relatives/' + this.user_id)
                .then(response => {
                    this.list = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });   
            },
            validate_send: function () {
                axios.post(app_url + 'users/validate/', $('#add_form').serialize())
                .then(response => {
                    if ( response.data.status == 1 ) {
                        this.send_form();
                    } else {
                        toastr['error']('Revise las casillas en rojo');
                    }
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            send_form: function() {
                axios.post(app_url + 'students/insert_relative/' + this.user_id + '/' + this.relation_type, $('#add_form').serialize())
                .then(response => {
                    console.log('status: ' + response.data.message);
                    if ( response.data.status == 1 )
                    {
                        this.get_list();
                        this.clean_form();
                        this.toggle_show_form();
                        toastr['success']('El usuario fue creado correctamente');
                    }
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            generate_username: function() {
                const params = new URLSearchParams();
                params.append('first_name', this.form_values.first_name);
                params.append('last_name', this.form_values.last_name);
                
                axios.post(app_url + 'users/username/', params)
                .then(response => {
                    this.form_values.username = response.data;
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            validate_form: function() {
                axios.post(app_url + 'users/validate/', $('#add_form').serialize())
                .then(response => {
                    //this.form_valido = response.data.status;
                    this.validation = response.data.validation;
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            clean_form: function() {
                for ( key in form_values ) {
                    this.form_values[key] = '';
                }
                $('#field-first_name').focus();
            },
            generate_display_name: function(){
                form_values.display_name = form_values.first_name + ' ' + form_values.last_name;
            },
            empty_generate_display_name: function(){
                if ( form_values.display_name.length < 2 ) { this.generate_display_name(); }
            },
            toggle_show_form: function(){
                this.show_form = ! this.show_form;
            },
            set_current: function(key){
                this.key = key;
            },
            remove_relative: function(){
                this.gu_id = this.list[this.key].gu_id;
                axios.get(app_url + 'users/remove_relative/' + this.user_id + '/' + this.list[this.key].id + '/' + this.gu_id)
                .then(response => {
                    var type = 'info';
                    if ( response.data.status == 1 ) { type = 'success' }
                    toastr[type](response.data.message);
                    this.get_list();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>