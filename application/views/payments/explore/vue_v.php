<script>
    new Vue({
        el: '#app_explore',
        created: function(){
            this.get_list();
        },
        data: {
            controller: '<?php echo $controller; ?>',
            num_page: 1,
            max_page: 1,
            list: [],
            element: [],
            selected: [],
            all_selected: false,
            filters: <?php echo json_encode($filters) ?>
        },
        methods: {
            get_list: function(){
                axios.post(app_url + this.controller + '/get/' + this.num_page, $('#search_form').serialize())
                .then(response => {
                    this.list = response.data.list;
                    this.max_page = response.data.max_page;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            select_all: function() {
                this.selected = [];
                if (!this.all_selected) {
                    for (element in this.list) {
                        this.selected.push(this.list[element].id);
                    }
                }
            },
            sum_page: function(sum){
                this.num_page = Pcrn.limit_between(this.num_page + sum, 1, this.max_page);
                this.get_list();
            },
            delete_selected: function(){
                var params = new FormData();
                params.append('selected', this.selected);
                
                axios.post(app_url + this.controller + '/delete_selected', params)
                .then(response => {
                    this.hide_deleted();
                    this.selected = [];
                    if ( response.data.status == 1 )
                    {
                        toastr_cl = 'info';
                        toastr_text = 'Registros eliminados';
                        toastr[toastr_cl](toastr_text);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            hide_deleted: function(){
                for (let index = 0; index < this.selected.length; index++) {
                    const element = this.selected[index];
                    console.log('ocultando: row_' + element);
                    $('#row_' + element).addClass('table-danger');
                    $('#row_' + element).hide('slow');
                }
            },
            set_current: function(key){
                this.element = this.list[key];
            },
            set_payed: function(key, new_status){
                this.set_current(key);
                var url = app_url + 'payments/set_payed/' + this.element.id + '/' + this.element.charge_id + '/' + new_status;
                axios.get(url)
                .then(response => {
                    if ( response.data.status == 1 )
                    {
                        this.list[key].status = new_status;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>