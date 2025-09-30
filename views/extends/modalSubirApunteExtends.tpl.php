<dialog id="modal">
    <section class="contenido_modal">
        <i id="cerrar_modal" class="fa-solid fa-x"></i>
        <section class="imagen">
            <img id="koala_modal" src="views/static/img/modal/koala.png" alt="">
        </section>
        <section class="contenido_formulario">
            <span class="form_titulo poppins-bold">Subi tu apunte a COALA</span>
            <form action="?slug=inicio" method="POST" id="formulario" enctype="multipart/form-data">
                <input type="text" name="titulo" id="titulo" class="campo_modal poppins-semibold" placeholder="Titulo del apunte">
                <!-- <input type="text" name="materia" id="materia" class="campo_modal poppins-semibold" placeholder="Materia"> -->
                <select name="materia" id="" class="campo_modal poppins-semibold">
                    <option value="">Materia</option>
                    <option value="1">Matematicas</option>
                </select>
                <section class="curso_division">
                    <select name="curso" id="curso" class="campo_modal poppins-semibold">
                        <option value="">Curso</option>
                        <option value="1">1</option>
                        {{ CURSOS }}
                    </select>
                    <select name="division" id="division" class="campo_modal poppins-semibold">
                        <option value="">Division</option>
                        <option value="1">1</option>
                        {{ DIVISIONES }}
                    </select>
                </section>
                <input type="text" name="profesor" id="profesor" class="campo_modal poppins-semibold" placeholder="Profesor">
                <textarea id="descripcion" class="campo_modal poppins-semibold" name="descripcion" rows="4" cols="50" placeholder="Descripción"></textarea>
                <input type="file" name="btn_subir_archivo" id="btn_subir_arch" class="poppins-semibold" value="Subir archivo">
                <label for="btn_subir_arch" class="btn_label poppins-semibold">
                    <i class="fa-solid fa-file-arrow-up"></i>
                    Subir archivo
                </label>
                <button id="subir_apunte" name="btn_subir_apunte" type="submit" class="btn_modal poppins-semibold">Subir Apunte</button>
                <div class="errorMsg" id="errorGeneral">{{ MSG_ERROR }}</div>
            </form>
        </section>
    </section>
</dialog>