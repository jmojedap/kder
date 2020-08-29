<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['files_info'] = '';
    $cl_nav_2['files_cropping'] = '';
    $cl_nav_2['files_change'] = '';
    $cl_nav_2['files_edit'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf == 'files/explore' ) { $cl_nav_2['files_explore'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    var element_id = '<?= $file_id ?>';
    
    sections.explore = {
        icon: 'fa fa-arrow-left',
        text: 'Explorar',
        class: '<?= $cl_nav_2['files_explore'] ?>',
        cf: 'files/explore/',
        anchor: true
    };

    sections.info = {
        icon: 'fa fa-info-circle',
        text: 'Información',
        class: '<?= $cl_nav_2['files_info'] ?>',
        cf: 'files/info/' + element_id
    };

    sections.cropping = {
        icon: 'fa fa-crop',
        text: 'Recortar',
        class: '<?= $cl_nav_2['files_cropping'] ?>',
        cf: 'files/cropping/' + element_id
    };

    sections.change = {
        icon: 'fa fa-exchange-alt',
        text: 'Cambiar',
        class: '<?= $cl_nav_2['files_change'] ?>',
        cf: 'files/change/' + element_id
    };

    sections.edit = {
        icon: 'fa fa-pencil-alt',
        text: 'Editar',
        class: '<?= $cl_nav_2['files_edit'] ?>',
        cf: 'files/edit/' + element_id
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['explore', 'info', 'cropping', 'change', 'edit'];
    sections_rol.admn = ['explore', 'info', 'cropping', 'change', 'edit'];
    
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