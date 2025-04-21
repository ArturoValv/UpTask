<?php include_once __DIR__ . '/header-dashboard.php' ?>


<div class="contenedor-sm">

    <?php include_once __DIR__ . '/../templates/alertas.php' ?>

    <a href="/perfil" class="enlace">Volver al Perfil</a>

    <form method="POST" class="formulario" action="/cambiar-password">

        <div class="campo">
            <label for="password">Password Actual:</label>

            <input type="password" name="password_actual" id="" value="" placeholder="Tu Password Actual">
        </div>

        <div class="campo">
            <label for="email">Password Nuevo:</label>

            <input type="password" name="password_nuevo" id="" value="" placeholder="Tu password nuevo">
        </div>

        <input type="submit" value="Guardar Cambios">

    </form>

</div>


<?php include_once __DIR__ . '/footer-dashboard.php' ?>