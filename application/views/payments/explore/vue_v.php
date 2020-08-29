<script>
// Variables
//-----------------------------------------------------------------------------
    var charge_types = <?= json_encode($arr_types); ?>;

// Filters
//-----------------------------------------------------------------------------

    Vue.filter('currency', function (value) {
        if (!value) return '';
        value = '$ ' + new Intl.NumberFormat().format(value);
        return value;
    });

    Vue.filter('type_name', function (value) {
        if (!value) return '';
        value = charge_types[value];
        return value;
    })

// App
//-----------------------------------------------------------------------------

    new Vue({
        el: '#app_explore',
        created: function(){
            //this.get_list();
        },
        data: {
            cf: '<?= $cf; ?>',
            controller: '<?= $controller; ?>',
            num_page: 1,
            max_page: 1,
            list: <?= json_encode($list) ?>,
            element: [],
            selected: [],
            all_selected: false,
            filters: <?= json_encode($filters) ?>,
            showing_filters: false
        },
        methods: {
            get_list: function(){
                axios.post(url_app + this.controller + '/get/' + this.num_page, $('#search_form').serialize())
                .then(response => {
                    this.list = response.data.list;
                    this.max_page = response.data.max_page;
                    $('#head_subtitle').html(response.data.search_num_rows);
                    history.pushState(null, null, url_app + this.cf + this.num_page +'/?' + response.data.str_filters);
                    this.all_selected = false;
                    this.selected = [];
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            select_all: function() {
                this.selected = [];
                if (this.all_selected) {
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
                
                axios.post(url_app + this.controller + '/delete_selected', params)
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
                var url = url_app + 'payments/set_payed/' + this.element.id + '/' + this.element.charge_id + '/' + new_status;
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
            toggle_filters: function(){
                $('#adv_filters').toggle('fast');
            },
        }
    });
</script>