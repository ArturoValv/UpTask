<div class="contenedor login">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesión</p>

        <?php include_once __DIR__ . '/../templates/alertas.php' ?>

        <form action="/" class="formulario" method="POST">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Tu Email">
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Tu password">
            </div>
            <input type="submit" value="Iniciar Sesión" class="boton">
        </form>
        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta?</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>
    </div>

</div>