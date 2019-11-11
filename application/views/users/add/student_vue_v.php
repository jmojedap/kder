<script>
    var random = '16073' + Math.floor(Math.random() * 100000);
    var form_values = {};
    var form_values = {
        first_name: 'Diana María',
        last_name: 'González Rondón',
        display_name: 'Diana María González Rondón',
        id_number: random,
        id_number_type: '06',
        username: 'anna' + random,
        //username: '',
        birth_date: '2015-06-10',
        gender: '01'
    };
    
    /*var form_values = {
        first_name: '',
        last_name: '',
        display_name: '',
        id_number: '',
        id_number_type: '06',
        email: '',
        username: '',
        password: '',
        city_id: '',
        birth_date: '2016-01-01',
        role: '015',
        gender: '01'
    };*/
            
    new Vue({
        el: '#add_user',
        data: {
            form_values: form_values,
            validation: {
                id_number_is_unique: true,
                username_is_unique: true,
                email_is_unique: true
            },
            row_id: 0
        },
        methods: {
            validate_send: function () {
                axios.post(app_url + 'users/validate_row/', $('#add_form').serialize())
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
                axios.post(app_url + 'users/insert/', $('#add_form').serialize())
                .then(response => {
                    console.log('status: ' + response.data.message);
                    if ( response.data.status == 1 )
                    {
                        this.row_id = response.data.saved_id;
                        this.clean_form();
                        $('#modal_created').modal();
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
                axios.post(app_url + 'users/validate_row/', $('#add_form').serialize())
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
            },
            go_created: function() {
                window.location = app_url + 'users/profile/' + this.row_id;
            },
            generate_display_name: function(){
                form_values.display_name = form_values.first_name + ' ' + form_values.last_name;
            },
            empty_generate_display_name: function(){
                if ( form_values.display_name.length < 2 ) { this.generate_display_name(); }
            }
        }
    });
</script>