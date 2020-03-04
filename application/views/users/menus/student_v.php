<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['users_profile'] = '';
    $cl_nav_2['users_notes'] = '';
    $cl_nav_2['students_relatives'] = '';
    $cl_nav_2['users_edit'] = '';
    //$cl_nav_2['users_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf == 'users/explore' ) { $cl_nav_2['users_explore'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    var element_id = '<?php echo $row->id ?>';
    
    sections.explore = {
        'icon': 'fa fa-arrow-left',
        'text': 'Explorar',
        'class': '<?php echo $cl_nav_2['users_explore'] ?>',
        'cf': 'users/explore/'
    };

    sections.profile = {
        'icon': 'fa fa-user',
        'text': 'Perfil',
        'class': '<?php echo $cl_nav_2['users_profile'] ?>',
        'cf': 'users/profile/' + element_id
    };

    sections.notes = {
        'icon': 'far fa-sticky-note',
        'text': 'Anotaciones',
        'class': '<?php echo $cl_nav_2['users_notes'] ?>',
        'cf': 'users/notes/' + element_id
    };

    sections.relatives = {
        'icon': 'fa fa-heart',
        'text': 'Familia',
        'class': '<?php echo $cl_nav_2['students_relatives'] ?>',
        'cf': 'students/relatives/' + element_id
    };

    sections.edit = {
        'icon': 'fa fa-pencil-alt',
        'text': 'Editar',
        'class': '<?php echo $cl_nav_2['users_edit'] ?>',
        'cf': 'users/edit/' + element_id
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['explore', 'profile', 'notes', 'relatives', 'edit'];
    sections_rol.admn = ['profile'];
    sections_rol.prpt = ['profile', 'edit'];
    
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