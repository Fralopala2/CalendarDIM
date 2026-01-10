# Changelog - Sistema de Calendario

## [2.0.0] - 2026-01-10

### ✨ Nueva Versión Android APK
- **NUEVA**: App nativa Android con Apache Cordova
- **NUEVA**: Base de datos SQLite local para funcionamiento offline
- **NUEVA**: EventManager y BirthdayManager convertidos a JavaScript
- **NUEVA**: Modal unificado adaptado para móviles
- **NUEVA**: Estructura de proyecto Cordova completa
- **NUEVA**: Instrucciones de compilación para generar APK

### 🔧 Arquitectura Android
- **Añadido**: DatabaseManager.js para gestión SQLite
- **Añadido**: EventManager.js (conversión desde PHP)
- **Añadido**: BirthdayManager.js (conversión desde PHP)
- **Añadido**: ModalUnified.js para interfaz móvil
- **Añadido**: App.js como controlador principal
- **Añadido**: Plugin cordova-sqlite-storage

### 📱 Funcionalidades Android
- ✅ Gestión completa de eventos offline
- ✅ Gestión completa de cumpleaños offline
- ✅ Calendario FullCalendar.js adaptado
- ✅ Timeline de 24 horas responsive
- ✅ Sidebar colapsible para móviles
- ✅ Misma paleta de colores que versión web
- ✅ Validaciones de datos idénticas

### 🗂️ Estructura de Proyecto
- **Añadido**: Rama `android-apk-version` en Git
- **Añadido**: Directorio `calendario-android/` con proyecto Cordova
- **Copiado**: Todos los assets CSS, JS e imágenes
- **Adaptado**: HTML principal para app móvil

### 📋 Compatibilidad
- **Android**: 5.0+ (API Level 21+)
- **Offline**: 100% funcional sin conexión
- **Responsive**: Optimizado para pantallas móviles
- **Performance**: Base de datos local SQLite

---

## [1.0.0] - 2026-01-09

### 🎉 Versión Inicial Web
- **Inicial**: Sistema de calendario web completo
- **Inicial**: Gestión de eventos con PHP y MySQL
- **Inicial**: Gestión de cumpleaños con recurrencia anual
- **Inicial**: Interfaz responsive con FullCalendar.js
- **Inicial**: Modal unificado para eventos y cumpleaños
- **Inicial**: Timeline de 24 horas en sidebar
- **Inicial**: 10 colores para eventos, 5 para cumpleaños
- **Inicial**: Funcionalidad offline (sin CDNs externos)
- **Inicial**: Tests unitarios y de propiedades completos