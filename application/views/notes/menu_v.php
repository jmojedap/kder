<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['notes_info'] = '';
    $cl_nav_2['notes_edit'] = '';
    $cl_nav_2['notes_image'] = '';
    //$cl_nav_2['notes_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'notes_cropping' ) { $cl_nav_2['notes_image'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    var element_id = '<?php echo $row->id ?>';
    
    sections.info = {
        'icon': 'fa fa-info-circle',
        'text': 'Información',
        'class': '<?php echo $cl_nav_2['notes_info'] ?>',
        'cf': 'notes/info/' + element_id
    };

    sections.edit = {
        'icon': 'fa fa-pencil-alt',
        'text': 'Editar',
        'class': '<?php echo $cl_nav_2['notes_edit'] ?>',
        'cf': 'notes/edit/' + element_id
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['info', 'edit'];
    sections_rol.admn = ['info'];
    sections_rol.mdlo = ['info', 'edit'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_r]) 
    {
        //console.log(sections_rol[rol][key_section]);
        var key = sections_rol[app_r][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
    
</script>

<?php
$this->load->view('common/nav_2_v');