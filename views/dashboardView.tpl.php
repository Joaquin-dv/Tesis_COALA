@extends(htmlHead)

<!-- Dashboard creado con Chart.js -->
<body>
    <h1>Dashboard</h1>
    <!-- Botón para generar reporte -->
    <button onclick="generarReportePDF()" class="btn btn-primary">
        📊 Generar Reporte PDF
    </button>
    <a href="?slug=logout">Cerrar sesion</a>
    <br>
    <br>

    <div>
        <canvas id="apuntesPorMateriaChart" width="400" height="400"></canvas>
    </div>

    <div>
        <canvas id="apuntesAprobadosRechazadosChart" width="400" height="400"></canvas>
    </div>

    <div>
        <canvas id="usuariosPorEscuelaChart" width="400" height="400"></canvas>
    </div>

    <div>
        <canvas id="apuntesMasVistosChart" width="400" height="400"></canvas>
    </div>

    <div>
        <canvas id="aniosMasBuscadosChart" width="400" height="400"></canvas>
    </div>

    <div>
        <canvas id="materiasMasBuscadasChart" width="400" height="400"></canvas>
    </div>

    <div>
        <canvas id="terminosMasBuscadosChart" width="400" height="400"></canvas>
    </div>

    <div>
        <canvas id="nuevosUsuariosUltimoMesChart" width="400" height="400"></canvas>
    </div>

    <div>
        <canvas id="usuariosLogueadosUltimoMesChart" width="400" height="400"></canvas>
    </div>

    <div>
        <canvas id="erroresSistemaUltimoMes" width="400" height="400"></canvas>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/views/static/js/estadisticas.js"></script>

    <!-- Librerias para generar PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="/views/static/js/reportes.js"></script>

</body>