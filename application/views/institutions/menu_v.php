<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['institutions_info'] = '';
    $cl_nav_2['institutions_calendars'] = '';
    $cl_nav_2['institutions_edit'] = '';
    //$cl_nav_2['institutions_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf == 'institutions/explore' ) { $cl_nav_2['institutions_explore'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    var element_id = '<?php echo $row->id ?>';
    
    sections.info = {
        'icon': 'fa fa-shield-alt',
        'text': 'Información',
        'class': '<?php echo $cl_nav_2['institutions_info'] ?>',
        'cf': 'institutions/info/' + element_id
    };

    sections.calendars = {
        'icon': 'far fa-calendar',
        'text': 'Calendarios',
        'class': '<?php echo $cl_nav_2['institutions_calendars'] ?>',
        'cf': 'institutions/calendars/' + element_id
    };

    sections.edit = {
        'icon': 'fa fa-pencil-alt',
        'text': 'Editar',
        'class': '<?php echo $cl_nav_2['institutions_edit'] ?>',
        'cf': 'institutions/edit/' + element_id
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['info', 'calendars', 'edit'];
    sections_rol.admn = ['info'];
    sections_rol.prpt = ['info', 'edit'];
    
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