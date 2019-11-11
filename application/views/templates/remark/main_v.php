<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
        
    //Sidebar, según el rol del usuario
        //$menubar = 'templates/remark/menus/menu_' . $this->session->userdata('role');
        $menubar = 'templates/remark/menus/menu_test';
?>
<!DOCTYPE html>
<html class="no-js css-menubar" lang="es">
    <head>
        <?php $this->load->view('templates/remark/parts/head'); ?>
        <?php $this->load->view('templates/remark/parts/routes_script_v'); ?>
    </head>
    <body class="animsition">
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <?php $this->load->view('templates/remark/parts/nav'); ?>
        <?php $this->load->view('templates/remark/parts/sidebar'); ?>

        <!-- Page -->
        <div class="page">
            <div class="page-content" id="page_content" style="padding: 10px 20px 10px 10px">
                <div id="nav_2">
                    <?php if ( ! is_null($nav_2) ) { ?>
                        <?php $this->load->view($nav_2); ?>
                    <?php } ?>
                </div>

                <div id="nav_3">
                    <?php if ( ! is_null($nav_3) ) { ?>
                        <?php $this->load->view($nav_3); ?>
                    <?php } ?>
                </div>

                <div id="view_a">
                    <?php $this->load->view($view_a); ?>
                </div>
            </div>
        </div>
        <!-- End Page -->

        <?php $this->load->view('templates/remark/parts/footer'); ?>
        <?php $this->load->view('templates/remark/parts/foot_scripts'); ?>
        
    </body>
</html>
