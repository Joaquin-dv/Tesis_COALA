@extends(htmlHead)

<body class="josefin-sans-normal">
    @extends(appHeader)

    <main>
            <section class="barra_superior">
                <section class="buscador_subir_apunte">
                    <div class="contenedor_buscador">
                        <input type="text" placeholder="Buscar..." class="input_buscador">
                    </div>
                </section>
                <section class="filtros">
                    @extends(botonesFiltro)
                </section>
            </section>

            <section class="contenedor_apuntes">
                {{ EXPLORAR }}
            </section>
    </main>
    @extends(mobile_nav)
    <script src="views/static/js/botonFiltro.js"></script>
</body>

</html>