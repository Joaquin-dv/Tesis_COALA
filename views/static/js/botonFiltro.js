document.addEventListener("DOMContentLoaded", () => {
    const selectAnio = document.getElementById("selectAnio");
    const selectMateria = document.getElementById("selectMateria");
    const inputBuscador = document.getElementById("input_buscador");

    let anioSeleccionado = selectAnio && selectAnio.value ? selectAnio.value : null;
    let materiaSeleccionada = null;

    function resetMateria() {
        if (!selectMateria) {
            return;
        }
        selectMateria.innerHTML = '<option value="">Materia</option>';
        selectMateria.value = "";
        selectMateria.disabled = true;
    }

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
    async function cargarMaterias(anio, materiaPreseleccionada = null) {
        if (!selectMateria || !anio) {
            resetMateria();
            return;
        }

        try {
            const response = await fetch(`api/index.php?model=Apuntes&method=getMateriasPorAnio&anio=${anio}`);
            const materias = await response.json();

            resetMateria();

            if (materias && Array.isArray(materias) && materias.length > 0) {
                materias.forEach(materia => {
                    const option = document.createElement("option");
                    option.value = materia.nombre;
                    option.textContent = materia.nombre;
                    selectMateria.appendChild(option);
                });

                selectMateria.disabled = false;

                if (materiaPreseleccionada) {
                    selectMateria.value = materiaPreseleccionada;
                    if (selectMateria.value === materiaPreseleccionada) {
                        materiaSeleccionada = materiaPreseleccionada;
                    }
                }
            }
        } catch (error) {
            console.error("Error cargando materias:", error);
            resetMateria();
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
        if (selectAnio) {
            selectAnio.value = anioSeleccionado;
        }
        cargarMaterias(anioSeleccionado, materiaParam);
    } else {
        resetMateria();
    }

    // Cambio en año
    if (selectAnio) {
        selectAnio.addEventListener("change", async () => {
            anioSeleccionado = selectAnio.value ? selectAnio.value : null;
            materiaSeleccionada = null;

            await cargarMaterias(anioSeleccionado);

            actualizarBusqueda();
        });
    }

    // Cambio en materia
    if (selectMateria) {
        selectMateria.addEventListener("change", () => {
            const value = selectMateria.value;
            materiaSeleccionada = value ? value : null;
            actualizarBusqueda();
        });
    }
});
