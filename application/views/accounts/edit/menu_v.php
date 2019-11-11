<?php
    $cl_nav_3['basic'] = '';
    $cl_nav_3['image'] = '';
    $cl_nav_3['password'] = '';

    $app_cf_index = $this->uri->segment(3);
    if ( strlen($app_cf_index) == 0 ) { $app_cf_index = 'basic'; }
    
    $cl_nav_3[$app_cf_index] = 'active';
    if ( $app_cf_index == 'crop' ) { $cl_nav_3['image'] = 'active'; }
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
        'cf': 'accounts/edit/basic'
    };
    
    sections.image = {
        'icon': 'fa fa-user-circle',
        'text': 'Imagen',
        'class': '<?php echo $cl_nav_3['image'] ?>',
        'cf': 'accounts/edit/image'
    };
    
    sections.password = {
        'icon': 'fa fa-lock',
        'text': 'Contraseña',
        'class': '<?php echo $cl_nav_3['password'] ?>',
        'cf': 'accounts/edit/password'
    };
    
    //Secciones para cada rol
    sections_role.dvlp = ['basic', 'image', 'password'];
    sections_role.admn = ['basic', 'image', 'password'];
    sections_role.edtr = ['basic', 'image', 'password'];
    sections_role.prpt = ['basic', 'image', 'password'];
    sections_role.clnt = ['basic', 'image', 'password'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_role[app_r]) 
    {
        var key = sections_role[app_r][key_section];   //Identificar elemento
        nav_3.push(sections[key]);    //Agregar el elemento correspondiente
    }
    
    //Si el perfil visitado es el mismo al de el usuario en sesión
    if ( element_id === '<?php echo $this->session->userdata('user_id'); ?>'  ) { nav_3.push(sections.password); }
</script>

<?php
$this->load->view('common/nav_3_v');