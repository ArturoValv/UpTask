<div class="sidebar">

    <div class="contenedor-sidebar">
        <h2>UpTask</h2>

        <div class="cerrar-menu">
            <img src="build/img/cerrar.svg" alt="imagen cerrar menu" id="cerrar-menu">
        </div>
    </div>

    <nav class="sidebar-nav">
        <a class="<?= $titulo === 'Proyectos' ? 'activo' : '' ?>" href="/dashboard">Dashboard</a>
        <a class="<?= $titulo === 'Crear Proyecto' ? 'activo' : '' ?>" href="/crear-proyecto">Crear Proyectos</a>
        <a class="<?= $titulo === 'Perfil' ? 'activo' : '' ?>" href="/perfil">Perfil</a>
    </nav>

    <div class="cerrar-sesion-mobile">
        <a href="/logout" class="cerrar-sesion">Cerrar Sesión</a>
    </div>

</div>