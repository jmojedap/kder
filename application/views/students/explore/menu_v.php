<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['students_explore'] = '';
    $cl_nav_2['students_add'] = '';
    $cl_nav_2['students_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf_index == 'students_import_e' ) { $cl_nav_2['students_import'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    
    sections.explore = {
        icon: 'fa fa-search',
        text: 'Explorar',
        class: '<?= $cl_nav_2['students_explore'] ?>',
        cf: 'students/explore'
    };

    sections.import = {
        icon: 'fa fa-upload',
        text: 'Importar',
        class: '<?= $cl_nav_2['students_import'] ?>',
        cf: 'students/import'
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['explore', 'import'];
    sections_rol.admn = ['explore', 'import'];
    sections_rol.edtr = ['explore', 'import'];
    sections_rol.prpt = ['explore', 'import'];
    
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