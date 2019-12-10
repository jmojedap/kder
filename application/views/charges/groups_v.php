<div id="groups_app">
    <table class="table bg-white">
        <thead>
            <th>Grupo</th>
            <th>Título</th>
            <th width="30px"></th>
        </thead>
        <tbody>
            <tr v-for="(group, key) in list">
                <td>{{ group.name }}</td>
                <td>{{ group.title }}</td>
                <td>
                    <button class="btn btn-success w120p" v-show="group.meta_id == 0" v-on:click="set_group(key)">
                        Agregar
                    </button>
                    <button class="btn btn-warning w120p" v-show="group.meta_id > 0" title="Quitar cobro a los estudiantes de este grupo" v-on:click="unset_group(key)">
                        <i class="fa fa-times"></i>
                        Quitar
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    new Vue({
        el: '#groups_app',
        created: function(){
            this.get_list();
        },
        data: {
            charge_id: '<?php echo $row->id ?>',
            group_id: 0,
            list: []
        },
        methods: {
            get_list: function(){
                axios.get(app_url + 'charges/get_groups/' + this.charge_id)
                .then(response => {
                    this.list = response.data.list;
                })
                .catch(function (error) {
                    console.log(error);
                });   
            },
            set_current: function(key){
                this.group_id = this.list[key].id;
            },
            set_group: function(key){
                this.set_current(key);
                axios.get(app_url + 'charges/set_group/' + this.charge_id + '/' + this.group_id)
                .then(response => {
                    console.log(response.data.message)
                    this.get_list();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            unset_group: function(key){
                this.set_current(key);
                var meta_id = this.list[key].meta_id;
                axios.get(app_url + 'charges/unset_group/' + this.charge_id + '/' + meta_id)
                .then(response => {
                    console.log(response.data.message)
                    this.get_list();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>