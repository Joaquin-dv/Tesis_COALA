// Función para generar reporte PDF de estadísticas
async function generarReportePDF() {
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF('p', 'mm', 'a4');
    
    // Título del reporte
    pdf.setFontSize(20);
    pdf.text('Reporte de Estadísticas COALA', 105, 10, { align: 'center' });
    pdf.setFontSize(12);
    pdf.text(`Generado el: ${new Date().toLocaleDateString('es-ES')}`, 105, 20, { align: 'center' });
    
    let yPosition = 30;
    
    // Gráficas con títulos
    const graficas = [
        { id: 'apuntesPorMateriaChart', titulo: 'Apuntes por Materia' },
        { id: 'apuntesAprobadosRechazadosChart', titulo: 'Apuntes Aprobados vs Rechazados' },
        { id: 'usuariosPorEscuelaChart', titulo: 'Usuarios por Escuela' },
        { id: 'apuntesMasVistosChart', titulo: 'Apuntes Más Vistos' },
        { id: 'aniosMasBuscadosChart', titulo: 'Años Más Buscados' },
        { id: 'materiasMasBuscadasChart', titulo: 'Materias Más Buscados' },
        { id: 'terminosMasBuscadosChart', titulo: 'Términos Más Buscados' },
        { id: 'usuariosLogueadosUltimoMesChart', titulo: 'Logueos Último Mes' },
        { id: 'nuevosUsuariosUltimoMesChart', titulo: 'Nuevos Usuarios Último Mes' },
        { id: 'erroresSistemaUltimoMes', titulo: 'Errores del Sistema' }
    ];
    
    for (const grafica of graficas) {
        const elemento = document.getElementById(grafica.id);
        if (elemento) {
            try {
                // Agregar nueva página si no hay espacio suficiente (título + gráfica + margen)
                if (yPosition > 180) {
                    pdf.addPage();
                    yPosition = 20;
                }
                
                // Título de la gráfica
                pdf.setFontSize(14);
                pdf.text(grafica.titulo, 20, yPosition);
                yPosition += 10;
                
                const canvas = await html2canvas(elemento, { 
                    scale: 1.5,
                    backgroundColor: '#ffffff',
                    useCORS: true
                });
                const imgData = canvas.toDataURL('image/jpeg', 0.7);
                
                // Todas las gráficas con dimensiones cuadradas
                const width = 110;
                const height = 110;
                const x = 45; // Centrado
                
                pdf.addImage(imgData, 'JPEG', x, yPosition, width, height);
                yPosition += 130;
            } catch (error) {
                console.error(`Error capturando ${grafica.id}:`, error);
            }
        }
    }
    
    pdf.save(`reporte-estadisticas-${new Date().toISOString().split('T')[0]}.pdf`);
}

// Botón para generar reporte
function agregarBotonReporte() {
    const boton = document.createElement('button');
    boton.textContent = 'Generar Reporte PDF';
    boton.className = 'btn btn-primary';
    boton.onclick = generarReportePDF;
    
    const container = document.querySelector('.estadisticas-container') || document.body;
    container.appendChild(boton);
}

// Ejecutar cuando el DOM esté listo
// document.addEventListener('DOMContentLoaded', agregarBotonReporte);