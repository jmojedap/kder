<?php
    $pct = number_format($step * 33.33, 0);
?>

<div class="progress mb-2">
    <div
        class="progress-bar"
        role="progressbar"
        style="width: <?php echo $pct ?>%;"
        aria-valuenow="<?php echo $pct ?>"
        aria-valuemin="0"
        aria-valuemax="100">
        Paso <?php echo $step ?>/3
    </div>
</div>