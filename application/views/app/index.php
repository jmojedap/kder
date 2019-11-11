<?php
    $url = 'https://www.vebonit.com/bonitas/';
    $seconds = 0;
?>

<html>
<head>
    <title>VeBonito | Cargando</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <?= "<meta http-equiv='Refresh' content='{$seconds};{$url}'>" ?>
    
    <link href="<?= base_url() ?>css/style.css" rel="stylesheet" type="text/css" />
    
</head>

<body style="font-family: arial; font-size: 0.8em">
    <span class="suave">Procesando...</span>
    <br/>
    <span class="suave">
        <?= $msg_redirect; ?>
    </span>

</body>
</html>
