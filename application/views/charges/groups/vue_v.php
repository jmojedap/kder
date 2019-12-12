<script>
    Vue.filter('date_format', function (date) {
        if (!date) return ''
        return moment(date).format('DD/MMM HH:mm');
    });
    Vue.filter('ago', function (date) {
        if (!date) return ''
        return moment(date, "YYYY-MM-DD HH:mm:ss").fromNow();
    });
    Vue.filter('status_format', function (status) {
        if (!status) return '';
        var status_format = 'No';
        if ( status == 1 ) { status_format = 'Sí'; }
        return status_format;
    });

    new Vue({
        el: '#groups_app',
        created: function(){
            this.get_list();
        },
        data: {
            charge_id: '<?php echo $row->id ?>',
            list: [],
            group: [],
            group_id: 0,
            students: [],
            student: [],
            show_detail: false,
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
                this.group = this.list[key];
                this.group_id = this.list[key].id;
            },
            set_group: function(key){
                this.set_current(key);
                axios.get(app_url + 'charges/set_group/' + this.charge_id + '/' + this.group_id)
                .then(response => {
                    console.log(response.data.message)
                    if ( response.data.status == 1 ) {
                        this.get_list();
                        this.get_students();
                        toastr['success']('Cobro agregado a los estudiantes');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            unset_group: function(){
                var meta_id = this.group.meta_id;
                axios.get(app_url + 'charges/unset_group/' + this.charge_id + '/' + meta_id)
                .then(response => {
                    console.log(response.data.message)
                    if ( response.data.status == 1 ) {
                        this.get_list();
                        this.students = [];
                        toastr['info']('Cobro retirado');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            show_students: function(key){
                this.set_current(key);
                this.get_students();
                //this.show_detail = true;
            },
            hide_students: function(){
                this.show_detail = false;  
            },
            get_students: function(){
                axios.get(app_url + 'charges/get_students_group/' + this.charge_id + '/' + this.group.id)
                .then(response => {
                    this.students = response.data.students;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            set_student: function(key_student){
                this.student = this.students[key_student];
                console.log(this.student);
            },
            set_payed: function(key_student, payment_status){
                this.set_student(key_student);
                var url = app_url + 'payments/set_payed/' + this.student.payment_id + '/' + this.charge_id + '/' + payment_status;
                axios.get(url)
                .then(response => {
                    if ( response.data.status == 1 ) {
                        this.get_students();
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>