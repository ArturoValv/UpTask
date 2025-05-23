<div class="contenedor reestablecer">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nuevo password</p>

        <?php include_once __DIR__ . '/../templates/alertas.php' ?>

        <?php if ($mostrar): ?>

            <form class="formulario" method="POST">
                <div class="campo">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Tu password">
                </div>
                <input type="submit" value="Guardar Password" class="boton">
            </form>
            <div class="acciones">
                <a href="/crear">¿Aún no tienes una cuenta?</a>
                <a href="/olvide">¿Olvidaste tu password?</a>
            </div>

        <?php endif ?>
    </div>

</div>