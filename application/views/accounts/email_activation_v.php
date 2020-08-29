<?php
    $texts['title'] = 'Bienvenido a ' . APP_NAME;
    $texts['paragraph'] = 'Para activar su cuenta haga clic en el siguiente enlace:';
    $texts['button'] = 'Activar mi cuenta';
    $texts['link'] = "accounts/activation/{$row_user->activation_key}";
    
    if ( $activation_type == 'recovery' ) 
    {
        $texts['title'] = APP_NAME;
        $texts['paragraph'] = 'Para restaurar su contraseña haga clic en el siguiente enlace:';
        $texts['button'] = 'Restaurar mi contraseña';
        $texts['link'] = "accounts/activation/{$row_user->activation_key}/recovery";
    }
?>
<div style="font-family: Arial; text-align: center">
    <h1 style="color: #E2061D"><?= $texts['title'] ?></h1>
    <h3><?= $row_user->first_name . ' ' . $row_user->last_name ?></h3>
    <p><?= $texts['paragraph'] ?></p>
    <?= anchor($texts['link'], $texts['button'], 'style="" target="_blank"') ?>
    <p style="margin-top: 50px; color: #AAAAAA; font-size: 0.7em">
        Creado por VeBonit | Colombia
    </p>
</div>