# 📦 Guía de Instalación Completa

Esta guía te ayudará a instalar el **Sistema de Alertas** en una computadora nueva desde cero.

---

## 📋 Tabla de Contenidos

1. [Requisitos del Sistema](#requisitos-del-sistema)
2. [Paso 1: Instalar XAMPP](#paso-1-instalar-xampp)
3. [Paso 2: Instalar Flutter](#paso-2-instalar-flutter)
4. [Paso 3: Instalar Android Studio (Opcional)](#paso-3-instalar-android-studio-opcional)
5. [Paso 4: Copiar Archivos del Proyecto](#paso-4-copiar-archivos-del-proyecto)
6. [Paso 5: Configurar Base de Datos](#paso-5-configurar-base-de-datos)
7. [Paso 6: Configurar Backend](#paso-6-configurar-backend)
8. [Paso 7: Configurar App Flutter](#paso-7-configurar-app-flutter)
9. [Paso 8: Configurar Google Maps API](#paso-8-configurar-google-maps-api)
10. [Paso 9: Instalar Ngrok (Opcional)](#paso-9-instalar-ngrok-opcional)
11. [Verificación Final](#verificación-final)

---

## 🖥️ Requisitos del Sistema

### **Hardware Mínimo**

- **Procesador:** Intel Core i3 / AMD Ryzen 3 o superior
- **RAM:** 8 GB (16 GB recomendado para Android Studio)
- **Disco:** 20 GB de espacio libre
- **Internet:** Conexión estable

### **Sistemas Operativos Soportados**

- Windows 10/11 (64-bit)
- macOS 10.14 o superior
- Linux (Ubuntu 18.04+, Debian 10+)

---

## 📥 Paso 1: Instalar XAMPP

### **1.1 Descargar XAMPP**

1. Ve a: [https://www.apachefriends.org/download.html](https://www.apachefriends.org/download.html)
2. Descarga la versión **8.2.12** (PHP 8.2.12)
3. Ejecuta el instalador

### **1.2 Instalar**

1. Selecciona componentes:

   - ✅ Apache
   - ✅ MySQL
   - ✅ PHP
   - ✅ phpMyAdmin
   - ⬜ FileZilla (opcional)
   - ⬜ Perl (opcional)

2. Directorio de instalación:

   - **Windows:** `C:\xampp`
   - **macOS/Linux:** `/Applications/XAMPP` o `/opt/lampp`

3. Haz clic en **Install** y espera

### **1.3 Configurar Permisos (MySQL)**

1. Abre `C:\xampp\mysql\bin\my.ini` (Windows) o `/opt/lampp/etc/my.cnf` (Linux/macOS)
2. Busca la sección `[mysqld]` y agrega:
   ```ini
   skip-grant-tables
   ```
3. **Guarda** el archivo
4. **Reinicia MySQL** desde el panel de XAMPP

### **1.4 Iniciar Servicios**

1. Abre **XAMPP Control Panel**
2. Haz clic en **Start** en:

   - ✅ Apache
   - ✅ MySQL

3. Verifica que ambos tengan fondo **verde**

---

## 🐦 Paso 2: Instalar Flutter

### **2.1 Descargar Flutter SDK**

1. Ve a: [https://docs.flutter.dev/get-started/install](https://docs.flutter.dev/get-started/install)
2. Selecciona tu sistema operativo
3. Descarga el archivo ZIP

### **2.2 Extraer Flutter**

- **Windows:** Extrae en `C:\src\flutter`
- **macOS/Linux:** Extrae en `~/development/flutter`

### **2.3 Configurar Variables de Entorno**

#### **Windows:**

1. Presiona `Win + R`, escribe `sysdm.cpl` y presiona Enter
2. Ve a **Opciones avanzadas** → **Variables de entorno**
3. En **Variables del sistema**, selecciona `Path` → **Editar**
4. Haz clic en **Nuevo** y agrega:
   ```
   C:\src\flutter\bin
   ```
5. Haz clic en **Aceptar** en todas las ventanas

#### **macOS/Linux:**

1. Abre la terminal
2. Edita el archivo de perfil:
   ```bash
   nano ~/.zshrc  # macOS con zsh
   nano ~/.bashrc # Linux con bash
   ```
3. Agrega al final:
   ```bash
   export PATH="$PATH:~/development/flutter/bin"
   ```
4. Guarda (`Ctrl + O`, `Enter`, `Ctrl + X`)
5. Recarga el perfil:
   ```bash
   source ~/.zshrc  # o source ~/.bashrc
   ```

### **2.4 Ejecutar Flutter Doctor**

1. Abre una **nueva terminal**
2. Ejecuta:
   ```bash
   flutter doctor
   ```
3. Verifica que no haya errores críticos

---

## 📱 Paso 3: Instalar Android Studio (Opcional)

Solo necesario si quieres desarrollar para Android.

### **3.1 Descargar**

1. Ve a: [https://developer.android.com/studio](https://developer.android.com/studio)
2. Descarga Android Studio

### **3.2 Instalar**

1. Ejecuta el instalador
2. Selecciona **Standard Installation**
3. Acepta licencias

### **3.3 Instalar Flutter Plugin**

1. Abre Android Studio
2. Ve a **File** → **Settings** (Windows/Linux) o **Android Studio** → **Preferences** (macOS)
3. Busca **Plugins**
4. Busca e instala:
   - **Flutter**
   - **Dart**

### **3.4 Configurar Emulador**

1. Ve a **Tools** → **AVD Manager**
2. Crea un dispositivo virtual:
   - **Device:** Pixel 5
   - **API Level:** 30 o superior

---

## 📂 Paso 4: Copiar Archivos del Proyecto

### **4.1 Copiar Backend a XAMPP**

1. Copia la carpeta `backend` completa
2. Pégala en:
   - **Windows:** `C:\xampp\htdocs\cementerio_alertas`
   - **macOS/Linux:** `/Applications/XAMPP/htdocs/cementerio_alertas` o `/opt/lampp/htdocs/cementerio_alertas`

### **4.2 Copiar App Flutter**

1. Copia la carpeta completa del proyecto `sistema_alertas`
2. Pégala en tu ubicación preferida (ej: `Escritorio`, `Documentos`)

---

## 🗄️ Paso 5: Configurar Base de Datos

### **5.1 Acceder a phpMyAdmin**

1. Abre tu navegador
2. Ve a: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)

### **5.2 Crear Base de Datos**

1. Haz clic en **Nueva** (en el panel izquierdo)
2. Nombre: `cementerio_alertas`
3. Cotejamiento: `utf8mb4_general_ci`
4. Haz clic en **Crear**

### **5.3 Crear Tabla**

1. Selecciona la base de datos `cementerio_alertas`
2. Haz clic en la pestaña **SQL**
3. Pega este código:

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

4. Haz clic en **Continuar**

### **5.4 Verificar Permisos**

En phpMyAdmin, ve a **Cuentas de usuario** y verifica que `root@localhost` tenga **todos los privilegios**.

---

## ⚙️ Paso 6: Configurar Backend

### **6.1 Configurar Conexión a BD**

1. Abre `C:\xampp\htdocs\cementerio_alertas\api\config.php`
2. Verifica estas líneas:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Vacío por defecto en XAMPP
define('DB_NAME', 'cementerio_alertas');
```

### **6.2 Crear Carpeta de Uploads**

1. Asegúrate de que exista:

   - **Windows:** `C:\xampp\htdocs\cementerio_alertas\api\uploads`
   - **macOS/Linux:** `/opt/lampp/htdocs/cementerio_alertas/api/uploads`

2. Dale permisos de escritura:
   - **Windows:** Click derecho → Propiedades → Seguridad → Editar → Permitir escritura
   - **Linux/macOS:**
     ```bash
     chmod 755 /opt/lampp/htdocs/cementerio_alertas/api/uploads
     ```

---

## 📲 Paso 7: Configurar App Flutter

### **7.1 Obtener Dependencias**

1. Abre una terminal en la carpeta `sistema_alertas`
2. Ejecuta:
   ```bash
   flutter pub get
   ```

### **7.2 Configurar URL del Servidor**

1. Abre `lib/main.dart`
2. Ve a la **línea 207**
3. Modifica la URL según tu red:

#### **Para Red Local (WiFi):**

1. Abre terminal y ejecuta:
   - **Windows:** `ipconfig`
   - **macOS/Linux:** `ifconfig`
2. Busca tu **dirección IPv4** (ej: `192.168.1.88`)
3. Actualiza:
   ```dart
   const String apiUrl = 'http://192.168.1.88/cementerio_alertas/api/recibir_alerta.php';
   ```

#### **Para Internet (Ngrok):**

Ver [Paso 9: Instalar Ngrok](#paso-9-instalar-ngrok-opcional)

---

## 🗺️ Paso 8: Configurar Google Maps API

### **8.1 Obtener API Key**

1. Ve a: [https://console.cloud.google.com/](https://console.cloud.google.com/)
2. Crea un proyecto nuevo
3. Ve a **APIs & Services** → **Credentials**
4. Haz clic en **Create Credentials** → **API Key**
5. Copia la clave generada

### **8.2 Configurar Android**

1. Abre `android/app/src/main/AndroidManifest.xml`
2. Busca esta línea (alrededor de la línea 12):
   ```xml
   <meta-data android:name="com.google.android.geo.API_KEY"
              android:value="TU_API_KEY_AQUI"/>
   ```
3. Reemplaza `TU_API_KEY_AQUI` con tu clave

### **8.3 Configurar iOS**

1. Abre `ios/Runner/AppDelegate.swift`
2. Busca esta línea:
   ```swift
   GMSServices.provideAPIKey("TU_API_KEY_AQUI")
   ```
3. Reemplaza `TU_API_KEY_AQUI` con tu clave

---

## 🌐 Paso 9: Instalar Ngrok (Opcional)

Solo necesario si quieres acceder desde internet (fuera de tu red local).

### **9.1 Descargar Ngrok**

1. Ve a: [https://ngrok.com/download](https://ngrok.com/download)
2. Descarga la versión para tu sistema operativo
3. Extrae el archivo ZIP

### **9.2 Registrarse**

1. Ve a: [https://dashboard.ngrok.com/signup](https://dashboard.ngrok.com/signup)
2. Crea una cuenta gratuita

### **9.3 Configurar Authtoken**

1. Ve al dashboard: [https://dashboard.ngrok.com/get-started/your-authtoken](https://dashboard.ngrok.com/get-started/your-authtoken)
2. Copia tu authtoken
3. Abre terminal en la carpeta donde está `ngrok.exe` y ejecuta:
   ```bash
   ngrok config add-authtoken TU_AUTHTOKEN_AQUI
   ```

### **9.4 Iniciar Túnel**

1. Ejecuta:
   ```bash
   ngrok http 80
   ```
2. Copia la URL generada (ej: `https://abc123.ngrok-free.app`)
3. Actualiza `lib/main.dart` línea 207:
   ```dart
   const String apiUrl = 'https://abc123.ngrok-free.app/cementerio_alertas/api/recibir_alerta.php';
   ```

**⚠️ IMPORTANTE:** La URL de Ngrok cambia cada vez que reinicias. Debes actualizar `main.dart` cada vez.

---

## ✅ Verificación Final

### **1. Probar Backend**

1. Ve a: [http://localhost/cementerio_alertas/test/test_api.php](http://localhost/cementerio_alertas/test/test_api.php)
2. Deberías ver: `Conexión exitosa a la base de datos`

### **2. Probar Panel del Guardia**

1. Ve a: [http://localhost/cementerio_alertas/guardia/index.php](http://localhost/cementerio_alertas/guardia/index.php)
2. Deberías ver el dashboard sin errores

### **3. Probar App Flutter**

1. Conecta tu teléfono Android/iOS o inicia un emulador
2. Ejecuta:
   ```bash
   flutter run
   ```
3. Intenta enviar una alerta de prueba

### **4. Verificar Notificaciones**

1. Envía una alerta desde la app
2. Abre el panel del guardia
3. Deberías ver:
   - ✅ Popup de notificación
   - ✅ Sonido automático (3 beeps)
   - ✅ Alerta en la lista

---

## 🎉 ¡Instalación Completa!

Ahora tu sistema está listo para usar. Para instrucciones de inicio rápido después de reiniciar la computadora, consulta [INICIO_RAPIDO.md](INICIO_RAPIDO.md).

---

## ⚠️ Problemas Comunes

### **Apache no inicia en XAMPP**

**Problema:** El puerto 80 está ocupado

**Solución:**

1. Abre `C:\xampp\apache\conf\httpd.conf`
2. Busca `Listen 80` y cámbialo a `Listen 8080`
3. Reinicia Apache
4. Accede con `http://localhost:8080`

### **MySQL no inicia**

**Problema:** El puerto 3306 está ocupado

**Solución:**

1. Detén otros servicios MySQL
2. O cambia el puerto en `C:\xampp\mysql\bin\my.ini`
3. Busca `port=3306` y cámbialo a `port=3307`

### **Flutter no reconocido en terminal**

**Problema:** PATH no configurado

**Solución:**

1. Cierra y vuelve a abrir la terminal
2. Verifica con: `echo $PATH` (macOS/Linux) o `echo %PATH%` (Windows)

---

## 📧 Soporte

Si tienes problemas, verifica:

- [README.md](README.md) - Documentación general
- [INICIO_RAPIDO.md](INICIO_RAPIDO.md) - Guía de inicio

---

**¡Bienvenido al Sistema de Alertas!** 🚀
