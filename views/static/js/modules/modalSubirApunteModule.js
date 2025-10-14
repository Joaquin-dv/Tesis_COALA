// import { validarFormulario } from "./validacionFormulario.js"; // Si no se usa, podés quitar la importación
import { cargando, exito, error, aprobado, rechazado, procesando, cerrarProcesando } from "./toastModule.js";

// IDs por defecto de la escuela y ciclo lectivo (ajustá si hace falta)
const ID_ESCUELA_DEFAULT = 1;
const ID_ANIO_LECTIVO_DEFAULT = 1;

// -------------------------------------------------------------
// API Helpers simples
// -------------------------------------------------------------

/**
 * Envuelve fetch + JSON con manejo básico de errores.
 * @param {string} url - URL del endpoint (puede incluir query params)
 * @param {RequestInit} [options] - Opciones para fetch
 * @returns {Promise<any>} - Respuesta JSON
 */
async function fetchJSON(url, options) {
    const resp = await fetch(url, options);
    if (!resp.ok) throw new Error(`Error HTTP ${resp.status}`);
    return resp.json();
}

/**
 * Trae escuelas disponibles.
 * Estructura esperada (ejemplo): [{ ESCUELA_ID, ESCUELA_NOMBRE }, ...]
 */
function obtenerEscuelas() {
    const url = `api/index.php?model=Escuelas&method=getEscuelas`;
    return fetchJSON(url);
}

/**
 * Trae cursos para una escuela/año lectivo.
 * Estructura esperada (ejemplo): [{ NIVEL }, ...]
 */
function obtenerNiveles(idEscuela, idAnioLectivo) {
    const url = `api/index.php?model=Escuelas&method=getNivelesByEscuela&id_escuela=${idEscuela}&id_anio_lectivo=${idAnioLectivo}`;
    return fetchJSON(url);
}

/**
 * Trae las divisiones para curso.
 * Estructura esperada (ejemplo): [{ DIVISION }, ...]
 */
function obtenerDivisiones(idEscuela, idAnioLectivo, nivel) {
    const url = `api/index.php?model=Escuelas&method=getDivisionesPorNivel&id_escuela=${idEscuela}&id_anio_lectivo=${idAnioLectivo}&nivel=${encodeURIComponent(nivel)}`;
    return fetchJSON(url);
}

/**
 * Busca un curso por nivel + división para mapear al ID real.
 * Estructura esperada (ej.): [{ id, nivel, division }]
 */
function buscarCurso(nivel, division, idEscuela) {
    const url = `api/index.php?model=Escuelas&method=getCursoByNivelandDivision&nivel=${encodeURIComponent(nivel)}&division=${encodeURIComponent(division)}&id_escuela=${idEscuela}&id_anio_lectivo=${ID_ANIO_LECTIVO_DEFAULT}`;
    return fetchJSON(url);
}

/**
 * Trae materias para una escuela/año lectivo.
 * Estructura esperada (ej.): [{ MATERIA_ID, MATERIA_NOMBRE }, ...]
 */
function obtenerMateriasPorCurso(idEscuela, idAnioLectivo, idCurso) {
    const url = `api/index.php?model=Escuelas&method=getMateriasByCurso&id_escuela=${idEscuela}&id_anio_lectivo=${idAnioLectivo}&id_curso=${idCurso}`;
    return fetchJSON(url);
}

// -------------------------------------------------------------
// Utilidades de UI simples
// -------------------------------------------------------------

/** Crea y devuelve un <option>. */
function crearOpcion(valor, texto) {
    const opt = document.createElement("option");
    opt.value = valor;
    opt.textContent = texto;
    return opt;
}

/**
 * Llena un <select> con valores únicos tomados de una lista,
 * usando una propiedad como "clave" y otra como "texto".
 * @param {HTMLSelectElement} select
 * @param {Array<any>} lista
 * @param {string} propValor - Propiedad a usar para value
 * @param {string} propTexto - Propiedad a usar para label
 * @param {string} placeholder - Texto para la primera opción vacía
 */
function llenarSelectUnico(select, lista, propValor, propTexto, placeholder) {
    // Limpio y agrego placeholder
    select.innerHTML = "";
    select.appendChild(crearOpcion("", placeholder));

    const vistos = new Set();
    for (const item of lista) {
        const valor = String(item[propValor] ?? "").trim();
        if (!valor || vistos.has(valor)) continue;

        const texto = String(item[propTexto] ?? valor);
        select.appendChild(crearOpcion(valor, texto));
        vistos.add(valor);
    }
}

/**
 * Valida de forma sencilla los campos mínimos del formulario.
 * Devuelve string con el mensaje de error o "" si todo OK.
 */
function validarCamposMinimos({ titulo, escuela, materia, curso, division, profesor, descripcion, archivo }) {
    if (!titulo) return "Ingresá un título.";
    if (!escuela) return "Seleccioná una escuela.";
    if (!materia) return "Seleccioná una materia.";
    if (!curso) return "Seleccioná un curso.";
    if (!division) return "Seleccioná una división.";
    if (!profesor) return "Ingresá un profesor.";
    if (!descripcion) return "Ingresá una descripcion.";
    if (!archivo) return "Seleccioná un archivo.";
    return "";
}

// -------------------------------------------------------------
// Entrada principal: inicializa el botón que abre el modal
// -------------------------------------------------------------

export function initModal() {
    const btnAbrir = document.querySelector("#abrir_modal");
    if (!btnAbrir) return;

    btnAbrir.addEventListener("click", abrirModalSubida);
}

// -------------------------------------------------------------
// Lógica del modal (Swal)
// -------------------------------------------------------------

async function abrirModalSubida() {
    Swal.fire({
        html: `
            <section class="contenido_modal_swal">
                <section class="imagen">
                    <img id="koala_modal" src="views/static/img/modal/koala.png" alt="Koala">
                </section>

                <section class="contenido_formulario">
                    <form id="formulario" autocomplete="off" enctype="multipart/form-data">
                        <input type="text" name="titulo" id="titulo" class="campo_modal poppins-semibold" placeholder="Título del apunte">
                        
                        <select name="escuela" id="escuela" class="campo_modal poppins-semibold">
                            <option value="">Escuela</option>
                        </select>

                        <section class="curso_division">
                            <select name="curso" id="curso" class="campo_modal poppins-semibold">
                                <option value="">Curso</option>
                            </select>
                            <select name="division" id="division" class="campo_modal poppins-semibold">
                                <option value="">División</option>
                            </select>
                        </section>

                        <select name="materia" id="materia" class="campo_modal poppins-semibold">
                            <option value="">Materia</option>
                        </select>

                        <!-- EL CAMPO DE PROFESOR SE COMENTA HASTA QUE SEA FUNCIONAL -->
                        <!-- <input type="text" name="profesor" id="profesor" class="campo_modal poppins-semibold" placeholder="Profesor (opcional)"> -->

                        <textarea id="descripcion" class="campo_modal poppins-semibold" name="descripcion" rows="4" cols="50" placeholder="Descripción (opcional)"></textarea>

                        <!-- El input file debe coincidir con el "name" que espera el backend -->
                        <input type="file" name="input_file" id="input_file" class="poppins-semibold" hidden>
                        <label for="input_file" class="btn_label poppins-semibold">
                            <i class="fa-solid fa-file-arrow-up"></i>
                            Subir archivo
                        </label>
                        <button id="subir_apunte" name="btn_subir_apunte" type="button" class="btn_modal poppins-semibold">Subir Apunte</button>
                        <div class="errorMsg" id="errorGeneral"></div>
                    </form>
                </section>
            </section>
        `,
        padding: 0,
        showConfirmButton: false,
        focusConfirm: false,
        customClass: {
            popup: "modal_coala_popup no-padding-modal",
            confirmButton: "btn_modal poppins-semibold",
            cancelButton: "btn_modal poppins-semibold",
        },

        // Carga inicial de selects cuando abre el popup
        didOpen: async (popup) => {
            const $ = (sel) => popup.querySelector(sel);
            const selectEscuela = $("#escuela");
            const selectCurso = $("#curso");
            const selectDivision = $("#division");
            const selectMateria = $("#materia");
            const errorGeneral = $("#errorGeneral");

            // 🔽 NUEVO: conectar el botón interno con preConfirm
            const btnSubir = $("#subir_apunte");
            btnSubir?.addEventListener("click", () => {
                Swal.clickConfirm(); // dispara preConfirm
            });

            // Deshabilitar selects dependientes inicialmente
            selectCurso.disabled = true;
            selectDivision.disabled = true;
            selectMateria.disabled = true;

            try {
                // Cargar escuelas
                const escuelas = await obtenerEscuelas();
                llenarSelectUnico(selectEscuela, escuelas, "ESCUELA_ID", "ESCUELA_NOMBRE", "Escuela");

                // Agregar event listeners para cascada
                selectEscuela.addEventListener("change", async () => {
                    const escuelaId = selectEscuela.value;
                    if (!escuelaId) {
                        selectCurso.disabled = true;
                        selectDivision.disabled = true;
                        selectMateria.disabled = true;
                        selectCurso.innerHTML = '<option value="">Curso</option>';
                        selectDivision.innerHTML = '<option value="">División</option>';
                        selectMateria.innerHTML = '<option value="">Materia</option>';
                        return;
                    }

                    try {
                        const cursos = await obtenerNiveles(escuelaId, ID_ANIO_LECTIVO_DEFAULT);
                        llenarSelectUnico(selectCurso, cursos, "NIVEL", "NIVEL", "Curso");
                        selectCurso.disabled = false;
                        selectDivision.disabled = true;
                        selectMateria.disabled = true;
                        selectDivision.innerHTML = '<option value="">División</option>';
                        selectMateria.innerHTML = '<option value="">Materia</option>';
                    } catch (e) {
                        console.error("Error cargando cursos:", e);
                    }
                });

                selectCurso.addEventListener("change", async () => {
                    const escuelaId = selectEscuela.value;
                    const cursoNivel = selectCurso.value;
                    if (!cursoNivel) {
                        selectDivision.disabled = true;
                        selectMateria.disabled = true;
                        selectDivision.innerHTML = '<option value="">División</option>';
                        selectMateria.innerHTML = '<option value="">Materia</option>';
                        return;
                    }

                    try {
                        const divisiones = await obtenerDivisiones(escuelaId, ID_ANIO_LECTIVO_DEFAULT, cursoNivel);
                        llenarSelectUnico(selectDivision, divisiones, "DIVISION", "DIVISION", "División");
                        selectDivision.disabled = false;
                        selectMateria.disabled = true;
                        selectMateria.innerHTML = '<option value="">Materia</option>';
                    } catch (e) {
                        console.error("Error cargando divisiones:", e);
                    }
                });

                selectDivision.addEventListener("change", async () => {
                    const escuelaId = selectEscuela.value;
                    if (!selectDivision.value) {
                        selectMateria.disabled = true;
                        selectMateria.innerHTML = '<option value="">Materia</option>';
                        return;
                    }

                    try {
                        const cursoData = await buscarCurso(selectCurso.value, selectDivision.value, escuelaId);
                        const idCurso = Array.isArray(cursoData) && cursoData[0]?.id ? String(cursoData[0].id) : "";
                        const materias = await obtenerMateriasPorCurso(escuelaId, ID_ANIO_LECTIVO_DEFAULT, idCurso);
                        llenarSelectUnico(selectMateria, materias, "MATERIA_ID", "MATERIA_NOMBRE", "Materia");
                        selectMateria.disabled = false;
                    } catch (e) {
                        console.error("Error cargando materias:", e);
                    }
                });

            } catch (e) {
                const msg = "No se pudieron cargar las opciones. Reintentá más tarde.";
                errorGeneral.textContent = msg;
                Swal.showValidationMessage(msg);
            }
        },

        // Validación + envío antes de confirmar
        preConfirm: async () => {
            const popup = Swal.getPopup();

            // Helper rápido para querySelector dentro del popup
            const q = (sel) => popup.querySelector(sel);

            const form = q("#formulario");
            const errorGeneral = q("#errorGeneral");

            // Extraemos valores simples para validar
            const titulo = q("#titulo")?.value.trim() || "";
            const escuela = q("#escuela")?.value.trim() || "";
            const materia = q("#materia")?.value.trim() || "";
            const cursoNivel = q("#curso")?.value.trim() || "";
            const division = q("#division")?.value.trim() || "";

            // EL CAMPO DE PROFESOR SE COMENTA HASTA QUE SEA FUNCIONAL
            const profesor = "No especificado";
            // const profesor = q("#profesor")?.value.trim() || "";
            const descripcion = q("#descripcion").value.trim() || "";
            const archivo = q("#input_file")?.files?.[0] || null;

            // Validación mínima
            const mensajeError = validarCamposMinimos({ titulo, escuela, materia, curso: cursoNivel, division, profesor, descripcion, archivo });
            if (mensajeError) {
                errorGeneral.textContent = mensajeError;
                // Swal.showValidationMessage(mensajeError);
                return false;
            }

            // (Opcional) Validación extra con tu función si la usás
            if (typeof validarFormulario === "function") {
                const ok = validarFormulario(form);
                if (!ok) {
                    const msg = "Revisá los campos resaltados.";
                    errorGeneral.textContent = msg;
                    Swal.showValidationMessage(msg);
                    return false;
                }
            }

            cargando();
            try {
                // FormData directo del form (incluye archivo)
                const formData = new FormData(form);

                // Campos que el backend espera
                formData.set("model", "Apuntes");
                formData.set("method", "create");
                formData.set("visibilidad", "publico");

                // Convertimos curso (nivel + división) al ID real
                const cursoData = await buscarCurso(cursoNivel, division, escuela);
                const idCurso = Array.isArray(cursoData) && cursoData[0]?.id ? String(cursoData[0].id) : "";

                if (!idCurso) {
                    const msg = "No se encontró el curso seleccionado. Probá de nuevo.";
                    errorGeneral.textContent = msg;
                    Swal.showValidationMessage(msg);
                    return false;
                }

                // Reemplazamos el valor de 'curso' por el ID real
                formData.set("curso", idCurso);
                
                // Envío al backend
                const result = await fetchJSON("api/index.php", { method: "POST", body: formData });

                // Convención de éxito según tu API
                if (result?.errno === 202) {
                    return { ok: true, apunte_id: result.apunte_id };
                }

                error(result.error);

                // Si la API devolvió algo distinto, mostramos mensaje claro
                // throw new Error(result?.message || "Hubo un error al subir el apunte. Intentá nuevamente.");
            } catch (e) {
                error();
            }
        },
    }).then((result) => {
        if (result.isConfirmed && result.value?.ok) {
            exito();
            setTimeout(() => {
            // Iniciar procesamiento del documento usando la función global
            if (typeof window.startDocumentProcessing === 'function') {
                window.startDocumentProcessing(result.value.apunte_id);
            } else {
                // Fallback al método local si no está disponible globalmente
                startDocumentProcessing(result.value.apunte_id);
            }}, 3000);
        }
    });
}

async function startDocumentProcessing(apunte_id) {
    const formData = new FormData();
    formData.append('model', 'Apuntes');
    formData.append('method', 'startProcessing');
    formData.append('apunte_id', apunte_id);

    const response = await fetch('api/index.php', {
        method: 'POST',
        body: formData
    });
    const result = await response.json();

    if (result.errno === 200) {
        // Mostrar notificación de procesamiento
        procesando();
        startPolling(result.processing_id);
    } else {
        console.error('Error al iniciar procesamiento:', result.error);
    }
}

function startPolling(processingId) {
    let processed = false;
    const pollInterval = setInterval(async () => {
        if (processed) return; // Deja de hacer polling si ya esta procesado

        const response = await fetch(`api/index.php?model=Apuntes&method=checkProcessingStatus&processing_id=${processingId}`);
        const data = await response.json();

        if (data.status === 'completed' && !processed) {
            processed = true;
            clearInterval(pollInterval);
            cerrarProcesando();
            showProcessingResult(data.result);
        } else if (data.status === 'error' && !processed) {
            processed = true;
            clearInterval(pollInterval);
            cerrarProcesando();
            console.error('Error en procesamiento:', data.message);
        }
        // Si sigue processing, continúa haciendo polling
    }, 2000); // Poll cada 2 segundos
}

function showProcessingResult(result) {
    if (result.status === 'approved') {
        aprobado();
    } else {
        rechazado();
    }
}