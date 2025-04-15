<?php
if (!empty($alertas)):
    foreach ($alertas as $key => $alerta):
        foreach ($alerta as $mensaje):
?>

            <div class="alerta <?= $key ?>">
                <?= $mensaje ?>
            </div>

<?php
        endforeach;
    endforeach;
endif;
?>