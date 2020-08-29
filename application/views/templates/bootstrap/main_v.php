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
        <div class="container-fluid">
            <!--Hearder-->
            <div class="row">
                <div class="col-xl-12">
                    <div class="breadcrumb-holder" style="min-height: 47px">
                        <h1>
                            <span id="head_title">
                                <?= $head_title ?>
                            </span>
                            <span id="head_subtitle" style="margin-left: 10px; color: #999; font-weight: 300; font-size: 0.7em;">
                                <?= $head_subtitle ?>
                            </span>
                        </h1>
                    </div>
                </div>
            </div>
            
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
        
    </body>
</html>