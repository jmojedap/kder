<?php $this->load->view('assets/jquery_autocomplete') ?>

<script>
  $( function() {
      
        $("#field-user").autocomplete({
            source: "<?php echo base_url('app/arr_elements/user') ?>",
            minLength: 3,
            select: function( event, ui ) { log(ui.item); }
        });

        function log(item) {
            var tr = '<tr>';
            tr += '<td>' + item.id + '</td>';
            tr += '<td>' + item.value + '</td>';
            tr += '</td>';
            console.log(tr);
            $(tr).prependTo("#table_body");
            //$( "#log" ).scrollTop( 0 );*/
        }
    } );
</script>

<input id="field-user" class="form-control" placeholder="Buscar usuario..."> 

<table class="table bg-white mt-2">
    <thead>
        <th>id</th>
        <th>value</th>
    </thead>
    <tbody id="table_body"></tbody>
</table>