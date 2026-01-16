/*
    estadisticas.js - Manejo de gráficas de estadísticas

    En el front utilizar las siguientes id para obtener los graficos
    - Apuntes por materia: apuntesPorMateriaChart
    - Apuntes aprobados vs rechazados: apuntesAprobadosRechazadosChart
    - Usuarios por escuela: usuariosPorEscuelaChart
    - Apuntes más vistos: apuntesMasVistosChart
    - Usuarios logueados último mes: usuariosLogueadosUltimoMesChart
    - Nuevos usuarios último mes: nuevosUsuariosUltimoMesChart
    - Errores del sistema: erroresSistemaUltimoMes
*/

// Función para verificar contenedor y cargar gráfica
async function cargarGrafica(containerId, apiMethod, procesarDatos) {
    const container = document.getElementById(containerId);
    if (container === null) return;
    
    try {
        const response = await fetch(`/api/index.php?model=Estadisticas&method=${apiMethod}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        procesarDatos(data, container);
    } catch (error) {
        console.error(`Error al cargar ${containerId}:`, error);
    }
}

// Apuntes por materia
cargarGrafica('apuntesPorMateriaChart', 'getApuntesPorMateria', (data, ctx) => {
    const labels = data.map(entry => entry.NOMBRE_MATERIA);
    const cantidadApuntes = data.map(entry => entry.CANTIDAD_APUNTES);
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Número de apuntes por materia',
                data: cantidadApuntes,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});

// Apuntes aprobados vs rechazados
cargarGrafica('apuntesAprobadosRechazadosChart', 'getApuntesAprobadosRechazados', (data, ctx) => {
    const labels = data.map(entry => entry.ESTADO_APUNTE);
    const cantidadApuntes = data.map(entry => entry.CANTIDAD_APUNTES);
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                label: 'Número de apuntes aprobados vs rechazados',
                data: cantidadApuntes,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Apuntes aprobados vs rechazados'
                }
            }
        }
    });
});

// Usuarios por escuela
cargarGrafica('usuariosPorEscuelaChart', 'getUsuariosPorEscuela', (data, ctx) => {
    const labels = data.map(entry => entry.NOMBRE_ESCUELA);
    const cantidadUsuarios = data.map(entry => entry.CANTIDAD_USUARIOS);
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Número de usuarios por escuela',
                data: cantidadUsuarios,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Usuarios por escuela'
                }
            }
        }
    });
});

// Apuntes más vistos
cargarGrafica('apuntesMasVistosChart', 'getApuntesMasVistos', (data, ctx) => {
    const labels = data.map(entry => entry.TITULO_APUNTE);
    const cantidadVistas = data.map(entry => entry.CANTIDAD_VISTAS);
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Apuntes más vistos',
                data: cantidadVistas,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});

// Años mas buscados
cargarGrafica('aniosMasBuscadosChart', 'getAniosMasBuscados', (data, ctx) => {
    const labels = data.map(entry => entry.ANIO);
    const cantidadBusquedas = data.map(entry => entry.CANTIDAD_BUSQUEDAS);
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Número de busquedas por año',
                data: cantidadBusquedas,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});

// Materias mas buscadas
cargarGrafica('materiasMasBuscadasChart', 'getMateriasMasBuscadas', (data, ctx) => {
    const labels = data.map(entry => entry.NOMBRE_MATERIA);
    const cantidadBusquedas = data.map(entry => entry.CANTIDAD_BUSQUEDAS);
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Número de busquedas por materia',
                data: cantidadBusquedas,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});

// Terminos mas buscados
cargarGrafica('terminosMasBuscadosChart', 'getTerminosMasBuscados', (data, ctx) => {
    const labels = data.map(entry => entry.TERMINO_BUSCADO);
    const cantidadBusquedas = data.map(entry => entry.CANTIDAD_BUSQUEDAS);
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Número de busquedas por término',
                data: cantidadBusquedas,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});

// Usuarios logueados último mes
cargarGrafica('usuariosLogueadosUltimoMesChart', 'getUsuariosLogueadosUltimoMes', (data, ctx) => {
    const labels = data.map(entry => entry.FECHA);
    const cantidadUsuarios = data.map(entry => entry.CANTIDAD_LOGUEOS);
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Logueos por fecha',
                data: cantidadUsuarios,
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});

// Nuevos usuarios último mes
cargarGrafica('nuevosUsuariosUltimoMesChart', 'getNuevosUsuariosUltimoMes', (data, ctx) => {
    const labels = data.map(entry => entry.FECHA);
    const cantidadUsuarios = data.map(entry => entry.CANTIDAD_USUARIOS);
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Nuevos usuarios por fecha',
                data: cantidadUsuarios,
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});

// Errores del sistema
cargarGrafica('erroresSistemaUltimoMes', 'getErroresSistemaUltimoMes', (data, ctx) => {
    const labels = data.map(entry => entry.CODIGO_ERROR);
    const cantidadErrores = data.map(entry => entry.CANTIDAD_ERRORES);
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Cantidad de errores por tipo',
                data: cantidadErrores,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});