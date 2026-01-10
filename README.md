# 📅 Sistema de Calendario - Instrucciones de Instalación

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

- **Lenguaje:** PHP 7.4+, JavaScript (ES5), HTML5, CSS3
- **Base de datos:** MySQL 5.7+ / MariaDB
- **Librerías:** jQuery 3.0, Bootstrap 4, FullCalendar 3.x, Moment.js
- **Compatibilidad:** Chrome 90+, Firefox 88+, Safari 14+, Edge 90+

---

**Desarrollado por Paco López Alarte - 2º DAW - Enero 2026**