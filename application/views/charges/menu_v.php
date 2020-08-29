<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['charges_info'] = '';
    $cl_nav_2['charges_groups'] = '';
    $cl_nav_2['charges_students'] = '';
    $cl_nav_2['charges_edit'] = '';
    //$cl_nav_2['charges_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf == 'charges/explore' ) { $cl_nav_2['charges_explore'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    var element_id = '<?= $row->id ?>';
    
    sections.explore = {
        icon: 'fa fa-arrow-left',
        text: 'Explorar',
        class: '<?= $cl_nav_2['charges_explore'] ?>',
        cf: 'charges/explore/',
        'anchor': true
    };

    sections.info = {
        icon: 'fa fa-info-circle',
        text: 'Información',
        class: '<?= $cl_nav_2['charges_info'] ?>',
        cf: 'charges/info/' + element_id
    };

    sections.students = {
        icon: 'fa fa-user',
        text: 'Estudiantes',
        class: '<?= $cl_nav_2['charges_students'] ?>',
        cf: 'charges/students/' + element_id
    };

    sections.groups = {
        icon: 'fa fa-users',
        text: 'Grupos',
        class: '<?= $cl_nav_2['charges_groups'] ?>',
        cf: 'charges/groups/' + element_id
    };

    sections.edit = {
        icon: 'fa fa-pencil-alt',
        text: 'Editar',
        class: '<?= $cl_nav_2['charges_edit'] ?>',
        cf: 'charges/edit/' + element_id
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['explore', 'info', 'groups', 'students', 'edit'];
    sections_rol.admn = ['explore', 'info'];
    sections_rol.prpt = ['explore', 'info', 'students', 'edit'];
    
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