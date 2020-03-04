        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="description" content="Aplicación web para administración de jardines infantiles y preescolar">
        <meta name="keywords" content="software, administracion, jardin infantil, app, aplicación, colombia">
        <meta name="author" content="Pacarina Media Lab">
        
        <title><?php echo $head_title ?></title>

        <link rel="apple-touch-icon" href="<?php echo URL_IMG ?>app/favicon.png">
        <link rel="shortcut icon" href="<?php echo URL_IMG ?>app/favicon.png">

        <!-- Stylesheets -->        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="<?php echo URL_RESOURCES ?>templates/remark/global/css/bootstrap-extend.min.css">
        <link rel="stylesheet" href="<?php echo URL_RESOURCES ?>templates/remark/base/assets/css/site.min.css">
        <link rel="stylesheet" href="<?php echo URL_RESOURCES ?>css/style_pml.css">
        <link rel="stylesheet" href="<?php echo URL_RESOURCES ?>css/remark_pml.css">

        <!-- Plugins -->
        <link rel="stylesheet" href="<?php echo URL_RESOURCES ?>templates/remark/global/vendor/asscrollable/asScrollable.css">
        <link rel="stylesheet" href="<?php echo URL_RESOURCES ?>templates/remark/global/vendor/intro-js/introjs.css">
        <link rel="stylesheet" href="<?php echo URL_RESOURCES ?>templates/remark/global/vendor/slidepanel/slidePanel.css">

        <!-- Fonts -->
        <link rel="stylesheet" href="<?php echo URL_RESOURCES ?>templates/remark/global/fonts/web-icons/web-icons.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
        <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>

        <!--[if lt IE 9]>
        <script src="<?php echo URL_RESOURCES ?>templates/remark/global/vendor/html5shiv/html5shiv.min.js"></script>
        <![endif]-->

        <!--[if lt IE 10]>
        <script src="<?php echo URL_RESOURCES ?>templates/remark/global/vendor/media-match/media.match.min.js"></script>
        <script src="<?php echo URL_RESOURCES ?>templates/remark/global/vendor/respond/respond.min.js"></script>
        <![endif]-->

        <!-- Scripts -->
        <script src="<?php echo URL_RESOURCES ?>templates/remark/global/vendor/breakpoints/breakpoints.js"></script>
        <script>Breakpoints();</script>
        
        <script src="<?php echo URL_RESOURCES . 'templates/remark/global/vendor/jquery/jquery.js' ?>"></script>

        <!-- Vue.js -->
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>

        <?php $this->load->view('assets/toastr'); ?>