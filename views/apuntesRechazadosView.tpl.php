@extends(htmlHead)

<body id="dashboard-body" class="josefin-sans-normal">
    <header class="dashboard-header">
        <h1><i class="fa-solid fa-chart-simple"  id="menu-icon"></i> Apuntes Rechazados - COALA</h1>
        <div id="dropdown-menu" class="dropdown">
            <ul>
                <li><a href="?slug=dashboard">Estadísticas</a></li>
                <li><a href="?slug=apuntesRechazados">Apuntes Rechazados</a></li>
                <!-- <li><a href="?slug=apuntesReportados">Apuntes Reportados</a></li> -->
            </ul>
        </div>
        <div class="dashboard-actions">
            <div>
                <a href="?slug=logout" class="btn btn-secondary">Cerrar sesión</a>
            </div>
        </div>
    </header>

    <main class="rechazados-container">
        <section class="Bienvenida-dashboard">
            <h2>Control de Calidad <span>IA</span></h2>
            <p>Supervisá los apuntes detectados por la IA y asegurá la calidad del contenido en COALA.</p>
        </section>

        <section class="contenedor_apuntes_rechazados">
           {{ APUNTES_EN_REVISION}}
        </section>



        
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/views/static/js/estadisticas.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="/views/static/js/reportes.js"></script>
    <script src="/views/static/js/menu-desplegable.js"></script>
</body>
