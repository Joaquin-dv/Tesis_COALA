@extends(htmlHead)

<body class="josefin-sans-normal">
    @extends(appHeader)

    <main>
            <section class="barra_superior">
                <nav class="sidebar_lateral">
                    <input type="checkbox" id="sidebar_activo">
                    <label for="sidebar_activo" class="abrir_sidebar">
                        <i class="fa-solid fa-bars"></i>
                    </label>
                    <div class="escuelas_materias">
                    <label for="sidebar_activo" class="cerrar_sidebar">
                        <i class="fa-solid fa-x"></i>
                    </label>

                    <span class="escuela">Roberto Arlt</span>
                        <a href="#" class="materia">Literatura</a>
                        <a href="#" class="materia">Matemática</a>
                        <a href="#" class="materia">Historia</a>

                    <span class="escuela">Fátima</span>
                        <a href="#" class="materia">Literatura</a>
                        <a href="#" class="materia">Matemática</a>
                        <a href="#" class="materia">Historia</a>
                    </div>
                </nav>
                <section class="buscador_subir_apunte">
                    <div class="contenedor_buscador">
                        <input type="text" placeholder="Buscar..." class="input_buscador">
                    </div>
                </section>
            </section>
            <section class="contenedor_apuntes">
                {{ EXPLORAR }}
            </section>
    </main>
    
</body>

</html>