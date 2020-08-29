<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['accounts_profile'] = '';
    $cl_nav_2['accounts_edit'] = '';
    //$cl_nav_2['accounts_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf == 'accounts/explore' ) { $cl_nav_2['accounts_explore'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    var element_id = '<?= $row->id ?>';
    
    sections.profile = {
        icon: 'fa fa-user',
        text: 'Perfil',
        class: '<?= $cl_nav_2['accounts_profile'] ?>',
        cf: 'accounts/profile/'
    };

    sections.edit = {
        icon: 'fa fa-pencil-alt',
        text: 'Editar',
        class: '<?= $cl_nav_2['accounts_edit'] ?>',
        cf: 'accounts/edit/basic'
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['profile', 'edit'];
    sections_rol.admn = ['profile', 'edit'];
    sections_rol.edtr = ['profile', 'edit'];
    sections_rol.prpt = ['profile', 'edit'];
    sections_rol.regi = ['profile', 'edit'];
    
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