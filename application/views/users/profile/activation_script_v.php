<script>
// Variables
//-----------------------------------------------------------------------------
    user_id = '<?php echo $row->id ?>';

// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function(){
        $('#btn_set_activation_key').click(function(){
            set_activation_key();
        });
    });

// Functions
//-----------------------------------------------------------------------------

    function set_activation_key(){
        $.ajax({        
            type: 'POST',
            url: app_url + 'users/set_activation_key/' + user_id,
            success: function(response){
                $('#activation_key').html(app_url + 'accounts/activation/' + response);
                toastr['success']('Se actualizó la clave de activación');
            }
        });
    }
</script>