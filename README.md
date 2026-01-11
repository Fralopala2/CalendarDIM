# 📅 Sistema de Calendario - Versión Web y Android

## 🎯 Dos Versiones Disponibles

### 📱 **Versión Android APK** (Completada)
- App nativa para Android completamente funcional
- Funciona 100% offline sin internet
- Persistencia con localStorage
- Interfaz optimizada para móviles
- **Estado: ✅ LISTA PARA USAR**

### 🌐 **Versión Web** (Original)
- Sistema web con PHP y MySQL
- Funciona en cualquier navegador
- Servidor local requerido

---

## 📱 VERSIÓN ANDROID APK - COMPLETADA

### ✅ Características Implementadas:
- **App nativa Android** (APK instalable)
- **100% offline** (sin conexión a internet)
- **Persistencia localStorage** (datos guardados localmente)
- **Modal unificado** para eventos y cumpleaños
- **Paleta de colores expandida** (10 eventos + 5 cumpleaños)
- **Sidebar colapsible** con timeline de 24 horas
- **CRUD completo** (crear, editar, eliminar)
- **Logo personalizado** configurado
- **Diseño responsive** optimizado para móviles
- **Botón flotante** para crear eventos rápidamente
- **Funcionalidad táctil** optimizada

### 📂 Estructura del Proyecto Android:
```
calendario-android/
├── www/                          # Código fuente de la app
│   ├── index.html               # App principal (1000+ líneas)
│   ├── css/home-mobile.css      # Estilos móviles optimizados
│   ├── IMAGES/logo.png          # Logo de la aplicación
│   └── js/                      # Librerías JavaScript
├── platforms/android/           # Proyecto Android nativo
├── config.xml                   # Configuración Cordova
└── build-instructions.md        # Instrucciones para generar APK
```

### 🔧 Funcionalidades de la App:

#### Gestión de Eventos:
- ✅ Crear eventos con título, fecha, hora (opcional)
- ✅ Descripción opcional (máximo 1000 caracteres)
- ✅ 10 colores disponibles: #FF5722, #FFC107, #8BC34A, #009688, #2196F3, #9C27B0, #E91E63, #795548, #607D8B, #FF9800
- ✅ Editar eventos existentes
- ✅ Eliminar eventos con confirmación

#### Gestión de Cumpleaños:
- ✅ Crear cumpleaños con nombre y fecha
- ✅ Recurrencia automática anual
- ✅ Emoji de pastel 🎂 automático
- ✅ 5 colores específicos: #FF69B4, #9C27B0, #E91E63, #673AB7, #3F51B5
- ✅ Editar y eliminar cumpleaños

#### Interfaz Móvil:
- ✅ Sidebar colapsible con botón ☰
- ✅ Timeline horizontal deslizable (24 horas)
- ✅ Modal responsive con botones alineados correctamente
- ✅ Botón flotante (+) para crear eventos
- ✅ Navegación táctil optimizada

### 🚀 Para Generar Nueva APK:
1. **Configurar entorno:**
   - Instalar Node.js y Cordova CLI
   - Configurar Android SDK
   - Instalar Java 11

2. **Compilar:**
   ```bash
   cd calendario-android
   cordova build android
   ```

3. **APK generada en:**
   `platforms/android/app/build/outputs/apk/debug/app-debug.apk`

**Ver instrucciones completas en:** `calendario-android/build-instructions.md`

---

## 🌐 VERSIÓN WEB - Instrucciones de Instalación

## 🎯 Requisitos del Sistema

Para ejecutar este calendario **completamente offline**, necesitas:

### Software Requerido:
- **PHP 7.4 o superior**
- **MySQL 5.7 o superior** (o MariaDB)
- **Servidor web local** (Apache, Nginx, o servidor integrado de PHP)

### Opciones Recomendadas (Todo-en-uno):
- **XAMPP** (Windows/Mac/Linux) - https://www.apachefriends.org/
- **WAMP** (Windows) - https://www.wampserver.com/
- **MAMP** (Mac) - https://www.mamp.info/
- **Laragon** (Windows) - https://laragon.org/

## 🚀 Instalación Paso a Paso

### Opción 1: Con XAMPP (Recomendado)

1. **Descargar e instalar XAMPP:**
   - Ir a https://www.apachefriends.org/
   - Descargar la versión para tu sistema operativo
   - Instalar siguiendo las instrucciones

2. **Iniciar servicios:**
   - Abrir XAMPP Control Panel
   - Iniciar **Apache** y **MySQL**

3. **Copiar archivos del proyecto:**
   - Copiar la carpeta `calendario` a `C:\xampp\htdocs\` (Windows) o `/Applications/XAMPP/htdocs/` (Mac)

4. **Crear base de datos:**
   - Abrir navegador y ir a http://localhost/phpmyadmin
   - Crear nueva base de datos llamada `calendario`
   - Importar el archivo `calendario/database/calendario_enhanced.sql`

5. **Configurar conexión:**
   - Abrir `calendario/PHP/config.php`
   - Verificar que los datos de conexión sean:
     ```php
     $usuario  = "root";
     $password = "";
     $servidor = "localhost";
     $basededatos = "calendario";
     ```

6. **Acceder al calendario:**
   - Abrir navegador y ir a http://localhost/calendario/

### Opción 2: Con Servidor PHP Integrado (Avanzado)

1. **Instalar PHP y MySQL por separado**
2. **Crear base de datos e importar SQL**
3. **Ejecutar desde terminal:**
   ```bash
   cd calendario
   php -S localhost:8000
   ```
4. **Acceder en:** http://localhost:8000

## 📁 Estructura de Archivos Incluidos

```
calendario/
├── index.php              # Página principal
├── PHP/                   # Lógica del servidor
│   ├── config.php         # Configuración de BD
│   ├── EventManager.php   # Gestión de eventos
│   ├── BirthdayManager.php # Gestión de cumpleaños
│   └── ...               # Otros archivos PHP
├── css/                   # Estilos (Bootstrap, FullCalendar, responsive)
├── js/                    # JavaScript (jQuery, FullCalendar, Bootstrap)
├── locales/               # Localización en español
├── IMAGES/                # Imágenes del proyecto
└── database/              # Script de base de datos
    └── calendario_enhanced.sql
```

## 🔧 Configuración de Base de Datos

### Importar automáticamente:
1. Acceder a phpMyAdmin (http://localhost/phpmyadmin)
2. Crear base de datos `calendario`
3. Seleccionar la base de datos
4. Ir a "Importar"
5. Seleccionar archivo `calendario/database/calendario_enhanced.sql`
6. Hacer clic en "Continuar"

### Configuración manual (si es necesario):
```sql
CREATE DATABASE calendario;
USE calendario;
-- Luego ejecutar el contenido de calendario_enhanced.sql
```

## 🌐 Funcionalidades Offline

✅ **Completamente offline** - No requiere conexión a internet
✅ **Todas las librerías incluidas** - jQuery, Bootstrap, FullCalendar
✅ **Emojis del sistema** - Usa emojis nativos del SO
✅ **Responsive design** - Funciona en móvil, tablet y desktop
✅ **Base de datos local** - MySQL/MariaDB

## 📱 Características del Sistema

### Gestión de Eventos:
- ✅ Crear, editar y eliminar eventos
- ✅ Fechas de inicio y fin
- ✅ Horarios opcionales
- ✅ Descripciones
- ✅ 10 colores diferentes
- ✅ Arrastrar y soltar para cambiar fechas

### Gestión de Cumpleaños:
- ✅ Crear, editar y eliminar cumpleaños
- ✅ Recurrencia automática anual
- ✅ Emoji de pastel 🎂
- ✅ 5 colores específicos para cumpleaños

### Interfaz:
- ✅ Sidebar colapsible con timeline de 24 horas
- ✅ Modal unificado para eventos y cumpleaños
- ✅ Diseño responsive (móvil, tablet, desktop)
- ✅ Interfaz en español

## 🔍 Solución de Problemas

### Error de conexión a base de datos:
- Verificar que MySQL esté iniciado
- Comprobar credenciales en `config.php`
- Asegurar que la base de datos `calendario` existe

### Página en blanco:
- Verificar que PHP esté funcionando
- Revisar logs de error de Apache/PHP
- Comprobar permisos de archivos

### Modal no se abre:
- Verificar que JavaScript esté habilitado
- Comprobar consola del navegador para errores
- Asegurar que Bootstrap y jQuery estén cargados

## 📞 Información Técnica

### Versión Web:
- **Lenguaje:** PHP 7.4+, JavaScript (ES5), HTML5, CSS3
- **Base de datos:** MySQL 5.7+ / MariaDB
- **Librerías:** jQuery 3.0, Bootstrap 4, FullCalendar 3.x, Moment.js

### Versión Android:
- **Framework:** Apache Cordova
- **Lenguaje:** HTML5, CSS3, JavaScript (ES5)
- **Persistencia:** localStorage
- **Librerías:** jQuery 3.0, Bootstrap 4, FullCalendar 3.x, Moment.js

### Compatibilidad:
- **Web:** Chrome 90+, Firefox 88+, Safari 14+, Edge 90+
- **Android:** Android 7.0+ (API 24+)

---

**Desarrollado por Paco López Alarte - 2º DAW - Enero 2026**