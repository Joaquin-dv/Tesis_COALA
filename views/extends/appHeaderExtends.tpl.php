<header>
    <div class="contenedor_logo_secundario">
        <img src="/views/static/img/branding/logo_secundario.png" alt="">
    </div>

    <nav class="barra_navegacion">
        <a href="?slug=inicio">Inicio</a>
        <a href="?slug=explorar">Explorar</a>
        <a href="?slug=mochila">Mochila</a>
        <!-- <a href="?slug=clases">Clases</a> -->
    </nav>


    <section class="perfil">
        <img id="perfil-img" src="https://api.dicebear.com/9.x/initials/svg?seed={{ NOMBRE_USUARIO }}" alt="avatar" />
        <ul id="menu-desplegable" class="oculto">
            <!-- <li><a href="?slug=perfil">Perfil</a></li> -->
            <li><a href="?slug=logout">Cerrar sesión</a></li>
        </ul>
    </section>
</header>