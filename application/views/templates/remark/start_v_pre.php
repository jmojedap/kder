<!DOCTYPE html>
<html class="no-js css-menubar" lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="description" content="Aplicación web para administración de jardines infantiles y preescolar">
        <meta name="keywords" content="software, administracion, jardin infantil, app, aplicación, colombia">
        <meta name="author" content="Pacarina Media Lab">

        <title><?php echo $head_title ?></title>

        <link rel="apple-touch-icon" href="<?php echo URL_IMG ?>app/favicon.png">
        <link rel="shortcut icon" href="<?php echo URL_IMG ?>app/favicon.png">

        <!-- Estilos -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="<?php echo URL_RESOURCES ?>templates/remark/global/css/bootstrap-extend.min.css">
        <link rel="stylesheet" href="<?php echo URL_RESOURCES ?>templates/remark/base/assets/css/site.min.css">

        <!-- Adicionales -->
        <link rel="stylesheet" href="<?php echo URL_RESOURCES . 'css/start.css' ?>">

        <!-- Fonts -->
        <link rel="stylesheet" href="<?php echo URL_RESOURCES ?>templates/remark/global/fonts/web-icons/web-icons.min.css">
        <link rel="stylesheet" href="<?php //echo URL_PTL . 'global/fonts/brand-icons/brand-icons.min.css'?>">
         <link rel="stylesheet" href="<?php echo URL_RESOURCES ?>templates/remark/global/fonts/font-awesome/font-awesome.min.css?v4.0.2">
        <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
        
        <!-- Core  -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <!-- Vue.js -->
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
    </head>
    <body class="animsition page-register layout-full page-dark">
        
        <div class="container">
            <div class="row" style="margin-top: 25px;">
                <div class="col-md-4 offset-md-1 col-sm-12">
                    <div class="text-center inicio_marco">
                        <div class="brand">
                            <a href="<?php echo base_url() ?>">
                                <img class="brand-img" src="<?php echo URL_IMG ?>app/md_logo.png" alt="...">
                            </a>
                        </div>
                        
                        <div class="text-right">
                            <h3 class="text-white">
                                La herramienta para la administración fácil
                                de tu jardín infantil.
                            </h3>
                        </div>

                        <footer class="page-copyright page-copyright-inverse text-center" style="margin-top: 250px;">
                            <p>
                                Creado por 
                                <a href="http://www.pacarina.com" target="_blank">Pacarina Media Lab</a>
                                <br/>
                                <img src="<?php echo URL_IMG ?>app/bandera.png" alt="bandera">
                                Colombia
                            </p>
                            <p>© 2019 | Derechos Reservados</p>
                            <div class="social">
                                <a class="btn btn-icon btn-pure" href="javascript:void(0)">
                                    <i class="icon bd-twitter" aria-hidden="true"></i>
                                </a>
                                <a class="btn btn-icon btn-pure" href="javascript:void(0)">
                                    <i class="icon bd-facebook" aria-hidden="true"></i>
                                </a>
                                <a class="btn btn-icon btn-pure" href="javascript:void(0)">
                                    <i class="icon bd-instagram" aria-hidden="true"></i>
                                </a>
                            </div>
                        </footer>
                    </div>
                </div>
                <div class="col col-md-6">
                    <div class="inicio_marco text-center" style="width: 350px;">
                        <?php $this->load->view($view_a); ?>
                    </div>
                </div>
                
            </div>
        </div>
    </body>
</html>