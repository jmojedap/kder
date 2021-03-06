<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['groups_info'] = '';
    $cl_nav_2['groups_students'] = '';
    $cl_nav_2['groups_edit'] = '';
    //$cl_nav_2['groups_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf == 'groups/explore' ) { $cl_nav_2['groups_explore'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    var element_id = '<?= $row->id ?>';
    
    sections.info = {
        icon: 'fa fa-info-circle',
        text: 'Información',
        class: '<?= $cl_nav_2['groups_info'] ?>',
        cf: 'groups/info/' + element_id
    };

    sections.students = {
        icon: 'fa fa-users',
        text: 'Estudiantes',
        class: '<?= $cl_nav_2['groups_students'] ?>',
        cf: 'groups/students/' + element_id
    };

    sections.edit = {
        icon: 'fa fa-pencil-alt',
        text: 'Editar',
        class: '<?= $cl_nav_2['groups_edit'] ?>',
        cf: 'groups/edit/' + element_id
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['info', 'students', 'edit'];
    sections_rol.admn = ['info'];
    sections_rol.prpt = ['info', 'students', 'edit'];
    
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