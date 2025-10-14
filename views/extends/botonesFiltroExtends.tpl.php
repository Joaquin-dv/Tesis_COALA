<section class="filtros">

    <!-- Dropdown Año -->
    <div class="dropdown">
        <button class="dropbtn">Año ▾</button>
        <div class="dropdown-content" id="listaAnios">
            {{ ANIOS_LECTIVOS }}
        </div>
    </div>

    <!-- Dropdown Materia (oculto al principio) -->
    <div class="dropdown" id="dropdownMateria" style="display:none;">
        <button class="dropbtn">Materia ▾</button>
        <div class="dropdown-content" id="listaMaterias">
            <!-- Se llenará dinámicamente según año -->
        </div>
    </div>

</section>

