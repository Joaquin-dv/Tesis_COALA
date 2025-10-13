# COALA - Plataforma de Apuntes Académicos

COALA es una aplicación web diseñada para estudiantes que facilita el intercambio y acceso a apuntes académicos. Permite a los usuarios subir, explorar, calificar y gestionar materiales de estudio de manera colaborativa, con un sistema de moderación basado en inteligencia artificial.

## Características Principales

### Gestión de Apuntes
- **Subida de Apuntes**: Los usuarios pueden subir apuntes en formato PDF con metadatos como título, descripción, materia, escuela y año lectivo.
- **Moderación Automática**: Integración con DocumentAI para procesar y moderar automáticamente los documentos subidos, verificando contenido apropiado.
- **Estados de Apuntes**: Los apuntes pasan por estados como pendiente, en revisión, aprobado o rechazado.
- **Prevención de Duplicados**: Sistema que detecta archivos duplicados mediante hash SHA256.

### Exploración y Descubrimiento
- **Página de Exploración**: Navega por apuntes aprobados con filtros por materia, escuela y año.
- **Sistema de Calificación**: Los usuarios pueden puntuar los apuntes para ayudar a otros en la selección.
- **Apuntes Favoritos**: Guarda apuntes en una lista personal de favoritos.

### Gestión Personal
- **Mochila (Backpack)**: Panel personal donde los usuarios ven sus apuntes organizados por estado (aprobados, rechazados, en revisión, favoritos).
- **Perfil de Usuario**: Información básica del usuario con nombre completo y escuela asociada.

### Seguridad y Acceso
- **Autenticación**: Sistema de login/registro con verificación por email.
- **Protección de Rutas**: Diferentes secciones requieren autenticación según el tipo de usuario.
- **Sesiones Seguras**: Manejo de sesiones para mantener la autenticación.

## Tecnologías Utilizadas

- **Backend**: PHP 7+
- **Base de Datos**: MySQL con procedimientos almacenados
- **Motor de Plantillas**: Mopla (motor personalizado)
- **Envío de Emails**: PHPMailer
- **Procesamiento de Documentos**: DocumentAI (integración con IA)
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Arquitectura**: MVC (Model-View-Controller)

## Estructura del Proyecto

```
c:/xampp/htdocs/COALA/Tesis_COALA/
├── index.php                 # Punto de entrada y router
├── api/                      # API endpoints
├── controllers/              # Controladores MVC
├── models/                   # Modelos de datos
├── views/                    # Plantillas y vistas
│   ├── static/               # Archivos estáticos (CSS, JS, imágenes)
│   └── extends/              # Componentes reutilizables
├── libs/                     # Librerías externas y personalizadas
└── README.md                 # Este archivo
```

## Instalación y Configuración

### Prerrequisitos
- Servidor web (Apache/Nginx) con PHP 7.0+
- MySQL 5.7+
- Composer (para dependencias PHP si es necesario)
- XAMPP o similar para desarrollo local

### Pasos de Instalación

1. **Clonar el repositorio**:
   ```bash
   git clone <url-del-repositorio>
   cd Tesis_COALA
   ```

2. **Configurar la base de datos**:
   - Crear una base de datos MySQL
   - Ejecutar los scripts SQL para crear tablas y procedimientos almacenados
   - Configurar las credenciales en `.env.php`

3. **Configurar variables de entorno**:
   - Copiar `.env.example.php` a `.env.php`
   - Configurar:
     - Credenciales de base de datos
     - Claves de API para DocumentAI
     - Configuración de email (SMTP)

4. **Instalar dependencias**:
   ```bash
   composer install  # Si usa Composer
   ```

5. **Configurar permisos**:
   - Asegurar que el directorio `data/uploads/` tenga permisos de escritura
   - Configurar permisos para archivos temporales

6. **Acceder a la aplicación**:
   - Abrir `http://localhost/Tesis_COALA/` en el navegador

## Uso de la Aplicación

### Para Nuevos Usuarios
1. Acceder a la página de inicio (landing)
2. Registrarse con email y datos personales
3. Verificar la cuenta mediante el email recibido
4. Iniciar sesión

### Subir Apuntes
1. Desde la página de inicio, hacer clic en "Subir Apunte"
2. Completar el formulario con título, descripción, materia, etc.
3. Seleccionar el archivo PDF
4. El sistema procesará automáticamente el documento con IA

### Explorar Apuntes
1. Ir a la sección "Explorar"
2. Navegar por los apuntes disponibles
3. Usar filtros para buscar por materia o escuela
4. Ver detalles y calificar apuntes

### Gestionar Mochila
1. Acceder a "Mochila" desde el menú
2. Ver apuntes personales organizados por estado
3. Gestionar favoritos

## API

La aplicación incluye endpoints API en `/api/` para integraciones externas:

- `POST /api/apuntes` - Subir apunte
- `GET /api/apuntes` - Obtener apuntes
- `GET /api/apuntes/{id}` - Detalles de apunte específico

## Contribución

Para contribuir al proyecto:

1. Fork el repositorio
2. Crear una rama para la nueva funcionalidad
3. Realizar los cambios siguiendo las convenciones del código
4. Enviar un Pull Request

## Licencia

Este proyecto es parte de una tesis académica. Consultar con los autores para términos de uso.

## Autores

- Desarrollado como parte del proyecto de tesis COALA
- Institución: E.E.S.T. N°3

## Contacto

Para preguntas o soporte, contactar a los desarrolladores del proyecto.