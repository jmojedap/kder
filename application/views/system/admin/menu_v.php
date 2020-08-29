<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['admin_options'] = '';
    $cl_nav_2['admin_acl'] = '';
    $cl_nav_2['admin_colors'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf == 'documents/structure' ) { $cl_nav_2['documents_info'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    
    sections.options = {
        icon: 'fa fa-cog',
        text: 'Opciones',
        class: '<?= $cl_nav_2['admin_options'] ?>',
        cf: 'admin/options/'
    };

    sections.acl = {
        icon: 'fa fa-users',
        text: 'Permisos',
        class: '<?= $cl_nav_2['admin_acl'] ?>',
        cf: 'admin/acl/'
    };

    sections.colors = {
        icon: 'fas fa-tint',
        text: 'Colores',
        class: '<?= $cl_nav_2['admin_colors'] ?>',
        cf: 'admin/colors/'
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['options', 'acl', 'colors'];
    sections_rol.admn = ['options', 'acl', 'colors'];
    
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