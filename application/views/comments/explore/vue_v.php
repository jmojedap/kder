<script>
    new Vue({
        el: '#explore_app',
        created: function(){
            this.get_list();
        },
        data: {
            num_page: 1,
            max_page: 1,
            search_num_rows: 0,
            list: [],
            show_filters: false,
            all_selected: false,
            selected: []
        },
        methods: {
            get_list: function (){
                axios.post(app_url + 'comments/get/' + this.num_page, $('#search_form').serialize())
                .then(response => {
                    this.list = response.data.list;
                    this.search_num_rows = response.data.search_num_rows;
                    this.max_page = response.data.max_page;
                    this.selected = [];
                    //$('#subtitulo_page').html(this.search_num_rows);
                    console.log(this.list.length);
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            search: function(){
                this.num_page = 1;
                this.get_list();
            },
            next_page: function(){
                this.num_page = Pcrn.limit_between(parseInt(this.num_page) + 1, 1, this.max_page);
                this.get_list();

            },
            prev_page: function(){
                this.num_page = Pcrn.limit_between(parseInt(this.num_page) - 1, 1, this.max_page);
                this.get_list();
            },
            toggle_filters: function(){
                this.show_filters = ! this.show_filters;
            },
            select_all: function() {
                this.selected = [];

                if ( ! this.all_selected) {
                    for (key in this.list) {
                        this.selected.push(this.list[key].id.toString());
                    }
                }
            },
            select: function () {
                this.all_selected = false;
            },
            delete_selected: function () {
                var params = new FormData();
                params.append('selected', this.selected);
                
                axios.post(app_url + 'comments/delete_selected/', params)
                .then(response => {
                    for ( key_s in this.selected ) {   //Recorrer selected
                        for ( key_u in this.list ) {       //Recorrer list elementos
                            if ( this.selected[key_s] == this.list[key_u].id.toString() ) {
                                this.list.splice(key_u, 1);
                            }
                        }
                    }
                    toastr['info'](this.selected.length + ' registros eliminados');
                    this.selected = [];
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            delete_row: function(key, row_id, document_id){
                axios.get(url_api + 'comments/delete/' + row_id + '/' + document_id)
                .then(response => {
                    console.log(response.data.message);
                    this.list.splice(key,1);
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        }
    });
</script>