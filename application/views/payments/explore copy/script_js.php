<script>    
// Variables
//-----------------------------------------------------------------------------
    var controller = '<?php echo $controller ?>';
    var str_filters = '<?php echo $str_filters ?>';
    var num_page = '<?php echo $num_page ?>';
    var max_page = '<?php echo $max_page ?>';
    var selected = '';
    var all_selected = '<?php echo $all_selected ?>';

    var payment_id = 0;
    var charge_id = 0;
        
// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function(){
        
        $('#search_form').submit(function(){
            num_page = 1;
            explore_table();
            return false;   //Evitar envío normal del formulario
        });

        $('#elements_table').on('change', '.check_row', function(){
            element_id = ',' + $(this).data('id');
            if( $(this).is(':checked') ) {
                selected += element_id;
            } else {  
                selected = selected.replace(element_id, '');
            }
            
            $('#selected').html(selected.substring(1));
        });

        $('#elements_table').on('change', '#check_all', function(){
            
            if($(this).is(":checked")) { 
                //Activado
                $('.check_row').prop('checked', true);
                selected = all_selected;
            } else {
                //Desactivado
                $('.check_row').prop('checked', false);
                selected = '';
            }
            
            $('#selected').html(selected.substring(1));
        });

        $('#btn-delete_selected').click(function(){
            delete_selected();
        });
        
        $('.not_filtered').hide();
        $('.b_avanzada_no').hide();
        
        $('#alternar_avanzada').click(function(){
            $('.not_filtered').toggle('fast');
            $('.b_avanzada_si').toggle();
            $('.b_avanzada_no').toggle();
        });

        $('#field-num_page').change(function(){
            num_page = $(this).val();
            explore_table();
        });
        
        $('#btn_explorar_next').click(function()
        {
            num_page = Pcrn.limit_between(parseInt(num_page) + 1, 1, max_page);
            explore_table();
        });
        
        $('#btn_explorar_prev').click(function()
        {
            num_page = Pcrn.limit_between(parseInt(num_page) - 1, 1, max_page);
            explore_table();
        });

        $('.btn_more').click(function(){
            $('.more').hide();
            var row_id = $(this).data('row_id');
            console.log(row_id);
            $('#more_' + row_id).show('slow');
        });

        $('#elements_table').on('click', '.btn_set_payed', function(){
            console.log('actualizando');
            payment_id = $(this).data('payment_id');
            charge_id = $(this).data('charge_id');
            payment_status = $(this).data('payment_status');

            set_payed(payment_status);
        });
    });

// Funciones
//-----------------------------------------------------------------------------

    //Actualizar la tabla explorar al cambiar de página
    function explore_table()
    {
        $.ajax({        
            type: 'POST',
            url: app_url + controller + '/explore_table/' + num_page + '/?' + str_filters,
            data: $("#search_form").serialize(),
            beforeSend: function(){
                $('#elements_table').html('<div class="text-center"><i class="text-center fa fa-spinner fa-spin fa-2x"></i></div>');
            },
            success: function(response){
                load_results(response);
            }
        });
    }

    /**
     * Después de obtener los datos de búqueda, se actualizan los elementos
     * de la página.
     */
    function load_results(response)
    {
        $('#elements_table').html(response.html);
        $('#head_subtitle').html(response.search_num_rows);
        $('#field-num_page').val(parseInt(num_page));
        $('#field-num_page').prop('title', parseInt(num_page) + ' páginas en total');

        all_selected = response.all_selected;
        num_page = response.num_page;
        max_page = response.max_page;
        selected = '';
        
        history.pushState(null, null, app_url + controller + '/explore/' + num_page + '/?' + response.str_filters);
    }

    //AJAX - Eliminar elementos selected.
    function delete_selected()
    {
        $.ajax({        
            type: 'POST',
            url: app_url + controller + '/delete_selected/',
            data: {
                selected : selected.substring(1)
            },
            success: function(response){
                console.log(response.message);
                if ( response.status == 1 ) {
                    hide_deleted();
                }
            }
        });
    }

    //Oculta las filas de los registros eliminados
    function hide_deleted()
    {
        var arr_deleted = selected.substring(1).split(',');
        for ( key in arr_deleted )
        {
            $('#row_' + arr_deleted[key]).addClass('table-danger');
            $('#row_' + arr_deleted[key]).hide('slow');
            console.log('#row_' + arr_deleted[key]);
        }
    }

    function set_payed(payment_status){
        $.ajax({        
            type: 'POST',
            url: app_url + 'payments/set_payed/' + payment_id + '/' + charge_id + '/' + payment_status,
            success: function(response){
                if ( response.status == 1 ) {
                    explore_table();
                }
            }
        });
    }
</script>