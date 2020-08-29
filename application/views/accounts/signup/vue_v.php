<script>
    new Vue({
        el: '#signup_app',
        data: {
            type: 0,
            validation: {
                email_is_valid: true,
                email_is_unique: true,
                email_is_gmail: true,
                username_is_unique: true
            },
            validated: 0
        },
        methods: {
            update_type: function(type){
                this.type = type;
            },
            register: function(){
                if ( this.validated ) {
                    
                    axios.post(url_api + 'accounts/register/', $('#signup_form').serialize())
                    .then(response => {
                        console.log(response.data.message);
                        if ( response.data.status == 1 ) {
                            window.location = url_app + 'accounts/registered/' + response.data.user_id;
                        } else {
                            this.recaptcha_message = response.data.recaptcha_message;
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }
            },
            validate_form: function(){
                var form_data = $('#signup_form').serialize();
                
                axios.post(url_api + 'accounts/validate_signup', form_data)
                .then(response => {
                    this.validated = response.data.status;
                    this.validation = response.data.validation;
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        }
    });
</script>