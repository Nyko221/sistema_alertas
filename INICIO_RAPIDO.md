# ⚡ Guía de Inicio Rápido

Esta guía te ayudará a **iniciar el sistema** después de reiniciar tu computadora o cerrar los servicios.

---

## 📋 Checklist Rápido

- [ ] Iniciar Apache y MySQL en XAMPP
- [ ] Verificar IP local (si cambió)
- [ ] Actualizar URL en app Flutter (si cambió)
- [ ] Iniciar Ngrok (solo si usas internet)
- [ ] Ejecutar app Flutter

---

## 🚀 Paso 1: Iniciar XAMPP

### **Windows**

1. Busca **XAMPP Control Panel** en el menú de inicio
2. Haz clic derecho → **Ejecutar como administrador**
3. Haz clic en **Start** en:

   - ✅ **Apache**
   - ✅ **MySQL**

4. **Verificación:** Ambos servicios deben tener fondo **verde**

### **macOS/Linux**

1. Abre terminal
2. Ejecuta:

   ```bash
   sudo /Applications/XAMPP/xamppfiles/xampp start
   # O en Linux:
   sudo /opt/lampp/lampp start
   ```

3. Verifica con:
   ```bash
   sudo /opt/lampp/lampp status
   ```

---

## 🌐 Paso 2: Verificar IP Local (Solo Red WiFi)

### **¿Por qué verificar?**

Tu router puede asignar una **nueva IP** después de reiniciar. Si usas la app en red local, necesitas actualizar la URL.

### **Obtener IP Actual**

#### **Windows:**

1. Presiona `Win + R`
2. Escribe `cmd` y presiona Enter
3. Ejecuta:
   ```bash
   ipconfig
   ```
4. Busca **Adaptador de LAN inalámbrica Wi-Fi**
5. Anota la **Dirección IPv4** (ej: `192.168.1.88`)

#### **macOS:**

1. Abre **Terminal**
2. Ejecuta:
   ```bash
   ifconfig en0 | grep inet
   ```
3. Anota la IP (ej: `192.168.1.88`)

#### **Linux:**

1. Abre terminal
2. Ejecuta:
   ```bash
   ip addr show | grep inet
   ```
3. Anota la IP (ej: `192.168.1.88`)

### **¿Cambió tu IP?**

Si tu IP anterior era `192.168.1.88` y ahora es **diferente**, continúa al **Paso 3**.

Si es la **misma**, salta al **Paso 4**.

---

## 📝 Paso 3: Actualizar URL en Flutter (Si cambió IP)

1. Abre `lib/main.dart` en VS Code o tu editor
2. Ve a la **línea 207**
3. Actualiza con tu nueva IP:

```dart
const String apiUrl = 'http://TU_NUEVA_IP/cementerio_alertas/api/recibir_alerta.php';
```

**Ejemplo:**

```dart
const String apiUrl = 'http://192.168.1.100/cementerio_alertas/api/recibir_alerta.php';
```

4. **Guarda** el archivo (`Ctrl + S`)

---

## 🌍 Paso 4: Iniciar Ngrok (Solo si usas Internet)

### **¿Cuándo usar Ngrok?**

- ✅ Quieres probar la app fuera de tu red WiFi
- ✅ Necesitas acceso desde cualquier lugar con internet
- ⬜ Solo usas red local (NO necesitas Ngrok)

### **Iniciar Túnel**

1. Abre **PowerShell** o **Terminal**
2. Ve a la carpeta donde está `ngrok.exe`:
   ```bash
   cd C:\ruta\a\ngrok
   ```
3. Ejecuta:

   ```bash
   ngrok http 80
   ```

4. **Copia la URL** que aparece (ej: `https://abc123.ngrok-free.app`)

### **Actualizar Flutter**

1. Abre `lib/main.dart`
2. Ve a la **línea 207**
3. Actualiza con la URL de Ngrok:

```dart
const String apiUrl = 'https://abc123.ngrok-free.app/cementerio_alertas/api/recibir_alerta.php';
```

4. **Guarda** el archivo

**⚠️ IMPORTANTE:** La URL de Ngrok **cambia** cada vez que lo reinicias (plan gratuito). Debes actualizar `main.dart` cada vez.

---

## 📱 Paso 5: Ejecutar App Flutter

### **Opción A: Con Dispositivo Físico**

1. Conecta tu teléfono Android/iOS al computador con USB
2. **Android:** Habilita **Depuración USB** en opciones de desarrollador
3. **iOS:** Confía en el computador cuando aparezca el mensaje
4. Abre terminal en la carpeta del proyecto
5. Ejecuta:

   ```bash
   flutter run
   ```

6. Selecciona tu dispositivo en la lista

### **Opción B: Con Emulador**

#### **Android:**

1. Abre **Android Studio**
2. Ve a **Tools** → **AVD Manager**
3. Haz clic en **▶️ Play** en tu emulador
4. Espera a que inicie completamente
5. En terminal ejecuta:
   ```bash
   flutter run
   ```

#### **iOS (Solo macOS):**

1. Abre **Xcode**
2. Ve a **Xcode** → **Open Developer Tool** → **Simulator**
3. Selecciona un dispositivo (ej: iPhone 14)
4. En terminal ejecuta:
   ```bash
   flutter run
   ```

---

## 🖥️ Paso 6: Abrir Panel del Guardia

1. Abre tu navegador (Chrome, Edge, Firefox)
2. Ve a: [http://localhost/cementerio_alertas/guardia/index.php](http://localhost/cementerio_alertas/guardia/index.php)

3. **Verificación:**
   - ✅ Dashboard carga sin errores
   - ✅ Contador de alertas visible
   - ✅ Auto-refresh funciona (cada 5 segundos)

---

## ✅ Verificación del Sistema

### **Prueba Completa**

1. **App Flutter:** Envía una alerta de prueba

   - Toma una foto
   - Agrega descripción
   - Envía

2. **Panel Web:** Verifica que se reciba

   - ✅ Popup de notificación aparece
   - ✅ Sonido automático (3 beeps)
   - ✅ Alerta visible en la lista

3. **Base de Datos:** Verifica en phpMyAdmin
   - Ve a: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
   - Selecciona `cementerio_alertas` → `alertas`
   - Verifica que aparezca el registro

---

## 🛠️ Solución de Problemas

### **Apache no inicia**

**Síntoma:** Botón en XAMPP no se pone verde

**Solución:**

1. Verifica que no haya otro servidor usando el puerto 80:
   ```bash
   netstat -aon | findstr :80
   ```
2. Cierra la aplicación que esté usando el puerto
3. O cambia el puerto de Apache a 8080 en `httpd.conf`

### **MySQL no inicia**

**Síntoma:** MySQL no arranca en XAMPP

**Solución:**

1. Verifica servicios en segundo plano:
   - Windows: `services.msc` → Busca "MySQL"
   - Detén servicios MySQL activos
2. Reinicia XAMPP Control Panel como administrador

### **App no se conecta al backend**

**Síntoma:** Error de conexión al enviar alerta

**Checklist:**

- [ ] Apache y MySQL están corriendo
- [ ] IP en `main.dart` es correcta
- [ ] Teléfono está en la **misma red WiFi** que la PC
- [ ] Firewall de Windows no está bloqueando Apache
- [ ] Si usas Ngrok, el túnel está activo

### **Panel web no muestra alertas**

**Síntoma:** Dashboard vacío

**Solución:**

1. Verifica que la base de datos tenga registros:
   - phpMyAdmin → `cementerio_alertas` → `alertas`
2. Abre la **consola del navegador** (F12):
   - Busca errores en JavaScript
3. Verifica que `obtener_alertas.php` funcione:
   - Ve a: `http://localhost/cementerio_alertas/guardia/obtener_alertas.php`
   - Deberías ver JSON con las alertas

### **No hay notificaciones/sonido**

**Síntoma:** Alerta llega pero sin popup ni sonido

**Solución:**

1. Verifica que el navegador tenga **sonido habilitado**
2. Abre la consola del navegador (F12) y busca errores
3. Refresca la página (`Ctrl + F5`)

---

## 📊 URLs de Referencia Rápida

| Servicio          | URL                                                     |
| ----------------- | ------------------------------------------------------- |
| Panel del Guardia | `http://localhost/cementerio_alertas/guardia/index.php` |
| phpMyAdmin        | `http://localhost/phpmyadmin`                           |
| Test API          | `http://localhost/cementerio_alertas/test/test_api.php` |
| Ngrok Dashboard   | `http://127.0.0.1:4040` (si está activo)                |

---

## 🔄 Reinicio Completo (Si algo falla)

Si el sistema no funciona, haz un **reinicio completo**:

### **1. Detener Todo**

```bash
# XAMPP
# Haz clic en Stop en Apache y MySQL

# Ngrok
# Presiona Ctrl + C en la terminal de Ngrok

# Flutter
# Cierra la app y la terminal
```

### **2. Reiniciar en Orden**

```bash
# 1. XAMPP
# Start Apache → Start MySQL

# 2. Verificar servicios
# Ve a http://localhost

# 3. Ngrok (opcional)
ngrok http 80

# 4. Flutter
flutter clean
flutter pub get
flutter run
```

---

## ⏱️ Tiempo Estimado de Inicio

| Tarea              | Tiempo         |
| ------------------ | -------------- |
| Iniciar XAMPP      | 30 segundos    |
| Verificar IP       | 1 minuto       |
| Actualizar Flutter | 2 minutos      |
| Iniciar Ngrok      | 1 minuto       |
| Ejecutar App       | 2-3 minutos    |
| **TOTAL**          | **~7 minutos** |

---

## 💡 Consejos Pro

### **Automatizar Inicio de XAMPP (Windows)**

1. Presiona `Win + R`, escribe `shell:startup`
2. Crea un acceso directo a:
   ```
   C:\xampp\xampp-control.exe
   ```
3. XAMPP se abrirá automáticamente al iniciar Windows

### **Script de Inicio Rápido (PowerShell)**

Crea un archivo `inicio_rapido.ps1`:

```powershell
# Iniciar XAMPP
Start-Process "C:\xampp\xampp-control.exe"

# Esperar 5 segundos
Start-Sleep -Seconds 5

# Abrir panel del guardia
Start-Process "http://localhost/cementerio_alertas/guardia/index.php"

# Abrir phpMyAdmin
Start-Process "http://localhost/phpmyadmin"

Write-Host "Sistema iniciado correctamente!" -ForegroundColor Green
```

Ejecuta:

```powershell
.\inicio_rapido.ps1
```

### **Guardar IP Actual**

Crea un archivo `mi_ip.txt` en la carpeta del proyecto con tu IP actual. Así no necesitas buscarla cada vez:

```
192.168.1.88
```

---

## 🎯 Checklist Final

Antes de empezar a usar el sistema:

- [ ] ✅ XAMPP corriendo (Apache + MySQL)
- [ ] ✅ IP verificada (o Ngrok activo)
- [ ] ✅ `main.dart` con URL correcta
- [ ] ✅ Panel web abierto en navegador
- [ ] ✅ App Flutter ejecutándose
- [ ] ✅ Prueba de alerta exitosa

---

**¡Listo! El sistema está operativo.** 🚀

Para más detalles, consulta:

- [README.md](README.md) - Documentación completa
- [INSTALACION.md](INSTALACION.md) - Guía de instalación
