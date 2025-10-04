@extends(htmlHead)

<body class="josefin-sans-normal">
    @extends(appHeader)

    <main>
        <section class="buscador_subir_apunte">
            <div class="contenedor_buscador">
                <h2>Busca clases</h2>
                <input type="text" placeholder="Buscar..." class="input_buscador">
            </div>
            <div class="boton_subir_apunte">
                <button id="abrir_modal">Subir apunte</button>
            </div>
        </section>

        <h2 class="vistos_recientemente">Vistos recientemente</h2>
        <section class="contenedor_apuntes">
            {{ CLASES }}
        </section>
        @extends(modalSubirApunte)
    </main>
    <script src="views/static/js/modal.js"></script>
</body>

</html>