<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php $this->load->view('templates/remark/parts/head') ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" integrity="sha256-PHcOkPmOshsMBC+vtJdVr5Mwb7r0LkSVJPlPrp/IMpU=" crossorigin="anonymous" />
        <link rel="stylesheet" href="<?php echo URL_RESOURCES ?>css/start.css">
    </head>

    <body>
        <div style="height: 12%" class="d-none d-lg-block"></div>
        <div class="start_container">
            <img src="<?php echo URL_IMG . 'app/start_logo.png' ?>" alt="Logo DeKinder" class="mb-3 animated zoomIn">
            <?php $this->load->view($view_a); ?>
        </div>
        <div class="fixed-bottom text-center pb-2">
            <span style="color: #FFFFFF">
                © 2020 &middot; Pacarina Media Lab &middot; Colombia
            </span>
        </div>
        
        <?php //$this->load->view('templates/remark/parts/footer_scripts_v') ?>
    </body>
</html>
