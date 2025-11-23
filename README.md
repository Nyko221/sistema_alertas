# 🚨 Sistema de Alertas - Cementerio General

Sistema completo de alertas en tiempo real que permite a visitantes reportar emergencias (accidentes, robos, daños) desde una **app móvil Flutter**, con notificaciones instantáneas al **personal de guardia** mediante un **panel web**.

---

## 📋 Índice

- [Características](#características)
- [Arquitectura del Sistema](#arquitectura-del-sistema)
- [Tecnologías Implementadas](#tecnologías-implementadas)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Funcionalidades](#funcionalidades)
- [Instalación](#instalación)
- [Uso](#uso)
- [Base de Datos](#base-de-datos)
- [Configuración](#configuración)
- [Seguridad](#seguridad)

---

## ✨ Características

### **App Móvil (Flutter)**

- ✅ **Geolocalización GPS** sin necesidad de internet
- ✅ **Captura de fotos** desde cámara o galería
- ✅ **3 tipos de alertas:** Accidente, Robo, Daño
- ✅ **Vista previa en mapa** de Google Maps
- ✅ **Descripción de texto** (máx. 280 caracteres)
- ✅ **Validaciones en tiempo real**
- ✅ **Envío HTTP seguro** al servidor
- ✅ **Funciona en Android e iOS**

### **Panel Web (Guardia)**

- ✅ **Notificaciones en tiempo real** con sonido
- ✅ **Dashboard con estadísticas** (Total, Pendientes, Atendidas)
- ✅ **Filtros** por tipo y estado
- ✅ **Visualización de fotos** adjuntas
- ✅ **Botones de acción:** Atender, Rechazar, Ver Mapa
- ✅ **Auto-refresh cada 5 segundos**
- ✅ **Diseño responsive** (móvil/escritorio)
- ✅ **Sin notificaciones duplicadas**

### **Backend PHP**

- ✅ **API REST** para recepción de alertas
- ✅ **Validación de imágenes** con `getimagesize()`
- ✅ **Base de datos MySQL** con prepared statements
- ✅ **Almacenamiento seguro** de archivos
- ✅ **CORS habilitado** para cross-origin
- ✅ **Respuestas JSON** estandarizadas

---

## 🏗️ Arquitectura del Sistema

```
┌─────────────────────┐
│   APP MÓVIL         │ ← Flutter (Android/iOS)
│   (Visitantes)      │    - GPS sin internet
└──────────┬──────────┘    - Cámara/Galería
           │                - Formulario
           │ HTTP POST
           │ (Foto + GPS + Descripción)
           ↓
┌─────────────────────┐
│   BACKEND PHP       │ ← XAMPP (Apache + MySQL)
│   recibir_alerta.php│    - Validación de datos
└──────────┬──────────┘    - Almacenamiento
           │
           │ INSERT INTO
           ↓
┌─────────────────────┐
│   BASE DE DATOS     │ ← MySQL
│   cementerio_alertas│    - Tabla: alertas
└──────────┬──────────┘    - Tipos, Estados, GPS
           │
           │ SELECT (cada 5s)
           ↓
┌─────────────────────┐
│   PANEL WEB         │ ← PHP + JavaScript
│   (Guardia)         │    - Auto-refresh
└─────────────────────┘    - Notificaciones 🚨
```

---

## 🛠️ Tecnologías Implementadas

### **Frontend Móvil**

| Tecnología              | Versión | Uso                       |
| ----------------------- | ------- | ------------------------- |
| **Flutter**             | 3.x     | Framework multiplataforma |
| **Dart**                | 3.x     | Lenguaje de programación  |
| **image_picker**        | ^1.0.4  | Captura de fotos          |
| **geolocator**          | ^10.1.0 | GPS sin internet          |
| **google_maps_flutter** | ^2.5.0  | Mapas                     |
| **http**                | ^1.1.0  | Peticiones HTTP           |
| **google_fonts**        | ^6.1.0  | Fuente Inter              |

### **Backend**

| Tecnología     | Versión | Uso               |
| -------------- | ------- | ----------------- |
| **XAMPP**      | 8.2.12  | Servidor local    |
| **Apache**     | 2.4.58  | Servidor web      |
| **PHP**        | 8.2.12  | Lenguaje backend  |
| **MySQL**      | 10.4.32 | Base de datos     |
| **phpMyAdmin** | 5.2.1   | Administración BD |

---

## 📂 Estructura del Proyecto

```
sistema_alertas/
├── lib/
│   └── main.dart                    # App Flutter (942 líneas)
│
├── backend/
│   ├── api/
│   │   ├── config.php              # Configuración BD + CORS
│   │   ├── recibir_alerta.php      # Endpoint: Recibir alertas
│   │   └── uploads/                # Almacén de fotos
│   │
│   ├── guardia/
│   │   ├── index.php               # Panel del guardia
│   │   ├── obtener_alertas.php     # Endpoint: GET alertas
│   │   └── cambiar_estado.php      # Endpoint: UPDATE estado
│   │
│   └── test/
│       └── test_api.php            # Script de prueba
│
├── android/
│   └── app/src/main/
│       └── AndroidManifest.xml     # Permisos Android
│
├── pubspec.yaml                    # Dependencias Flutter
├── README.md                       # Este archivo
├── INSTALACION.md                  # Guía de instalación
└── INICIO_RAPIDO.md                # Guía de inicio rápido
```

---

## 🎯 Funcionalidades Principales

### **App Móvil**

1. **Pantalla Inicial:** Botón de alerta con animación
2. **Selección de Tipo:** 3 tipos de emergencia
3. **Formulario:**
   - Geolocalización GPS automática
   - Captura/selección de foto
   - Descripción opcional (280 chars)
   - Validaciones en tiempo real

### **Panel Web**

1. **Dashboard:** Estadísticas en tiempo real
2. **Lista de Alertas:** Con fotos y datos GPS
3. **Filtros:** Por tipo y estado
4. **Acciones:** Atender, Rechazar, Ver Mapa
5. **Notificaciones:** Popup + sonido automático

---

## 💾 Base de Datos

```sql
CREATE TABLE `alertas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tipo` ENUM('Accidente','Robo','Daño') NOT NULL,
  `descripcion` TEXT DEFAULT NULL,
  `foto` VARCHAR(255) DEFAULT NULL,
  `latitud` DECIMAL(10,8) NOT NULL,
  `longitud` DECIMAL(11,8) NOT NULL,
  `estado` ENUM('pendiente','atendida','rechazada') DEFAULT 'pendiente',
  `fecha_hora` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## ⚙️ Configuración

### **Backend** (`backend/api/config.php`)

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cementerio_alertas');
```

### **App Flutter** (`lib/main.dart` línea 207)

```dart
// Red Local
const String apiUrl = 'http://192.168.1.XX/cementerio_alertas/api/recibir_alerta.php';

// Con Ngrok
const String apiUrl = 'https://abc123.ngrok-free.app/cementerio_alertas/api/recibir_alerta.php';
```

---

## 🔒 Seguridad

- ✅ Validación de tipo MIME con `getimagesize()`
- ✅ Prepared statements (previene SQL Injection)
- ✅ Sanitización de inputs
- ✅ Límite de tamaño de archivo (10MB)
- ✅ Nombres únicos de archivo
- ✅ CORS configurado

---

## 📦 Instalación

Ver [INSTALACION.md](INSTALACION.md) para instrucciones completas.

---

## 🚀 Inicio Rápido

Ver [INICIO_RAPIDO.md](INICIO_RAPIDO.md) para reiniciar el sistema.

---

## 🌐 URLs del Sistema

| Servicio   | URL                                                     |
| ---------- | ------------------------------------------------------- |
| Panel Web  | `http://localhost/cementerio_alertas/guardia/index.php` |
| phpMyAdmin | `http://localhost/phpmyadmin`                           |
| Test API   | `http://localhost/cementerio_alertas/test/test_api.php` |

---

## 📊 Estadísticas

- Líneas de código (Dart): ~942
- Líneas de código (PHP): ~600
- Dependencias Flutter: 6
- Endpoints API: 3

---

## 👨‍💻 Autor

**Bastian** - Sistema de Alertas Cementerio General

- GitHub: [@Nyko221](https://github.com/Nyko221)
- Fecha: Noviembre 2025

---

**¡Sistema 100% funcional y listo para producción!** 🎉
