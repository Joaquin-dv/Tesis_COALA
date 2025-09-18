<dialog id="modal">
    <section class="contenido_modal">
        <i id="cerrar_modal" class="fa-solid fa-x"></i>
        <form action="?slug=inicio" method="POST" id="form_subir_apunte" enctype="multipart/form-data">
            <label for="titulo">Titulo</label>
            <input type="text" name="titulo" id="titulo" placeholder="Titulo" class="campo_modal">
            <label for="descripcion">Descripcion</label>
            <input type="text" name="descripcion" id="descripcion" placeholder="Descripcion" class="campo_modal">
            <button id="subir_archivo" class="btn_modal">Agregar archivo</button>
            <section class="datos_apunte">
                <div class="input_label">
                    <label for="curso">Curso</label>
                    <select name="curso" id="curso" class="campo_modal">
                        <option value="">Seleccionar</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                    </select>
                </div>
                <div class="input_label">
                    <label for="division">Division</label>
                    <select name="division" id="division" class="campo_modal">
                        <option value="">Seleccionar</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                    </select>
                </div>
                <div class="input_label">
                    <label for="materia">Materia</label>
                    <select name="materia" id="materia" class="campo_modal">
                        <option value="">Seleccionar</option>
                        <option value="1">Cálculo I</option>
                        <option value="2">Cálculo II</option>
                        <option value="3">Física I</option>
                        <option value="4">Física II</option>
                        <option value="5">Álgebra</option>
                        <option value="6">Química</option>
                    </select>
                </div>
            </section>
            <button id="subir_apunte" name="btn_subir_apunte" type="submit" class="btn_modal">Subir Apunte</button>
        </form>
    </section>
</dialog>