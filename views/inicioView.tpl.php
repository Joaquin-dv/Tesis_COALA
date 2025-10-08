@extends(htmlHead)

<body class="josefin-sans-normal">
    @extends(appHeader)

    <main>
        <section class="buscador_subir_apunte">
            <div class="contenedor_buscador">
                <h2>¡Hola, Pepe!</h2>
                <input type="text" placeholder="Buscar..." class="input_buscador">
            </div>
            <div class="boton_subir_apunte">
                <button id="abrir_modal">Subir apunte</button>
            </div>
        </section>

        <h2 class="vistos_recientemente">Subidos recientemente</h2>
        <section id="contenedor_vistos_recientemente" class="contenedor_apuntes">
            
        </section>
        <h2 class="para_ti">Para ti</h2>
        <section id="contenedor_para_ti" class="contenedor_apuntes">
            
        </section>
        {{ MODAL_SUBIR_APUNTE }}
    </main>
    <script src="views/static/js/inicio.js"></script>
    <script src="views/static/js/modal.js"></script>
    <!-- <script src="views/static/js/validacionFormulario.js"></script> -->
    <script src="views/static/js/generalesScript.js"></script>
</body>

</html>