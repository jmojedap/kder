<script>
    /*var form_values = {
        post_name: 'Asunto por definir',
        excerpt: 'Este es el texto de la anotación, no tiene que ser tan larga pero si al menos dejar constancia de la situación, se hizo a las <?php echo date('Y-m-d H:i:s') ?>',
        cat_1: ''
    }*/
    var form_values = {
        post_name: '',
        excerpt: '',
        cat_1: '0910',
    }

// Variables
//-----------------------------------------------------------------------------
    var arr_types = <?php echo json_encode($arr_types); ?>;

// Filtros
//-----------------------------------------------------------------------------

    Vue.filter('ago', function (date) {
        if (!date) return ''
        return moment(date, "YYYY-MM-DD HH:mm:ss").fromNow();
    });

    Vue.filter('type_name', function (value) {
        if (!value) return '';
        value = arr_types[value];
        return value;
    });

    new Vue({
        el: '#app_notes',
        created: function(){
            this.get_list();
        },
        data: {
            row_key: -1,
            row_id: 0,
            edition_id: 0,
            list: [],
            num_page: 1,
            max_page: 1,
            user_id: '<?php echo $row->id ?>',
            form_values: form_values,
            search: {
                q: ''
            },
            search_num_rows: 0,
            show_add: false
        },
        methods: {
            get_list: function(){
                axios.post(app_url + 'notes/get/' + this.num_page, $('#search_form').serialize())
                .then(response => {
                    this.list = response.data.list;
                    this.max_page = response.data.max_page;
                    this.search_num_rows = response.data.search_num_rows;
                    console.log(this.num_page);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            sum_page: function(sum){
                this.num_page = Pcrn.limit_between(this.num_page + sum, 1, this.max_page);
                this.get_list();
            },
            //Cargar el formulario con datos de un elemento (key) de la list
            set_form: function (key){
                this.set_current(key);
                this.set_show_add(false);
                this.row_id = this.list[key].id;
                this.edition_id = this.list[key].id;
                
                //this.form_values = this.list[key];
                this.form_values.post_name = this.list[key].post_name;
                this.form_values.cat_1 = '0' + this.list[key].cat_1;
                this.form_values.excerpt = this.list[key].excerpt;
                
                $('#edition_note_' + this.row_id).append($("#note_form"));
                document.getElementById("field_post_name").focus();
            },
            send_form: function(){
                axios.post(app_url + 'notes/save/' + this.row_id, $('#note_form').serialize())
                .then(response => {
                    console.log(response.data);
                    
                    if ( response.data.status == 1 ) 
                    {
                        toastr['success']('Guardado');
                        this.get_list();
                        this.cancel_edition();
                        this.set_show_add(false);
                        this.row_id = response.data.saved_id;
                    }
                    
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            //Establece un elemento como el actual
            set_current: function(key) {
                this.row_id = this.list[key].id;
                this.row_key = key;
            },
            delete_element: function() {
                axios.get(app_url + 'notes/delete/' + this.row_id)
                .then(response => {
                    console.log(response.data);
                    if ( response.data.status == 1 )
                    {
                        $('#note_' + this.row_id).hide('slow');
                        toastr['info']('Eliminado');
                        this.search_num_rows -= 1;
                    }
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            clean_form: function() {
                $('#new_note').append($("#note_form"));
                for ( key in this.form_values ) {
                    this.form_values[key] = '';
                }
                this.form_values.cat_1 = '0410';
            },
            cancel_edition: function(){
                this.edition_id = 0;
                this.row_id = 0;
                this.row_key = -1;
                this.clean_form();
            },
            set_show_add: function(value){
                this.show_add = value;
                $('#new_note').append($("#note_form"));
            },
        }
    });
</script>