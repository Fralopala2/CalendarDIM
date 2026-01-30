# 📅 CalendarDIM - Sistema de Gestión de Calendario

Sistema de calendario completo y moderno desarrollado con PHP, MySQL, y FullCalendar. Incluye gestión de eventos, cumpleaños, interfaz responsive y versión móvil para Android.

## 🚀 Características Principales

### 📌 Gestión de Eventos
- **Crear, editar y eliminar eventos** con fechas de inicio y fin
- **Horarios opcionales** para especificar horas exactas
- **Descripciones detalladas** para cada evento
- **10 colores personalizables** para categorizar eventos
- **Drag & Drop** para cambiar fechas fácilmente
- **Modal unificado** para gestión intuitiva

### 🎂 Gestión de Cumpleaños
- **Recurrencia automática anual** - Los cumpleaños se muestran en años anteriores, actual y siguiente
- **Emoji de pastel** (🎂) como icono distintivo
- **5 colores específicos** para cumpleaños
- **Drag & Drop** para ajustar fechas
- **Sistema de recordatorios** integrado en el calendario

### 🎨 Interfaz de Usuario
- **Sidebar colapsible** con listado cronológico de eventos del día
- **Timeline ordenado por horas** para visualizar el día completo
- **Diseño responsive** - Optimizado para móvil, tablet y desktop
- **Colores dinámicos por mes** - Cada mes tiene su propia paleta de colores
- **Banner adaptativo** (140px desktop, 80px tablet, 70px móvil)
- **Swipe horizontal** en móvil/tablet para navegación rápida
- **Interfaz completamente en español**
- **Botón "Hoy"** para volver rápidamente al día actual

### 💻 Tecnología
- **100% Offline** - No requiere conexión a internet después de la instalación
- **Todas las librerías incluidas** - jQuery, Bootstrap, FullCalendar, Moment.js
- **Base de datos local** MySQL/MariaDB
- **Arquitectura orientada a objetos** con EventManager y BirthdayManager
- **Prepared Statements** para seguridad contra SQL injection

## 🎯 Requisitos del Sistema

### Software Requerido:
- **PHP 7.4 o superior**
- **MySQL 5.7 o superior** (o MariaDB 10.x)
- **Servidor web local** (Apache, Nginx, o servidor integrado de PHP)

### Opciones Recomendadas (Todo-en-uno):
- **XAMPP** (Windows/Mac/Linux) - https://www.apachefriends.org/
- **WAMP** (Windows) - https://www.wampserver.com/
- **MAMP** (Mac) - https://www.mamp.info/
- **Laragon** (Windows) - https://laragon.org/

## � Instalación Paso a Paso

### Opción 1: Con XAMPP (Recomendado para Principiantes)

1. **Descargar e instalar XAMPP:**
   - Ir a https://www.apachefriends.org/
   - Descargar la versión para tu sistema operativo
   - Instalar siguiendo las instrucciones del instalador

2. **Iniciar servicios:**
   - Abrir XAMPP Control Panel
   - Iniciar **Apache** y **MySQL**
   - Verificar que ambos estén en estado "Running" (verde)

3. **Copiar archivos del proyecto:**
   - Copiar la carpeta `calendario` a:
     - **Windows:** `C:\xampp\htdocs\`
     - **Mac:** `/Applications/XAMPP/htdocs/`
     - **Linux:** `/opt/lampp/htdocs/`

4. **Crear y configurar base de datos:**
   - Abrir navegador y ir a http://localhost/phpmyadmin
   - Clic en "Nueva" en el panel izquierdo
   - Nombre de la base de datos: `calendario`
   - Cotejamiento: `utf8_spanish_ci`
   - Clic en "Crear"
   - Seleccionar la base de datos `calendario`
   - Ir a pestaña "Importar"
   - Clic en "Seleccionar archivo"
   - Seleccionar `calendario/database/calendario_enhanced.sql`
   - Clic en "Continuar"

5. **Configurar conexión:**
   - Abrir el archivo `calendario/PHP/config.php`
   - Verificar/ajustar los datos de conexión:
     ```php
     $usuario  = "root";
     $password = "";  // En XAMPP por defecto está vacío
     $servidor = "localhost";
     $basededatos = "calendario";
     ```
   - **Nota:** Si usas otro entorno (WAMP, MAMP, etc.), ajusta las credenciales según corresponda

6. **Acceder al calendario:**
   - Abrir navegador
   - Ir a http://localhost/calendario/
   - ¡Listo! Ya puedes empezar a usar el calendario

### Opción 2: Con Servidor PHP Integrado (Para Desarrolladores)

1. **Verificar instalaciones:**
   ```bash
   php --version  # Debe mostrar PHP 7.4 o superior
   mysql --version  # Verificar MySQL instalado
   ```

2. **Crear base de datos:**
   ```bash
   mysql -u root -p
   CREATE DATABASE calendario CHARACTER SET utf8 COLLATE utf8_spanish_ci;
   USE calendario;
   SOURCE /ruta/a/calendario/database/calendario_enhanced.sql;
   EXIT;
   ```

3. **Configurar conexión en config.php** (ajustar credenciales si es necesario)

4. **Ejecutar servidor PHP:**
   ```bash
   cd /ruta/a/calendario
   php -S localhost:8000
   ```

5. **Acceder en:** http://localhost:8000


## 📁 Estructura del Proyecto

```
CalendarDIM/
├── calendario/                      # Aplicación web principal
│   ├── index.php                    # Página principal del calendario
│   ├── PHP/                         # Backend y lógica del servidor
│   │   ├── config.php              # Configuración de base de datos
│   │   ├── EventManager.php        # Clase para gestión de eventos
│   │   ├── BirthdayManager.php     # Clase para gestión de cumpleaños
│   │   ├── nuevoEvento.php         # Crear nuevos eventos
│   │   ├── UpdateEvento.php        # Actualizar eventos existentes
│   │   ├── deleteEvento.php        # Eliminar eventos
│   │   ├── processBirthday.php     # Procesar cumpleaños
│   │   ├── deleteBirthday.php      # Eliminar cumpleaños
│   │   ├── updateBirthdayDate.php  # Actualizar fecha de cumpleaños
│   │   ├── drag_drop_evento.php    # Drag & Drop de eventos
│   │   ├── getEventDetails.php     # Obtener detalles de eventos
│   │   ├── getEventsForDay.php     # Eventos de un día específico
│   │   ├── getSidebarEvents.php    # Eventos para la barra lateral
│   │   ├── getSidebarContent.php   # Contenido de la barra lateral
│   │   ├── modalUnifiedEvent.php   # Modal unificado de eventos/cumpleaños
│   │   └── birthday_config.php     # Configuración de cumpleaños
│   ├── css/                         # Estilos y diseño
│   │   ├── bootstrap.min.css       # Framework Bootstrap 4
│   │   ├── fullcalendar.min.css    # Estilos de FullCalendar
│   │   ├── fullcalendar-fix.css    # Correcciones personalizadas
│   │   ├── home.css                # Estilos desktop
│   │   ├── home-tablet.css         # Estilos tablet
│   │   └── home-mobile.css         # Estilos móvil
│   ├── js/                          # JavaScript y librerías
│   │   ├── jquery-3.0.0.min.js     # jQuery 3.0
│   │   ├── bootstrap.min.js        # Bootstrap JS
│   │   ├── moment.min.js           # Moment.js para fechas
│   │   ├── fullcalendar.min.js     # FullCalendar 3.x
│   │   └── popper.min.js           # Popper.js para tooltips
│   ├── locales/                     # Archivos de localización
│   │   └── es.js                   # Idioma español para FullCalendar
│   ├── database/                    # Scripts de base de datos
│   │   └── calendario_enhanced.sql # Schema completo de la BD
│   └── IMAGES/                      # Recursos gráficos
│       └── ImagenAgenda.svg        # Icono de la aplicación
│
├── calendario-android/              # Versión móvil con Apache Cordova
│   ├── package.json                 # Dependencias Node.js/Cordova
│   ├── platforms/                   # Plataformas compiladas
│   │   └── android/                # Build Android (APK)
│   ├── plugins/                     # Plugins de Cordova
│   └── www/                         # Assets web para la app móvil
│
├── README.md                        # Este archivo
└── LICENSE                          # Licencia MIT
```


## �️ Base de Datos

### Estructura de Tablas

El sistema utiliza 3 tablas principales:

#### 1. `eventoscalendar` - Eventos del Calendario
```sql
Campos principales:
- id (INT) - Identificador único
- evento (VARCHAR) - Título del evento
- fecha_inicio (VARCHAR) - Fecha de inicio
- fecha_fin (VARCHAR) - Fecha de finalización
- hora_inicio (TIME) - Hora opcional del evento
- color_evento (VARCHAR) - Color del evento (10 opciones)
- descripcion (TEXT) - Descripción detallada
- es_recurrente (TINYINT) - Si el evento es recurrente
- dias_semana (VARCHAR) - Días de repetición (para eventos recurrentes)
- fecha_fin_recurrencia (DATE) - Límite de repetición
- evento_padre_id (INT) - ID del evento padre (para instancias)
- recurring_group_id (VARCHAR) - Agrupación de eventos recurrentes
```

#### 2. `cumpleañoscalendar` - Cumpleaños
```sql
Campos principales:
- id (INT) - Identificador único
- nombre (VARCHAR) - Nombre de la persona
- dia_nacimiento (INT) - Día del mes (1-31)
- mes_nacimiento (INT) - Mes del año (1-12)
- color_cumpleanos (VARCHAR) - Color del cumpleaños (5 opciones)
- created_at (TIMESTAMP) - Fecha de creación
- updated_at (TIMESTAMP) - Última actualización
```

#### 3. `migrations` - Control de Migraciones
```sql
Campos:
- id (INT) - Identificador
- migration_name (VARCHAR) - Nombre de la migración
- executed_at (TIMESTAMP) - Fecha de ejecución
```

### Importación de Base de Datos

#### Método 1: phpMyAdmin (Recomendado)
1. Acceder a http://localhost/phpmyadmin
2. Crear base de datos `calendario` con cotejamiento `utf8_spanish_ci`
3. Seleccionar la base de datos creada
4. Ir a pestaña "Importar"
5. Seleccionar archivo `calendario/database/calendario_enhanced.sql`
6. Clic en "Continuar"

#### Método 2: Línea de Comandos
```bash
# Crear base de datos
mysql -u root -p -e "CREATE DATABASE calendario CHARACTER SET utf8 COLLATE utf8_spanish_ci;"

# Importar schema
mysql -u root -p calendario < calendario/database/calendario_enhanced.sql
```

#### Método 3: Manual (SQL)
```sql
CREATE DATABASE calendario CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE calendario;
-- Copiar y ejecutar el contenido de calendario_enhanced.sql
```


## 🔍 Solución de Problemas

### ❌ Error de conexión a base de datos
**Síntomas:** Mensaje "Error de conexion: No se pudo conectar a la base de datos MySQL"

**Soluciones:**
1. Verificar que MySQL esté iniciado en XAMPP Control Panel
2. Comprobar credenciales en `calendario/PHP/config.php`:
   ```php
   $usuario  = "root";
   $password = "";  // O tu contraseña si la cambiaste
   ```
3. Verificar que el servidor sea `localhost` (o `127.0.0.1`)
4. Asegurar que la base de datos `calendario` existe en phpMyAdmin

### ❌ Error "La base de datos no existe"
**Síntomas:** Mensaje "No se pudo seleccionar la base de datos 'calendario'"

**Soluciones:**
1. Ir a http://localhost/phpmyadmin
2. Crear base de datos `calendario`
3. Importar archivo `calendario/database/calendario_enhanced.sql`
4. Recargar la página del calendario

### ❌ Página en blanco o sin contenido
**Síntomas:** La página se carga pero está vacía

**Soluciones:**
1. Verificar que Apache esté funcionando en XAMPP
2. Revisar errores PHP:
   - Abrir `C:\xampp\php\php.ini`
   - Buscar `display_errors` y establecer en `On`
   - Reiniciar Apache
3. Verificar ruta correcta: http://localhost/calendario/
4. Revisar la consola del navegador (F12) para errores JavaScript

### ❌ Modal no se abre al crear evento
**Síntomas:** Al hacer clic en "+ Nuevo" no aparece el modal

**Soluciones:**
1. Verificar que JavaScript esté habilitado en el navegador
2. Abrir consola del navegador (F12) y buscar errores
3. Verificar que jQuery, Bootstrap y FullCalendar estén cargados:
   ```javascript
   // En consola del navegador:
   console.log(typeof jQuery);  // Debe mostrar "function"
   console.log(typeof $.fn.fullCalendar);  // Debe mostrar "function"
   ```
4. Limpiar caché del navegador (Ctrl+Shift+Del)
5. Recargar página con Ctrl+F5

### ❌ Los eventos no se muestran en el calendario
**Síntomas:** La página carga pero no aparecen eventos

**Soluciones:**
1. Verificar que la base de datos tenga datos:
   ```sql
   SELECT * FROM eventoscalendar;
   SELECT * FROM cumpleañoscalendar;
   ```
2. Verificar conexión a BD en `config.php`
3. Revisar consola del navegador para errores AJAX
4. Verificar permisos de archivo `PHP/config.php`

### ❌ Error al arrastrar eventos (Drag & Drop)
**Síntomas:** No se pueden mover eventos arrastrándolos

**Soluciones:**
1. Verificar que `editable: true` esté en la configuración de FullCalendar
2. Revisar que el archivo `PHP/drag_drop_evento.php` exista
3. Comprobar permisos de escritura en la base de datos
4. Verificar que no haya errores JavaScript en consola (F12)

### ❌ Problemas con caracteres especiales (ñ, tildes)
**Síntomas:** Los textos con acentos se ven mal

**Soluciones:**
1. Verificar que la base de datos use `utf8_spanish_ci`:
   ```sql
   ALTER DATABASE calendario CHARACTER SET utf8 COLLATE utf8_spanish_ci;
   ```
2. Asegurar que `php.ini` tenga:
   ```ini
   default_charset = "UTF-8"
   ```
3. Verificar que los archivos PHP tengan codificación UTF-8
4. Reiniciar Apache después de cambios

### 💡 Modo Debug

Para activar mensajes de error detallados:

1. Editar `calendario/PHP/config.php` y agregar al inicio:
   ```php
   <?php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```

2. Revisar logs de PHP:
   - **Windows XAMPP:** `C:\xampp\php\logs\php_error_log`
   - **Linux:** `/var/log/apache2/error.log`


## �️ Arquitectura Técnica

### Backend (PHP)

#### Clases Principales:

**EventManager.php**
- Gestión completa de eventos del calendario
- Métodos: `saveEvent()`, `createEvent()`, `updateEvent()`, `deleteEvent()`
- Validación de datos con `validateEventData()`
- Uso de Prepared Statements para seguridad
- Soporte para eventos recurrentes

**BirthdayManager.php**
- Gestión de cumpleaños con recurrencia anual automática
- Métodos: `saveBirthday()`, `createBirthday()`, `updateBirthday()`, `deleteBirthday()`
- Validación de fechas (día 1-31, mes 1-12)
- Sistema de colores específicos para cumpleaños

#### Endpoints API:

| Archivo | Método | Descripción |
|---------|--------|-------------|
| `nuevoEvento.php` | POST | Crear nuevo evento |
| `UpdateEvento.php` | POST | Actualizar evento existente |
| `deleteEvento.php` | POST | Eliminar evento |
| `drag_drop_evento.php` | POST | Actualizar fecha al arrastrar |
| `processBirthday.php` | POST | Crear/actualizar cumpleaños |
| `deleteBirthday.php` | POST | Eliminar cumpleaños |
| `updateBirthdayDate.php` | POST | Actualizar fecha de cumpleaños |
| `getEventDetails.php` | GET | Obtener detalles de un evento |
| `getEventsForDay.php` | GET | Eventos de un día específico |
| `getSidebarEvents.php` | GET | Eventos para barra lateral |

### Frontend

#### Librerías JavaScript:
- **jQuery 3.0.0** - Manipulación DOM y AJAX
- **FullCalendar 3.x** - Visualización de calendario
- **Moment.js** - Manejo de fechas y horas
- **Bootstrap 4** - Componentes UI y responsive
- **Popper.js** - Tooltips y popovers

#### Estilos CSS:
- **home.css** - Estilos desktop (1024px+)
- **home-tablet.css** - Estilos tablet (768px-1023px)
- **home-mobile.css** - Estilos móvil (<768px)
- **fullcalendar-fix.css** - Correcciones personalizadas de FullCalendar

### Características Técnicas:

✅ **Arquitectura orientada a objetos** con clases PHP reutilizables  
✅ **Prepared Statements** para prevenir SQL Injection  
✅ **Validación de datos** en cliente y servidor  
✅ **Responsive Design** con breakpoints específicos  
✅ **AJAX** para operaciones sin recargar página  
✅ **Manejo de errores** con try-catch y error_log  
✅ **Caché control** con meta tags y query strings versionadas  
✅ **Localización** completa en español  

## 📊 Funcionalidades Detalladas

### Vista de Calendario
- **Vista mensual** con FullCalendar
- **Colores dinámicos por mes** - Cada mes tiene su paleta de colores única
- **Eventos arrastrables** con confirmación visual
- **Click en día** para ver eventos de ese día en sidebar
- **Botón "Hoy"** para volver al día actual
- **Botones de navegación** (anterior/siguiente mes)
- **Botón "+ Nuevo"** para crear eventos rápidamente

### Sidebar Interactivo
- **Colapsible** - Clic en header para expandir/contraer
- **Indicador visual** (▼/▲) del estado del sidebar
- **Timeline ordenado** - Eventos ordenados cronológicamente por hora
- **Separación visual** entre cumpleaños y eventos
- **Click en evento** abre modal con detalles
- **Responsive** - Se adapta a diferentes tamaños de pantalla

### Modal Unificado
- **Un solo modal** para eventos y cumpleaños
- **Tabs intuitivos** para cambiar entre tipos
- **Validación en tiempo real** de formularios
- **Picker de colores** - 10 colores para eventos, 5 para cumpleaños
- **Campo de hora opcional** para eventos
- **Descripción ampliable** con textarea
- **Botones de acción** (Guardar/Eliminar/Cancelar)

### Sistema de Colores

**Eventos (10 colores):**
- 🔵 Azul (#007bff)
- 🟢 Verde (#28a745)
- 🔴 Rojo (#dc3545)
- 🟡 Amarillo (#ffc107)
- 🟣 Púrpura (#6f42c1)
- 🟠 Naranja (#fd7e14)
- 🔵 Cian (#17a2b8)
- ⚫ Oscuro (#343a40)
- 🟤 Marrón (#795548)
- 🔵 Índigo (#6610f2)

**Cumpleaños (5 colores):**
- 🩷 Rosa (#FF69B4)
- 💜 Morado (#9370DB)
- 💙 Azul Claro (#87CEEB)
- 💚 Verde Menta (#98FB98)
- 🧡 Naranja Coral (#FF7F50)

**Colores de Meses (Header):**
Cada mes tiene su color distintivo en el header del calendario:
- Enero: Índigo, Febrero: Rosa, Marzo: Verde, Abril: Naranja
- Mayo: Cian, Junio: Púrpura, Julio: Amarillo, Agosto: Naranja profundo
- Septiembre: Marrón, Octubre: Gris azulado, Noviembre: Azul gris, Diciembre: Azul

## 📱 Versión Android

El proyecto incluye una versión móvil nativa para Android desarrollada con **Apache Cordova**.

### Características de la App Android:
- **Mismo código base** que la versión web
- **Funciona offline** una vez instalada
- **Interfaz optimizada** para pantallas táctiles
- **APK lista para instalar** (en `calendario-android/platforms/android/app/build/outputs/apk/`)

### Compilar la App Android:

#### Requisitos Previos:
- **Node.js** (v14 o superior)
- **Android Studio** con Android SDK
- **Cordova CLI**: `npm install -g cordova`

#### Pasos para Compilar:
```bash
cd calendario-android

# Instalar dependencias
npm install

# Agregar plataforma Android (si no existe)
cordova platform add android

# Compilar APK de debug
cordova build android

# Compilar APK de release (firmado)
cordova build android --release
```

#### Ejecutar en Emulador/Dispositivo:
```bash
# Listar dispositivos disponibles
cordova run android --list

# Ejecutar en dispositivo conectado
cordova run android
```

El APK generado estará en:
```
calendario-android/platforms/android/app/build/outputs/apk/debug/app-debug.apk
```

### Instalar en Android:
1. Habilitar "Orígenes desconocidos" en el dispositivo Android
2. Transferir el archivo APK al dispositivo
3. Abrir el APK y seguir las instrucciones de instalación

## 🚀 Guía de Uso

### Crear un Evento
1. Clic en botón **"+ Nuevo"** en la barra superior
2. Seleccionar pestaña **"Evento"** en el modal
3. Completar los campos:
   - **Título**: Nombre del evento
   - **Fecha inicio**: Fecha de inicio del evento
   - **Fecha fin**: Fecha de finalización
   - **Hora** (opcional): Hora específica del evento
   - **Color**: Seleccionar uno de los 10 colores
   - **Descripción** (opcional): Detalles adicionales
4. Clic en **"Guardar"**

### Crear un Cumpleaños
1. Clic en botón **"+ Nuevo"** en la barra superior
2. Seleccionar pestaña **"Cumpleaños"** en el modal
3. Completar los campos:
   - **Nombre**: Nombre de la persona
   - **Día**: Día del mes (1-31)
   - **Mes**: Mes del año (1-12)
   - **Color**: Seleccionar uno de los 5 colores de cumpleaños
4. Clic en **"Guardar"**
5. El cumpleaños se mostrará automáticamente cada año

### Editar Evento o Cumpleaños
- **Opción 1**: Hacer clic en el evento/cumpleaños en el calendario
- **Opción 2**: Hacer clic en el evento/cumpleaños en la sidebar
- Modificar los campos deseados en el modal
- Clic en **"Guardar"** para confirmar cambios

### Eliminar Evento o Cumpleaños
1. Abrir el evento/cumpleaños (clic en calendario o sidebar)
2. Clic en botón **"Eliminar"** en el modal
3. Confirmar la eliminación

### Mover Eventos (Drag & Drop)
1. Hacer clic y mantener presionado sobre el evento
2. Arrastrar a la nueva fecha deseada
3. Soltar el mouse
4. Los cambios se guardan automáticamente

### Ver Eventos de un Día Específico
1. Hacer clic en el número del día en el calendario
2. La sidebar se actualizará mostrando todos los eventos de ese día
3. Los eventos se ordenan cronológicamente por hora

### Navegar por el Calendario
- **Mes anterior/siguiente**: Usar flechas en la barra superior
- **Ir a hoy**: Clic en botón **"Hoy"**
- **Cambiar mes** (móvil/tablet): Deslizar horizontalmente (swipe)

### Colapsar/Expandir Sidebar
- Hacer clic en el **header de la sidebar** (donde aparece la fecha)
- El indicador cambiará de ▼ a ▲ según el estado

## 🔐 Seguridad

El sistema implementa varias medidas de seguridad:

✅ **Prepared Statements** - Todas las consultas SQL usan prepared statements para prevenir SQL Injection  
✅ **Validación de datos** - Validación en cliente (JavaScript) y servidor (PHP)  
✅ **Sanitización de entrada** - Uso de `trim()`, `ucwords()` para limpiar datos  
✅ **Manejo de errores** - Try-catch en todas las operaciones críticas  
✅ **Type checking** - Validación de tipos de datos (int, string, etc.)  
✅ **Charset UTF-8** - Prevención de ataques XSS mediante codificación correcta  

### Recomendaciones Adicionales:
- Cambiar las credenciales de MySQL por defecto en producción
- Configurar contraseña para usuario `root` de MySQL
- Limitar acceso a carpeta `PHP/` mediante `.htaccess` si se expone públicamente
- Mantener PHP y MySQL actualizados

## 💾 Backup y Restauración

### Hacer Backup de la Base de Datos

**Método 1: phpMyAdmin**
1. Ir a http://localhost/phpmyadmin
2. Seleccionar base de datos `calendario`
3. Clic en pestaña "Exportar"
4. Seleccionar "Método rápido" o "Personalizado"
5. Clic en "Continuar"
6. Guardar archivo `.sql` generado

**Método 2: Línea de Comandos**
```bash
# Backup completo
mysqldump -u root -p calendario > backup_calendario_$(date +%Y%m%d).sql

# Backup solo estructura
mysqldump -u root -p --no-data calendario > backup_estructura.sql

# Backup solo datos
mysqldump -u root -p --no-create-info calendario > backup_datos.sql
```

### Restaurar desde Backup

**Método 1: phpMyAdmin**
1. Ir a http://localhost/phpmyadmin
2. Seleccionar/crear base de datos `calendario`
3. Clic en "Importar"
4. Seleccionar archivo backup `.sql`
5. Clic en "Continuar"

**Método 2: Línea de Comandos**
```bash
mysql -u root -p calendario < backup_calendario_20260130.sql
```

## 📈 Rendimiento y Optimización

### Optimizaciones Implementadas:

✅ **Índices en base de datos** - Índices en campos frecuentemente consultados  
✅ **AJAX para operaciones** - Sin recargas de página completas  
✅ **Caché control** - Meta tags para controlar caché del navegador  
✅ **Archivos minificados** - jQuery, Bootstrap, FullCalendar minificados  
✅ **Carga condicional** - Scripts se cargan solo cuando son necesarios  
✅ **CSS responsive** - Media queries específicas por dispositivo  

### Métricas Esperadas:
- **Tiempo de carga inicial**: < 2 segundos (localhost)
- **Operaciones AJAX**: < 500ms
- **Renderizado calendario**: < 1 segundo
- **Tamaño total**: ~2MB (incluyendo todas las librerías)

## 🧪 Testing y Desarrollo

### Verificar Instalación

Después de instalar, verificar que todo funciona:

- [ ] El calendario se muestra correctamente
- [ ] Se puede crear un evento de prueba
- [ ] Se puede crear un cumpleaños de prueba
- [ ] Los eventos aparecen en el calendario
- [ ] El drag & drop funciona
- [ ] La sidebar muestra eventos al hacer clic en un día
- [ ] El modal se abre al hacer clic en "+ Nuevo"
- [ ] Se pueden editar y eliminar eventos
- [ ] Los colores se aplican correctamente
- [ ] La navegación entre meses funciona

### Modo Desarrollo

Para desarrollo activo, habilitar errores:

```php
// Agregar al inicio de calendario/PHP/config.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
```

### Logs de Debug

Revisar logs para detectar errores:

**Windows (XAMPP):**
- Apache: `C:\xampp\apache\logs\error.log`
- PHP: `C:\xampp\php\logs\php_error_log`
- MySQL: `C:\xampp\mysql\data\mysql_error.log`

**Linux:**
- Apache: `/var/log/apache2/error.log`
- PHP: `/var/log/php_errors.log`
- MySQL: `/var/log/mysql/error.log`

## 🌐 Compatibilidad de Navegadores

| Navegador | Versión Mínima | Estado |
|-----------|----------------|--------|
| Chrome | 90+ | ✅ Totalmente compatible |
| Firefox | 88+ | ✅ Totalmente compatible |
| Safari | 14+ | ✅ Totalmente compatible |
| Edge | 90+ | ✅ Totalmente compatible |
| Opera | 76+ | ✅ Totalmente compatible |
| Internet Explorer | - | ❌ No compatible |

### Características por Dispositivo:

| Característica | Desktop | Tablet | Móvil |
|----------------|---------|--------|-------|
| Vista completa | ✅ | ✅ | ✅ |
| Drag & Drop | ✅ | ✅ | ✅ |
| Sidebar | ✅ | ✅ Colapsible | ✅ Colapsible |
| Swipe navigation | ❌ | ✅ | ✅ |
| Modal completo | ✅ | ✅ | ✅ Adaptado |
| Todas funciones | ✅ | ✅ | ✅ |

## 📚 Recursos y Documentación

### Librerías Utilizadas:
- **FullCalendar** - https://fullcalendar.io/docs/v3
- **jQuery** - https://api.jquery.com/
- **Bootstrap 4** - https://getbootstrap.com/docs/4.6/
- **Moment.js** - https://momentjs.com/docs/
- **Apache Cordova** - https://cordova.apache.org/docs/

### Tecnologías:
- **PHP** - https://www.php.net/manual/es/
- **MySQL** - https://dev.mysql.com/doc/
- **JavaScript ES5** - https://developer.mozilla.org/es/docs/Web/JavaScript

## 🤝 Contribución

Este es un proyecto educativo desarrollado como parte del curso de 2º DAW (Desarrollo de Aplicaciones Web).

### Cómo Contribuir:
1. Fork el proyecto
2. Crear una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abrir un Pull Request

### Ideas para Mejoras Futuras:
- [ ] Sistema de notificaciones push
- [ ] Exportar calendario a iCal/Google Calendar
- [ ] Vista semanal y diaria
- [ ] Categorías personalizadas de eventos
- [ ] Búsqueda y filtrado de eventos
- [ ] Modo oscuro
- [ ] Multi-idioma
- [ ] Integración con redes sociales
- [ ] Sincronización en la nube

## 📄 Licencia

Este proyecto está bajo la Licencia MIT - ver el archivo [LICENSE](LICENSE) para más detalles.

Copyright (c) 2026 Paco López Alarte

## ✨ Autor

**Paco López Alarte**  
Estudiante de 2º DAW (Desarrollo de Aplicaciones Web)  
Proyecto desarrollado en Enero 2026

---

## 📞 Información Técnica del Proyecto

| Aspecto | Detalle |
|---------|---------|
| **Lenguajes** | PHP 7.4+, JavaScript (ES5), HTML5, CSS3 |
| **Base de datos** | MySQL 5.7+ / MariaDB 10.x |
| **Servidor** | Apache 2.4+ / Nginx |
| **Librerías** | jQuery 3.0, Bootstrap 4, FullCalendar 3.x, Moment.js |
| **Arquitectura** | MVC (Modelo-Vista-Controlador) |
| **Patrón de diseño** | Orientado a Objetos con clases Manager |
| **Seguridad** | Prepared Statements, Validación doble |
| **Compatibilidad** | Chrome 90+, Firefox 88+, Safari 14+, Edge 90+ |
| **Responsive** | Mobile-first con breakpoints 768px y 1024px |
| **Licencia** | MIT License |

---

**⭐ Si este proyecto te fue útil, no olvides darle una estrella!**

