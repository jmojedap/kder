<?php
    $arr_colors = array(
        array('name' => 'info','background' => '#00c0ef', 'font_color' => '#FFFFFF'),
        array('name' => 'info hover','background' => '#0ab6e0', 'font_color' => '#FFFFFF'),
        array('name' => 'primary','background' => '#3E8EF7', 'font_color' => '#FFFFFF'),
        array('name' => 'primary hover','background' => '#589FFC', 'font_color' => '#FFFFFF'),
        array('name' => 'success','background' => '#11c26d', 'font_color' => '#FFFFFF'),
        array('name' => 'success hover','background' => '#28d17c', 'font_color' => '#FFFFFF'),
        array('name' => 'warning','background' => '#fdd835', 'font_color' => '#FFFFFF'),
        array('name' => 'warning hover','background' => '#f1cd2d', 'font_color' => '#FFFFFF'),
        array('name' => 'danger','background' => '#FF4C52', 'font_color' => '#FFFFFF'),
        array('name' => 'danger hover','background' => '#FF666B', 'font_color' => '#FFFFFF'),
        array('name' => 'dekinder','background' => '#008BEF', 'font_color' => '#FFFFFF'),
        array('name' => 'dekinder hover','background' => '#1899F5', 'font_color' => '#FFFFFF'),
        array('name' => 'dekinder_red','background' => '#E80F40', 'font_color' => '#FFFFFF'),
    );

    $arr_classes = array(
        'light',
        'info',
        'primary',
        'success',
        'warning',
        'danger',
        'secondary',
    );
?>

<div class="row">
    <div class="col-md-4">
        <table class="table bg-white">
            <thead>
                <th>Color</th>
                <th></th>
            </thead>
            <?php foreach ( $arr_colors as $color ) { ?>
                <tr>
                    <td><?= $color['name'] ?></td>
                    <td style="background-color: <?= $color['background'] ?>; color: <?= $color['font_color'] ?>"><?= $color['background'] ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div class="col-md-4">
        <?php foreach ( $arr_classes as $class ) { ?>
            <button class="btn btn-<?= $class ?> btn-block"><?= $class ?></button>
        <?php } ?>
    </div>
</div>