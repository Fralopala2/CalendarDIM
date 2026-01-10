# Instrucciones para Generar APK

## Requisitos Previos

1. **Android Studio** - Descargar desde https://developer.android.com/studio
2. **Java JDK 8 o superior**
3. **Gradle** (incluido con Android Studio)

## Configuración del Entorno

1. Instalar Android Studio
2. Configurar variables de entorno:
   - ANDROID_HOME: Ruta al SDK de Android
   - JAVA_HOME: Ruta al JDK

## Comandos para Generar APK

```bash
# Compilar la aplicación
cordova build android

# Generar APK de debug (para pruebas)
cordova build android --debug

# Generar APK de release (para distribución)
cordova build android --release
```

## Ubicación del APK

El archivo APK se generará en:
`platforms/android/app/build/outputs/apk/`

## Instalación en Dispositivo

```bash
# Instalar APK en dispositivo conectado
adb install platforms/android/app/build/outputs/apk/debug/app-debug.apk
```

## Características de la App

- ✅ Funciona completamente offline
- ✅ Base de datos SQLite local
- ✅ Gestión de eventos y cumpleaños
- ✅ Interfaz responsive para móviles
- ✅ Calendario interactivo con FullCalendar.js
- ✅ Timeline de 24 horas
- ✅ Modal unificado para crear/editar eventos