document.addEventListener("DOMContentLoaded", () => {
    const listaMaterias = document.getElementById("listaMaterias");
    const dropdownMateria = document.getElementById("dropdownMateria");
    const dropdownModalidad = document.getElementById("dropdownModalidad");

    let anioSeleccionado = null;
    let modalidadSeleccionada = null;

    // Materias hardcodeadas según año y modalidad
    const materiasPorAnioModalidad = {
        "1": { "Informatica": ["Literatura"], "Alimentos": ["Matemática"] },
        "2": { "Informatica": ["Historia"], "Alimentos": ["Ciencias"] },
        "3": { "Informatica": ["Matemática"], "Alimentos": ["Programación"] },
        "4": { "Informatica": ["Física"], "Alimentos": ["Química"] },
        "5": { "Informatica": ["Biología"], "Alimentos": ["Literatura"] },
        "6": { "Informatica": ["Redes"], "Alimentos": ["Programación"] },
        "7": { "Informatica": ["Programación"], "Alimentos": ["Bases de Datos"] }
    };

    // Click en año
    document.querySelectorAll("#listaAnios a").forEach(link => {
        link.addEventListener("click", (e) => {
            e.preventDefault();
            anioSeleccionado = link.dataset.anio;

            // Mostrar dropdown de modalidad
            dropdownModalidad.style.display = "inline-block";
            dropdownMateria.style.display = "none"; // ocultar materia
        });
    });

    // Click en modalidad
    document.querySelectorAll("#listaModalidad a").forEach(link => {
        link.addEventListener("click", (e) => {
            e.preventDefault();
            modalidadSeleccionada = link.dataset.modalidad;

            // Mostrar dropdown de materia
            dropdownMateria.style.display = "inline-block";

            // Llenar materias según año + modalidad
            listaMaterias.innerHTML = "";
            const materias = materiasPorAnioModalidad[anioSeleccionado][modalidadSeleccionada] || [];
            materias.forEach(m => {
                let a = document.createElement("a");
                a.href = "#";
                a.textContent = m;
                listaMaterias.appendChild(a);

                // Click en materia
                a.addEventListener("click", (e) => {
                    e.preventDefault();
                });
            });
        });
    });
});
