<section class="filtros">

    <!-- Dropdown Año -->
    <div class="dropdown">
        <button class="dropbtn">Año ▾</button>
        <div class="dropdown-content" id="listaAnios">
            <a href="#" data-anio="1">1º Año</a>
            <a href="#" data-anio="2">2º Año</a>
            <a href="#" data-anio="3">3º Año</a>
            <a href="#" data-anio="4">4º Año</a>
            <a href="#" data-anio="5">5º Año</a>
            <a href="#" data-anio="6">6º Año</a>
            <a href="#" data-anio="7">7º Año</a>
        </div>
    </div>

    <!-- Dropdown Modalidad (oculto al principio) -->
    <div class="dropdown" id="dropdownModalidad" style="display:none;">
        <button class="dropbtn">Modalidad ▾</button>
        <div class="dropdown-content" id="listaModalidad">
            <a href="#" data-modalidad="Informatica">Informatica</a>
            <a href="#" data-modalidad="Alimentos">Alimentos</a>
            <a href="#" data-modalidad="Media">Media</a>
        </div>
    </div>

    <!-- Dropdown Materia (oculto al principio) -->
    <div class="dropdown" id="dropdownMateria" style="display:none;">
        <button class="dropbtn">Materia ▾</button>
        <div class="dropdown-content" id="listaMaterias">
            <!-- Se llenará dinámicamente según año + modalidad -->
        </div>
    </div>

</section>

