<?php
    $cl_nav_3['institutional'] = '';
    $cl_nav_3['student'] = '';

    $app_cf_index = $this->uri->segment(3);
    if ( strlen($app_cf_index) == 0 ) { $app_cf_index = 'institutional'; }
    
    $cl_nav_3[$app_cf_index] = 'active';
    if ( $app_cf_index == 'crop' ) { $cl_nav_3['image'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_3 = [];
    var sections_role = [];
    //var element_id = '<?php //echo $this->uri->segment(3) ?>';
    
    sections.institutional = {
        icon: '',
        text: 'Institucional',
        class: '<?= $cl_nav_3['institutional'] ?>',
        cf: 'users/add/institutional'
    };

    sections.student = {
        icon: '',
        text: 'Estudiante',
        class: '<?= $cl_nav_3['student'] ?>',
        cf: 'users/add/student'
    };
    
    //Secciones para cada rol
    sections_role.dvlp = ['institutional'];
    sections_role.admn = ['institutional'];
    sections_role.edtr = ['institutional'];
    sections_role.prpt = ['institutional'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_role[app_r]) 
    {
        var key = sections_role[app_r][key_section];   //Identificar elemento
        nav_3.push(sections[key]);    //Agregar el elemento correspondiente
    }
</script>

<?php
$this->load->view('common/nav_3_v');