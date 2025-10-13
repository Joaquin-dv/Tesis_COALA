import { initModal } from './modules/modalSubirApunteModule.js';

document.addEventListener("DOMContentLoaded", function () {
    initModal();

    // Función común de búsqueda
    function initSearch() {
        const inputBuscador = document.getElementById("input_buscador");
        if (inputBuscador) {
            // Establecer valor inicial desde URL
            const urlParams = new URLSearchParams(window.location.search);
            const queryParam = urlParams.get('q');
            if (queryParam) {
                inputBuscador.value = queryParam;
            }

            let debounceTimer;
            inputBuscador.addEventListener("input", () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const query = inputBuscador.value.trim();
                    const params = new URLSearchParams(window.location.search);
                    if (query) {
                        params.set('q', query);
                    } else {
                        params.delete('q');
                    }
                    // Recargar la página con el parámetro de búsqueda
                    window.location.search = params.toString();
                }, 500);
            });
        }
    }

    initSearch();
});