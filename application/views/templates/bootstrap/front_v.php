<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>
<!doctype html>
<html lang="es">
    <head>
        <?php $this->load->view('templates/bootstrap/parts/head_v'); ?>
        <?php $this->load->view('templates/bootstrap/parts/routes_script_v'); ?>
    </head>
    <body>
        <?php $this->load->view('templates/bootstrap/parts/navbar_v'); ?>
        <div class="container">            
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
            
            <div id="view_b">
                <?php if ( ! is_null($view_b) ) { ?>
                    <?php $this->load->view($view_b); ?>
                <?php } ?>
            </div>
        </div>
        
    </body>
</html>