<article class="apunte_mochila">
    <header class="encabezado_apunte">
        <section class="info_encabezado_apunte">
            <h2>{{ TITULO }}</h2>
            <p>{{ AÑO }}</p>
            <p>{{ MATERIA }} - {{ ESCUELA }} <!-- | Prof. {{ PROFESOR }} --></p>
        </section>
        <figure><img src="{{ IMAGEN }}" alt=""></figure>
    </header>
    <section class="cuerpo_apunte">
        <p>{{ DESCRIPCION }}</p>
        <p>Contiene 2 archivos</p>
    </section>
    <footer>
        <p>⭐ {{ PUNTUACION }} | 👁️ 123</p>
        <button>Ver mas</button>
    </footer>
</article>