// import { validarFormulario } from "./validacionFormulario.js"; // Si no se usa, podés quitar la importación
import { cargando, exito, error, aprobado, rechazado } from "./toastModule.js";

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
 * Trae cursos para una escuela/año lectivo.
 * Estructura esperada (ejemplo): [{ id, nivel, division }, ...]
 */
function obtenerCursos(idEscuela, idAnioLectivo) {
    const url = `api/index.php?model=Escuelas&method=getCursos&id_escuela=${idEscuela}&id_anio_lectivo=${idAnioLectivo}`;
    return fetchJSON(url);
}

/**
 * Busca un curso por nivel + división para mapear al ID real.
 * Estructura esperada (ej.): [{ id, nivel, division }]
 */
function buscarCurso(nivel, division) {
    const url = `api/index.php?model=Escuelas&method=getCursoByNivelandDivision&nivel=${encodeURIComponent(nivel)}&division=${encodeURIComponent(division)}&id_escuela=${ID_ESCUELA_DEFAULT}&id_anio_lectivo=${ID_ANIO_LECTIVO_DEFAULT}`;
    return fetchJSON(url);
}

/**
 * Trae materias para una escuela/año lectivo.
 * Estructura esperada (ej.): [{ id, nombre }, ...]
 */
function obtenerMaterias(idEscuela, idAnioLectivo) {
    const url = `api/index.php?model=Escuelas&method=getMaterias&id_escuela=${idEscuela}&id_anio_lectivo=${idAnioLectivo}`;
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
  .then(data => console.log('Log enviado:', data))
  .catch(err => console.error('Fallo al enviar el log:', err));

  // (opcional) log local para depurar
  console.log('Intentando registrar error:', codigoError, mensajeError);
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
function validarCamposMinimos({ titulo, materia, curso, division, profesor, descripcion, archivo }) {
    if (!titulo) return "Ingresá un título.";
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

                        <select name="materia" id="materia" class="campo_modal poppins-semibold">
                            <option value="">Materia</option>
                        </select>

                        <section class="curso_division">
                            <select name="curso" id="curso" class="campo_modal poppins-semibold">
                                <option value="">Curso</option>
                            </select>
                            <select name="division" id="division" class="campo_modal poppins-semibold">
                                <option value="">División</option>
                            </select>
                        </section>

                        <input type="text" name="profesor" id="profesor" class="campo_modal poppins-semibold" placeholder="Profesor (opcional)">

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
            const selectCurso = $("#curso");
            const selectDivision = $("#division");
            const selectMateria = $("#materia");
            const errorGeneral = $("#errorGeneral");

            // 🔽 NUEVO: conectar el botón interno con preConfirm
            const btnSubir = $("#subir_apunte");
            btnSubir?.addEventListener("click", () => {
                Swal.clickConfirm(); // dispara preConfirm
            });

            try {
                // Traemos cursos y materias en paralelo
                const [cursos, materias] = await Promise.all([
                    obtenerCursos(ID_ESCUELA_DEFAULT, ID_ANIO_LECTIVO_DEFAULT),
                    obtenerMaterias(ID_ESCUELA_DEFAULT, ID_ANIO_LECTIVO_DEFAULT),
                ]);

                // Curso = "nivel", División = "division" según tu API
                llenarSelectUnico(selectCurso, cursos, "nivel", "nivel", "Curso");
                llenarSelectUnico(selectDivision, cursos, "division", "division", "División");

                // Materias: value=id, label=nombre
                llenarSelectUnico(selectMateria, materias, "id", "nombre", "Materia");
            } catch (e) {
                const msg = "No se pudieron cargar las opciones. Reintentá más tarde.";
                errorLogger('404','No se pudieron cargar las opciones. Reintentá más tarde.');
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
            const materia = q("#materia")?.value.trim() || "";
            const cursoNivel = q("#curso")?.value.trim() || "";
            const division = q("#division")?.value.trim() || "";
            const profesor = q("#profesor")?.value.trim() || "";
            const descripcion = q("#descripcion").value.trim() || "";
            const archivo = q("#input_file")?.files?.[0] || null;

            // Validación mínima
            const mensajeError = validarCamposMinimos({ titulo, materia, curso: cursoNivel, division, profesor, descripcion, archivo });
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
                const cursoData = await buscarCurso(cursoNivel, division);
                const idCurso = Array.isArray(cursoData) && cursoData[0]?.id ? String(cursoData[0].id) : "";

                if (!idCurso) {
                    const msg = "No se encontró el curso seleccionado. Probá de nuevo.";
                    errorLogger('404','No se encontró el curso seleccionado. Probá de nuevo.');
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
                errorLogger('500',e);
                error(e);
            }
        },
    }).then((result) => {
        if (result.isConfirmed && result.value?.ok) {
            exito();
            // Iniciar procesamiento del documento
            startDocumentProcessing(result.value.apunte_id);
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
            showProcessingResult(data.result);
        } else if (data.status === 'error' && !processed) {
            processed = true;
            clearInterval(pollInterval);
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