@extends(htmlHead)

<body class="josefin-sans-normal">
    @extends(appHeader)

    <main>
        <section class="buscador_subir_apunte">
            <div class="contenedor_buscador">
                <h2>¡Hola, {{ PRIMER_NOMBRE_USUARIO }}!</h2>
                <input type="text" placeholder="Buscar..." class="input_buscador" id="input_buscador">
            </div>
            <div class="boton_subir_apunte">
                <button id="abrir_modal">Subir apunte</button>
            </div>
        </section>

        <h2 class="vistos_recientemente">Subidos recientemente</h2>
        <section id="contenedor_vistos_recientemente" class="contenedor_apuntes">
            {{ SUBIDOS_RECIENTEMENTE }}
        </section>
        <h2 class="para_ti">Para ti</h2>
        <section id="contenedor_para_ti" class="contenedor_apuntes">
            {{ PARA_TI }}
        </section>
        {{ MODAL_SUBIR_APUNTE }}
    </main>
    @extends(mobile_nav)
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- <script src="views/static/js/toast.js"></script> -->
    <!-- <script src="views/static/js/validacionFormulario.js"></script> -->
    <script type="module" src="views/static/js/inicioScript.js"></script>
    <script src="views/static/js/generalesScript.js"></script>
</body>

</html>