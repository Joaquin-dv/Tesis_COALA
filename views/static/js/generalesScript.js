// Menu desplegable del perfil
document.addEventListener("DOMContentLoaded", () => {
    const perfilImg = document.getElementById("perfil-img");
    const menu = document.getElementById("menu-desplegable");

    perfilImg.addEventListener("click", () => {
        menu.classList.toggle("oculto");
    });

    // Cierra el menú si se hace clic fuera
    document.addEventListener("click", (e) => {
        if (!perfilImg.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add("oculto");
        }
    });
});

function checkDemoOrInvitadoUser() {
    if (typeof window.userRole !== 'undefined' && (window.userRole === 'demo' || window.userRole === 'Invitado')) {
        // Mostrar mensaje de que debe crear cuenta
        let titleText = window.userRole === 'demo' ? 'Funcionalidad no disponible' : 'Funcionalidad no disponible';
        let bodyText = window.userRole === 'demo' ? 'Para acceder a esta funcionalidad, debes crear una cuenta.' : 'Para acceder a esta funcionalidad, debes iniciar sesion.';
        let confirmButtonText = window.userRole === 'demo' ? 'Crear cuenta' : 'Iniciar sesion';
        let redirectSlug = window.userRole === 'demo' ? '?slug=registerButton' : '?slug=login';

        Swal.fire({
            title: titleText,
            text: bodyText,
            icon: 'info',
            confirmButtonText: confirmButtonText,
            showCancelButton: true,
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = redirectSlug;
            }
        });
        return false; // Previene la acción
    }
    return true; // Permite la acción
}

// Función para verificar si es usuario demo
function checkDemoUser() {
    // Verificar si existe la sesión y si el rol es demo
    // Nota: En PHP, la sesión se pasa a JS a través de una variable global
    // Asumiendo que se define window.userRole en algún lugar del PHP
    if (typeof window.userRole !== 'undefined' && window.userRole === 'demo') {
        // Mostrar mensaje de que debe crear cuenta
        Swal.fire({
            title: 'Funcionalidad no disponible',
            text: 'Para acceder a esta funcionalidad, debes crear una cuenta.',
            icon: 'info',
            confirmButtonText: 'Crear cuenta',
            showCancelButton: true,
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '?slug=registerButton';
            }
        });
        return false; // Previene la acción
    }
    return true; // Permite la acción
}

// Función para verificar si es usuario demo
function checkInvitadoUser() {
    // Verificar si existe la sesión y si el rol es demo
    // Nota: En PHP, la sesión se pasa a JS a través de una variable global
    // Asumiendo que se define window.userRole en algún lugar del PHP
    if (typeof window.userRole !== 'undefined' && window.userRole === 'Invitado') {
        // Mostrar mensaje de que debe crear cuenta
        Swal.fire({
            title: 'Funcionalidad no disponible',
            text: 'Para acceder a esta funcionalidad, debes iniciar sesion.',
            icon: 'info',
            confirmButtonText: 'Iniciar sesion',
            showCancelButton: true,
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '?slug=login';
            }
        });
        return false; // Previene la acción
    }
    return true; // Permite la acción
}

// Inicializar verificación de procesamiento activo al cargar la página
document.addEventListener("DOMContentLoaded", function() {
    checkActiveProcessing();
});

// Variables globales para mantener el estado del procesamiento
window.processingIntervals = new Map(); // Mapa para almacenar intervalos de polling por processing_id

// Función para verificar si hay procesamiento activo al cargar la página
function checkActiveProcessing() {
    const activeProcessing = sessionStorage.getItem('activeProcessing');
    if (activeProcessing) {
        try {
            const processingData = JSON.parse(activeProcessing);
            // Importar dinámicamente el módulo de toast si no está disponible
            if (typeof procesando === 'function') {
                procesando();
                // Reanudar polling si es necesario
                startPolling(processingData.processingId);
            } else {
                import('./modules/toastModule.js').then(module => {
                    module.procesando();
                    startPolling(processingData.processingId);
                }).catch(err => {
                    console.error('Error al importar toastModule:', err);
                });
            }
        } catch (error) {
            sessionStorage.removeItem('activeProcessing');
        }
    }
}

// Función para iniciar procesamiento de documento (versión global)
window.startDocumentProcessing = async function(apunte_id) {
    const formData = new FormData();
    formData.append('model', 'Apuntes');
    formData.append('method', 'startProcessing');
    formData.append('apunte_id', apunte_id);

    try {
        const response = await fetch('api/index.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.errno === 200) {
            // Almacenar estado de procesamiento en sessionStorage
            sessionStorage.setItem('activeProcessing', JSON.stringify({
                processingId: result.processing_id,
                apunteId: apunte_id,
                timestamp: Date.now()
            }));

            // Mostrar notificación de procesamiento usando toast
            if (typeof procesando === 'function') {
                procesando();
            } else {
                import('./modules/toastModule.js').then(module => {
                    module.procesando();
                }).catch(err => {
                    console.error('Error al importar toastModule:', err);
                });
            }
            startPolling(result.processing_id);
        } else {
            console.error('Error al iniciar procesamiento:', result.error);
        }
    } catch (error) {
        console.error('Error en startDocumentProcessing:', error);
    }
};

// Función para hacer polling del estado de procesamiento (versión global)
window.startPolling = function(processingId) {
    let processed = false;
    const pollInterval = setInterval(async () => {
        if (processed) return; // Deja de hacer polling si ya esta procesado

        try {
            const response = await fetch(`api/index.php?model=Apuntes&method=checkProcessingStatus&processing_id=${processingId}`);
            const data = await response.json();

            if (data.status === 'completed' && !processed) {
                processed = true;
                clearInterval(pollInterval);
                window.processingIntervals.delete(processingId);
                // Limpiar sessionStorage
                sessionStorage.removeItem('activeProcessing');
                // Cerrar toast de procesamiento
                if (typeof cerrarProcesando === 'function') {
                    cerrarProcesando();
                } else {
                    import('./modules/toastModule.js').then(module => {
                        module.cerrarProcesando();
                    });
                }
                showProcessingResult(data.result);
            } else if (data.status === 'error' && !processed) {
                processed = true;
                clearInterval(pollInterval);
                window.processingIntervals.delete(processingId);
                // Limpiar sessionStorage
                sessionStorage.removeItem('activeProcessing');
                // Cerrar toast de procesamiento
                if (typeof cerrarProcesando === 'function') {
                    cerrarProcesando();
                } else {
                    import('./modules/toastModule.js').then(module => {
                        module.cerrarProcesando();
                    });
                }
                console.error('Error en procesamiento:', data.message);
            }
            // Si sigue processing, continúa haciendo polling
        } catch (error) {
            console.error('Error en polling:', error);
        }
    }, 2000); // Poll cada 2 segundos

    // Almacenar el intervalo para poder limpiarlo si es necesario
    window.processingIntervals.set(processingId, pollInterval);
};

// Función para mostrar resultado del procesamiento (versión global)
window.showProcessingResult = function(result) {
    if (result.status === 'approved') {
        if (typeof aprobado === 'function') {
            aprobado();
        } else {
            import('./modules/toastModule.js').then(module => {
                module.aprobado();
            });
        }
    } else {
        if (typeof rechazado === 'function') {
            rechazado();
        } else {
            import('./modules/toastModule.js').then(module => {
                module.rechazado();
            });
        }
    }
};
