<!doctype html>
<html lang="es">
    <head>
        <?php $this->load->view('templates/bootstrap/parts/head_v'); ?>
        <link rel="stylesheet" href="<?php echo base_url('resources/templates/bootstrap/css/start.css') ?>">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.css" integrity="sha256-a2tobsqlbgLsWs7ZVUGgP5IvWZsx8bTNQpzsqCSm5mk=" crossorigin="anonymous" />
    </head>
    <body>
        <div class="container-fluid" id="start_content">
            <a href="<?php echo base_url() ?>" class="">
                <img src="<?php echo URL_IMG . 'app/start_logo.png' ?>" alt="logo app" class="animated zoomIn">
            </a>
            <?php $this->load->view($view_a); ?>
        </div>
    </body>
</html>