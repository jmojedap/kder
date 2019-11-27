<?php
    $cl_nav_3['basic'] = '';
    $cl_nav_3['image'] = '';
    $cl_nav_3['password'] = '';

    $app_cf_index = $this->uri->segment(4);
    if ( strlen($app_cf_index) == 0 ) { $app_cf_index = 'basic'; }
    
    $cl_nav_3[$app_cf_index] = 'active';
    if ( $app_cf_index == 'cropping' ) { $cl_nav_3['image'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_3 = [];
    var sections_role = [];
    var element_id = '<?php echo $this->uri->segment(3) ?>';
    
    sections.basic = {
        'icon': '',
        'text': 'General',
        'class': '<?php echo $cl_nav_3['basic'] ?>',
        'cf': 'users/edit/' + element_id + '/basic'
    };
    
    sections.image = {
        'icon': 'fa fa-user-circle',
        'text': 'Imagen',
        'class': '<?php echo $cl_nav_3['image'] ?>',
        'cf': 'users/edit/' + element_id + '/image'
    };
    
    //Secciones para cada rol
    sections_role.dvlp = ['basic', 'image'];
    sections_role.admn = ['basic'];
    sections_role.prpt = ['basic', 'image'];
    sections_role.clnt = ['basic', 'image'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_role[app_r]) 
    {
        var key = sections_role[app_r][key_section];   //Identificar elemento
        nav_3.push(sections[key]);    //Agregar el elemento correspondiente
    }
</script>

<?php
$this->load->view('common/nav_3_v');