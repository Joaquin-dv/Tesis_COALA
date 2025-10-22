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
async function fetchJSON(url, options = {}) {
    const res = await fetch(url, {
        ...options,
        headers: { Accept: 'application/json', ...(options.headers || {}) },
    });

    const ct = res.headers.get('content-type') || '';
    const text = await res.text(); // SIEMPRE leemos texto

    if (!res.ok) {
        // Te deja ver el HTML de error (o el stack PHP) en consola
        throw new Error(`HTTP ${res.status} - ${ct}: ${text.slice(0, 500)}`);
    }
    if (ct.includes('application/json')) {
        try {
            return JSON.parse(text);
        } catch (e) {
            throw new Error(`Respuesta no parseable como JSON: ${text.slice(0, 500)}`);
        }
    }
    throw new Error(`Esperaba JSON, llegó ${ct}: ${text.slice(0, 500)}`);
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

function errorLogger(codigoError, mensajeError) {
    const url = 'api/index.php';

    // Hacemos la llamada al servidor SIN async/await
    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            model: 'Logger',
            method: 'error',
            codigoError,
            mensajeError: mensajeError?.toString() ?? 'Error desconocido'
        })
    })
        .then(res => res.json())
    // .then(data => console.log('Log enviado:', data))
    // .catch(err => console.error('Fallo al enviar el log:', err));
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
function validarCamposMinimos({ titulo, escuela, materia, curso, division, profesor, archivos }) {
    if (!titulo) return "Ingresá un título.";
    if (!escuela) return "Seleccioná una escuela.";
    if (!materia) return "Seleccioná una materia.";
    if (!curso) return "Seleccioná un curso.";
    if (!division) return "Seleccioná una división.";
    if (!profesor) return "Ingresá un profesor.";
    // if (!descripcion) return "Ingresá una descripcion.";
    if (!archivos || archivos.length === 0) return "Seleccioná al menos un archivo.";

    // Validar que todos sean imágenes o todos sean PDF
    const tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
    const tiposArchivos = archivos.map(file => file.type);

    // Verificar que todos los archivos sean de tipos permitidos
    for (const tipo of tiposArchivos) {
        if (!tiposPermitidos.includes(tipo)) {
            return "Solo se permiten imágenes (JPG, PNG, GIF, WebP) o archivos PDF.";
        }
    }

    // Verificar que no haya mezcla de tipos
    const tieneImagenes = tiposArchivos.some(tipo => tipo.startsWith('image/'));
    const tienePDF = tiposArchivos.some(tipo => tipo === 'application/pdf');

    if (tieneImagenes && tienePDF) {
        return "No se puede subir una mezcla de imágenes y PDF. Seleccioná solo imágenes o solo un PDF.";
    }

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
                <section id="archivos" class="archivos">
                    <span id="tittle_arch">Sin archivos seleccionados</span>
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
                        <input type="file" name="input_file[]" id="input_file" class="poppins-semibold" multiple accept="image/*,.pdf" hidden>
                        <label for="input_file" class="btn_label poppins-semibold">
                            <i class="fa-solid fa-file-arrow-up"></i>
                            Subir archivos (imágenes o PDF)
                        </label>
                        <details id="mobile-file-toggle" class="archivos-mobile">
                            <summary class="archivos-mobile__summary poppins-semibold">
                                <span class="archivos-mobile__tittle">Archivos seleccionados</span>
                                <span class="archivos-mobile__meta">
                                    <span id="mobile-file-count" class="archivos-mobile__count">(0)</span>
                                    <i class="fa-solid fa-chevron-down archivos-mobile__chevron" aria-hidden="true"></i>
                                </span>
                            </summary>
                            <div id="contenedor_celular" class="archivos-mobile__content">
                                <span id="tittle_arch_mobile">Sin archivos seleccionados</span>
                            </div>
                        </details>
                        <button id="subir_apunte" name="btn_subir_apunte" type="button" class="btn_modal poppins-semibold">Subir Apunte</button>
                        <div class="errorMsg" id="errorGeneral"></div>
                    </form>
                </section>
            </section>
        `,
        padding: 0,
        showConfirmButton: false,
        showCloseButton: true,
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

            const isMobileView = window.innerWidth <= 750;
            const previewContainer = isMobileView ? popup.querySelector("#contenedor_celular") : popup.querySelector("#archivos");
            const tittleDesktop = popup.querySelector("#tittle_arch");
            const tittleMobile = popup.querySelector("#tittle_arch_mobile");
            const mobileToggle = popup.querySelector("#mobile-file-toggle");
            const mobileCount = popup.querySelector("#mobile-file-count");
            const fileInput = popup.querySelector("#input_file");
            const archivosSeleccionados = new Map();
            popup.archivosSeleccionados = archivosSeleccionados;

            const buildFileKey = (file) => `${file.name}-${file.lastModified}`;

            const updateEmptyState = () => {
                const hayArchivos = archivosSeleccionados.size > 0;
                if (tittleDesktop) {
                    tittleDesktop.style.display = hayArchivos ? "none" : "block";
                }
                if (tittleMobile) {
                    tittleMobile.style.display = hayArchivos ? "none" : "block";
                }
            };

            const updateMobileMeta = () => {
                if (!isMobileView) return;
                if (mobileCount) {
                    mobileCount.textContent = `(${archivosSeleccionados.size})`;
                }
                if (mobileToggle) {
                    if (archivosSeleccionados.size === 0) {
                        mobileToggle.removeAttribute("open");
                    }
                }
            };

            updateEmptyState();
            updateMobileMeta();

            if (fileInput && previewContainer) {
                fileInput.addEventListener("change", (e) => {
                    const files = Array.from(e.target.files);

                    files.forEach((file) => {
                        const fileKey = buildFileKey(file);
                        if (archivosSeleccionados.has(fileKey)) {
                            return;
                        }
                        archivosSeleccionados.set(fileKey, file);

                        const item = document.createElement("div");
                        item.classList.add("previsualizacion-item");
                        item.dataset.fileKey = fileKey;
                        const tipo = file.type;

                        if (tipo.startsWith("image/")) {
                            const reader = new FileReader();
                            reader.onload = (event) => {
                                item.innerHTML = `
                            <div class="contenedor_img_arch">
                            <img id="img_subir_apunte" src="${event.target.result}"">
                            </div>
                            <span id="nombre_archivo">${file.name}</span>
                            <button id="btn_quitar_arch" class="quitar-btn"><i class="fa-solid fa-xmark"></i></button>
                        `;
                                item.querySelector(".quitar-btn").addEventListener("click", () => {
                                    const key = item.dataset.fileKey;
                                    if (key) {
                                        archivosSeleccionados.delete(key);
                                    }
                                    item.remove();
                                    updateEmptyState();
                                    updateMobileMeta();
                                });
                                previewContainer.appendChild(item);
                            }
                            reader.readAsDataURL(file);
                        } else if (tipo === "application/pdf") {
                            const reader = new FileReader();
                            reader.onload = (event) => {
                                item.innerHTML = `
                            <div class="contenedor_img_arch pdf-preview">
                                <i class="fa-solid fa-file-pdf pdf-icon"></i>
                            </div>
                            <span id="nombre_archivo">${file.name}</span>
                            <button id="btn_quitar_arch" class="quitar-btn"><i class="fa-solid fa-xmark"></i></button>
                        `;
                                item.querySelector(".quitar-btn").addEventListener("click", () => {
                                    const key = item.dataset.fileKey;
                                    if (key) {
                                        archivosSeleccionados.delete(key);
                                    }
                                    item.remove();
                                    updateEmptyState();
                                    updateMobileMeta();
                                });
                                previewContainer.appendChild(item);
                            }
                            reader.readAsDataURL(file);
                        }

                    });

                    updateEmptyState();
                    updateMobileMeta();
                    fileInput.value = "";
                });
            }


            // 🔽 NUEVO: conectar el botón interno con preConfirm
            const btnSubir = $("#subir_apunte");
            btnSubir?.addEventListener("click", () => {
                Swal.clickConfirm(); // dispara preConfirm
            });

            const form = popup.querySelector('#formulario');

            form?.addEventListener('submit', (event) => {
                event.preventDefault();            // evita el GET implícito
            });

            form?.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' && event.target.tagName !== 'TEXTAREA') {
                    event.preventDefault();
                }
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
                errorLogger('404', 'No se pudieron cargar las opciones. Reintentá más tarde.');
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
            const archivosSeleccionadosMap = popup.archivosSeleccionados instanceof Map ? popup.archivosSeleccionados : new Map();
            const archivos = Array.from(archivosSeleccionadosMap.values());

            // Validación mínima
            const mensajeError = validarCamposMinimos({ titulo, escuela, materia, curso: cursoNivel, division, profesor, archivos });
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
                formData.delete("input_file[]");
                archivos.forEach((file) => {
                    formData.append("input_file[]", file);
                });

                // Campos que el backend espera
                formData.set("model", "Apuntes");
                formData.set("method", "create");
                formData.set("visibilidad", "publico");

                // Convertimos curso (nivel + división) al ID real
                const cursoData = await buscarCurso(cursoNivel, division, escuela);
                const idCurso = Array.isArray(cursoData) && cursoData[0]?.id ? String(cursoData[0].id) : "";

                if (!idCurso) {
                    const msg = "No se encontró el curso seleccionado. Probá de nuevo.";
                    errorLogger('404', 'No se encontró el curso seleccionado. Probá de nuevo.');
                    errorGeneral.textContent = msg;
                    Swal.showValidationMessage(msg);
                    return false;
                }

                // Reemplazamos el valor de 'curso' por el ID real
                formData.set("curso", idCurso);

                // Envío al backend
                const result = await fetchJSON("/api/", { method: "POST", body: formData });

                // Convención de éxito según tu API
                if (result?.errno === 202) {
                    return { ok: true, apunte_id: result.apunte_id };
                }

                // Si hay error, mostrarlo y detener
                const errorMsg = result?.error || "Hubo un error al subir el apunte. Intentá nuevamente.";
                errorGeneral.textContent = errorMsg;
                errorLogger('500', errorMsg);
                error(errorMsg);
                return false;
            } catch (e) {
                const errorMsg = e.message || "Error de conexión. Verificá tu conexión a internet.";
                errorGeneral.textContent = errorMsg;
                errorLogger('500', errorMsg);
                error(errorMsg);
                return false;
            }
        },
    }).then((result) => {
        if (result.isConfirmed && result.value?.ok) {
            exito(() => {
                // Iniciar procesamiento del documento cuando se cierre el toast de éxito
                if (typeof window.startDocumentProcessing === 'function') {
                    window.startDocumentProcessing(result.value.apunte_id);
                } else {
                    // Fallback al método local si no está disponible globalmente
                    startDocumentProcessing(result.value.apunte_id);
                }
            });
        } else if (result.isConfirmed && !result.value?.ok) {
            // Si el preConfirm devolvió false o no hay valor, mostrar error
            error("No se pudo completar la subida del apunte.");
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
