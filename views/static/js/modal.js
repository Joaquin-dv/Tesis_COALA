document.addEventListener("DOMContentLoaded", () => {

    const btn_abrir = document.querySelector('#abrir_modal');
    const btn_cerrar = document.querySelector('#cerrar_modal');
    const modal = document.querySelector('#modal');
    const subir_apunte = document.querySelector('#subir_apunte');
    const formulario = document.querySelector('#formulario');

    const select_cursos = document.getElementById("curso");
    const select_divisiones = document.getElementById("division");

    btn_abrir.addEventListener("click", () => {
        modal.showModal();
        const cursos = obtenerCursos(1, 1); // Reemplaza con los IDs reales
            cursos.then(data => {
                // Llenar cursos (sin duplicados)
                const cursosAgregados = new Set();
                data.forEach(curso => {
                    if (!cursosAgregados.has(curso.nivel)) {
                        const option = document.createElement("option");
                        option.value = curso.nivel; // valor igual al nombre
                        option.textContent = curso.nivel;
                        select_cursos.appendChild(option);
                        cursosAgregados.add(curso.nivel);
                    }
                });

                // Llenar divisiones (sin duplicados)
                const divisionesAgregadas = new Set();
                data.forEach(curso => {
                    if (!divisionesAgregadas.has(curso.division)) {
                        const option_division = document.createElement("option");
                        option_division.value = curso.division; // valor igual al nombre
                        option_division.textContent = curso.division;
                        select_divisiones.appendChild(option_division);
                        divisionesAgregadas.add(curso.division);
                    }
                });
            });
    })
    btn_cerrar.addEventListener("click", () => {
        modal.close();
    })
    
    subir_apunte.addEventListener("click", async () => {
        let vacio = true;

        const errorGeneral = formulario.querySelector('#errorGeneral');
        errorGeneral.textContent = "";

        formulario.querySelectorAll("input[type='text'], input[type='file'], textarea, select").forEach(input => {
            input.classList.remove('remarcadoError');
            if (input.value === "") {
                vacio = false;
                input.classList.add("remarcadoError");
            }
        });
        if (!vacio) {
            errorGeneral.textContent = "Por favor completa todos los campos";
        } else {
            const titulo = formulario.querySelector('#titulo').value;
            const materia = formulario.querySelector("select[name='materia']").value;
            const curso = select_cursos.value;
            const division = select_divisiones.value;
            const descripcion = formulario.querySelector('#descripcion').value;
            const archivo = formulario.querySelector('#btn_subir_arch').files[0];

            const cursoData = await buscarCurso(curso, division);
            const idCurso = cursoData[0]?.id ?? '';

            // Usar FormData para enviar el archivo
            const formData = new FormData();
            formData.append('model', 'Apuntes');
            formData.append('method', 'create');
            formData.append('titulo', titulo);
            formData.append('descripcion', descripcion);
            formData.append('materia', materia);
            formData.append('curso', idCurso);
            formData.append('division', division);
            formData.append('visibilidad', 'publico');
            formData.append('btn_subir_archivo', archivo); // nombre igual al esperado en PHP

            const response = await fetch('/api/', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            console.log(result);

            if (result.errno === 202) {
                errorGeneral.textContent = "¡Apunte subido correctamente!";
                modal.close();
            } else {
                errorGeneral.textContent = result.error || "Error al subir el apunte";
            }
        }
    });
});

async function obtenerCursos(id_escuela, id_anio_lectivo) {
    const response = await fetch(`api?model=Escuelas&method=getCursos&id_escuela=${id_escuela}&id_anio_lectivo=${id_anio_lectivo}`);
    const data = await response.json();
    return data;
}

async function buscarCurso(nivel, division) {
    const response = await fetch(`api?model=Escuelas&method=getCursoByNivelandDivision&nivel=${nivel}&division=${division}&id_escuela=1&id_anio_lectivo=1`);
    const data = await response.json();
    return data;
}

async function obtenerMaterias(id_escuela, id_anio_lectivo) {
    const response = await fetch(`api?model=Escuelas&method=getMaterias&id_escuela=${id_escuela}&id_anio_lectivo=${id_anio_lectivo}`);
    const data = await response.json();
    return data;
}