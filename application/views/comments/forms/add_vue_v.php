<script>
    
    //var form_values = {};
    var form_values = {
        comment_text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris sollicitudin diam libero, vitae sodales felis pulvinar nec.'
    };
    
    /*var form_values = {
        comment_name: '',
        description: ''
    };*/
            
    new Vue({
        el: '#add_comment',
        data: {
            form_values: form_values,
            row_id: 0
        },
        methods: {
            send_form: function() {
                axios.post(url_app + 'comments/insert/', $('#add_form').serialize())
                .then(response => {
                    console.log('Resultado: ' + response.data.message);
                    if ( response.data.status == 1 )
                    {
                        this.row_id = response.data.comment_id;
                        this.clean_form();
                        $('#modal_created').modal();
                    }
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
            goto_created: function() {
                window.location = url_app + 'comments/info/' + this.row_id;
            }
        }
    });
</script>