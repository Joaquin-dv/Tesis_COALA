@extends(htmlHead)

<body id="dashboard-body" class="josefin-sans-normal">
    <header class="dashboard-header">
        <h1><i class="fa-solid fa-chart-simple"  id="menu-icon"></i> Panel de Estadísticas - COALA</h1>
        <div id="dropdown-menu" class="dropdown">
            <ul>
                <li><a href="?slug=dashboard">Estadísticas</a></li>
                <li><a href="?slug=apuntesRechazados">Apuntes Rechazados</a></li>
                <li><a href="?slug=apuntesReportados">Apuntes Reportados</a></li>
            </ul>
        </div>
        <div class="dashboard-actions">
            <div>

                <button onclick="generarReportePDF()" class="btn btn-primary">
                    <i class="fa-solid fa-file-pdf"></i> Generar Reporte PDF
                </button>
            </div>
            <div>
                <a href="?slug=logout" class="btn btn-secondary">Cerrar sesión</a>
            </div>
        </div>
    </header>

    <main class="estadisticas-container">
        <section class="Bienvenida-dashboard">
            <h2>Bienvenido al Panel de Administración de <span>COALA</span></h2>
            <p>Gestioná, analizá y hacé crecer la comunidad educativa.</p>
        </section>

        <section class="dashboard-grid">

        <!-- Fila 3: 3 normales -->
            <div class="fila-normal">
                <div class="chart-card normal">
                    <h3>Usuarios por Escuela</h3>
                    <canvas id="usuariosPorEscuelaChart"></canvas>
                </div>
                <div class="chart-card normal">
                    <h3>Apuntes Aprobados vs Rechazados</h3>
                    <canvas id="apuntesAprobadosRechazadosChart"></canvas>
                    
                </div>
                
                
            </div>

            
            
            <!-- Fila 2: 1 grande -->
            <div class="fila-grande">
                <div class="chart-card grande">
                    <h3>Apuntes por Materia</h3>
                    <canvas id="apuntesPorMateriaChart"></canvas>
                </div>
            </div>

            <!-- Fila 1: 2 medianas -->
             <div class="fila-mediana">
                <div class="chart-card normal">
                    <h3>Apuntes Más Vistos</h3>
                    <canvas id="apuntesMasVistosChart"></canvas>
                </div>
                <div class="chart-card normal">
                    <h3>Años Más Buscados</h3>
                    <canvas id="aniosMasBuscadosChart"></canvas>
                </div>
                
             </div>
                
            

            <!-- Fila 4: 2 grandes -->
            <div class="fila-grande">
                <div class="chart-card grande">
                    <h3>Términos Más Buscados</h3>
                    <canvas id="terminosMasBuscadosChart"></canvas>
                </div>
            </div>

            <div class="fila-grande">
                <div class="chart-card grande">
                    <h3>Nuevos Usuarios (Último Mes)</h3>
                    <canvas id="nuevosUsuariosUltimoMesChart"></canvas>
                </div>
            </div>

            <!-- Fila 5: 2 normales -->
            <div class="fila-normal">
                <div class="chart-card normal">
                    <h3>Usuarios Logueados (Último Mes)</h3>
                    <canvas id="usuariosLogueadosUltimoMesChart"></canvas>
                </div>
                <div class="chart-card normal">
                    <h3>Materias Más Buscadas</h3>
                    <canvas id="materiasMasBuscadasChart"></canvas>
                </div>
                
                <div class="chart-card normal">
                    <h3>Errores del Sistema</h3>
                    <canvas id="erroresSistemaUltimoMes"></canvas>
                </div>
            </div>
            </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/views/static/js/estadisticas.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="/views/static/js/reportes.js"></script>
    <script src="/views/static/js/menu-desplegable.js"></script>
</body>
