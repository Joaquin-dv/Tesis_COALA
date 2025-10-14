@extends(htmlHead)
<script>
    // Pasar el rol del usuario a JavaScript
    window.userRole = '{{ USER_ROLE }}';
</script>
<body class="josefin-sans-normal">
    @extends(appHeader)

    <main>
            <section class="barra_superior">
                <section class="buscador_subir_apunte">
                    <div class="contenedor_buscador">
                        <input type="text" placeholder="Buscar..." class="input_buscador" id="input_buscador">
                    </div>
                </section>
                <section class="filtros">
                    @extends(botonesFiltro)
                </section>
            </section>

            <section class="contenedor_apuntes" id="contenedor_apuntes">
                {{ EXPLORAR }}
            </section>
    </main>
    @extends(mobile_nav)
    <script src="views/static/js/botonFiltro.js"></script>
    <script type="module" src="views/static/js/inicioScript.js"></script>
    <script src="views/static/js/generalesScript.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="views/static/js/modules/toastModule.js"></script>

</body>

</html>