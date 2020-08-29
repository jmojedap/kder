<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view('templates/admin_pml/parts/head') ?>
        <?php $this->load->view('templates/admin_pml/parts/routes_script_v') ?>
    </head>
    <body class="skin-dekinder">
        <div class="wrapper">

            <?php $this->load->view('templates/admin_pml/parts/header'); ?>
            <?php $this->load->view('templates/admin_pml/parts/sidebar'); ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <section class="content">
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
                </section>
            </div>
            
            <footer class="main-footer text-center">
                <small>
                    &copy; 2020 &middot; <a href="http://www.pacarina.com">Pacarina Media Lab</a> &middot; Colombia
                </small>
            </footer>
        </div><!-- ./wrapper -->
    </body>
</html>