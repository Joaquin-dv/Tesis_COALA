/**
 * Valida un formulario, marcando campos vacíos y mostrando un mensaje de error general.
 * @param {HTMLFormElement} form - El formulario a validar
 * @param {string} errorSelector - Selector del contenedor de errores dentro del formulario (por defecto: '#errorGeneral')
 * @returns {boolean} - true si el formulario es válido, false si hay campos vacíos
 */
export function validarFormulario(form, errorSelector = '#errorGeneral') {
    let valido = true;

    const errorGeneral = form.querySelector(errorSelector);
    if (errorGeneral) errorGeneral.textContent = "";

    form.querySelectorAll("input[type='text'], input[type='file'], textarea, select").forEach(input => {
        input.classList.remove('remarcadoError');

        // Si es input file, verificamos que tenga archivos seleccionados
        if (input.type === 'file') {
            console.log(input.files);
            if (input.files.length === 0) {
                valido = false;
                input.classList.add('remarcadoError');
            }
        } else if (input.value.trim() === "") {
            valido = false;
            input.classList.add('remarcadoError');
        }
    });

    if (!valido && errorGeneral) {
        errorGeneral.textContent = "Por favor completa todos los campos";
    }

    return valido;
}
