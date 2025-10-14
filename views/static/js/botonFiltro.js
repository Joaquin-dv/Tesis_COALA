document.addEventListener("DOMContentLoaded", () => {
    const listaMaterias = document.getElementById("listaMaterias");
    const dropdownMateria = document.getElementById("dropdownMateria");
    const inputBuscador = document.getElementById("input_buscador");

    let anioSeleccionado = null;
    let materiaSeleccionada = null;

    // Función para actualizar la URL y recargar apuntes
    function actualizarBusqueda() {
        const params = new URLSearchParams(window.location.search);
        const query = inputBuscador ? inputBuscador.value.trim() : "";
        if (query) {
            params.set('q', query);
        } else {
            params.delete('q');
        }
        if (anioSeleccionado) {
            params.set('anio', anioSeleccionado);
        } else {
            params.delete('anio');
        }
        if (materiaSeleccionada) {
            params.set('materia', materiaSeleccionada);
        } else {
            params.delete('materia');
        }

        // Recargar la página con los nuevos parámetros
        window.location.search = params.toString();
    }

    // Función para cargar materias de un año
    async function cargarMaterias(anio) {
        try {
            const response = await fetch(`api/index.php?model=Apuntes&method=getMateriasPorAnio&anio=${anio}`);
            const materias = await response.json();

            // Mostrar dropdown de materia
            dropdownMateria.style.display = "inline-block";

            // Llenar materias
            listaMaterias.innerHTML = "";
            if (materias && Array.isArray(materias)) {
                materias.forEach(materia => {
                    let a = document.createElement("a");
                    a.href = "#";
                    a.textContent = materia.nombre;
                    a.dataset.materia = materia.nombre;
                    listaMaterias.appendChild(a);

                    // Click en materia
                    a.addEventListener("click", (e) => {
                        e.preventDefault();
                        materiaSeleccionada = materia.nombre;
                        actualizarBusqueda();
                    });
                });
            }
        } catch (error) {
            console.error("Error cargando materias:", error);
        }
    }

    // Inicializar búsqueda y filtros desde URL
    const urlParams = new URLSearchParams(window.location.search);
    const queryParam = urlParams.get('q');
    const anioParam = urlParams.get('anio');
    const materiaParam = urlParams.get('materia');

    if (inputBuscador) {
        if (queryParam) {
            inputBuscador.value = queryParam;
        }

        let debounceTimer;
        inputBuscador.addEventListener("input", () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(actualizarBusqueda, 500);
        });
    }

    // Si hay año en URL, mostrar dropdown de materia y cargar materias
    if (anioParam) {
        anioSeleccionado = anioParam;
        if (materiaParam) {
            materiaSeleccionada = materiaParam;
        }
        cargarMaterias(anioSeleccionado);
    }

    // Click en año
    document.querySelectorAll("#listaAnios a").forEach(link => {
        link.addEventListener("click", async (e) => {
            e.preventDefault();
            anioSeleccionado = link.dataset.anio;
            materiaSeleccionada = null;

            // Cargar materias para este año
            await cargarMaterias(anioSeleccionado);

            actualizarBusqueda();
        });
    });
});
