<?php
// filepath: backend/guardia/index.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Panel de Guardia - Sistema de Alertas</title>
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background: #f5f5f5;
      }

      .header {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        position: relative;
      }

      .header h1 {
        font-size: 28px;
        display: flex;
        align-items: center;
        gap: 10px;
      }

      .header .status-indicator {
        position: absolute;
        top: 20px;
        right: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.2);
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 14px;
      }

      .status-dot {
        width: 12px;
        height: 12px;
        background: #28a745;
        border-radius: 50%;
        animation: pulse 2s infinite;
      }

      @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
      }

      /* NOTIFICACIÓN EMERGENTE */
      .notification-popup {
        position: fixed;
        top: -200px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        background: linear-gradient(135deg, #dc3545, #ff4757);
        color: white;
        padding: 25px 40px;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(220, 53, 69, 0.5);
        min-width: 400px;
        max-width: 90%;
        transition: top 0.5s ease;
        border: 3px solid #fff;
      }

      .notification-popup.show {
        top: 30px;
        animation: shake 0.5s ease;
      }

      @keyframes shake {
        0%, 100% { transform: translateX(-50%) rotate(0deg); }
        25% { transform: translateX(-50%) rotate(-5deg); }
        75% { transform: translateX(-50%) rotate(5deg); }
      }

      .notification-popup.blink {
        animation: blink 0.5s ease infinite;
      }

      @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
      }

      .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
      }

      .notification-title {
        font-size: 22px;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 10px;
      }

      .notification-icon {
        font-size: 32px;
        animation: rotate 1s linear infinite;
      }

      @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
      }

      .notification-close {
        background: transparent;
        border: none;
        color: white;
        font-size: 28px;
        cursor: pointer;
        opacity: 0.8;
        transition: opacity 0.3s;
      }

      .notification-close:hover {
        opacity: 1;
      }

      .notification-body {
        margin: 15px 0;
      }

      .notification-type {
        display: inline-block;
        background: rgba(255, 255, 255, 0.3);
        padding: 6px 15px;
        border-radius: 20px;
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 14px;
      }

      .notification-description {
        font-size: 16px;
        line-height: 1.5;
        margin: 10px 0;
      }

      .notification-location {
        background: rgba(255, 255, 255, 0.2);
        padding: 10px 15px;
        border-radius: 8px;
        margin-top: 10px;
        font-size: 13px;
      }

      .notification-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
      }

      .notification-btn {
        flex: 1;
        padding: 12px;
        border: 2px solid white;
        border-radius: 8px;
        background: transparent;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
      }

      .notification-btn:hover {
        background: white;
        color: #dc3545;
      }

      .container {
        max-width: 1400px;
        margin: 30px auto;
        padding: 0 20px;
      }

      .filters {
        background: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
      }

      .filters select {
        padding: 10px 15px;
        border: 2px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
      }

      .filters button {
        padding: 10px 20px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 600;
      }

      .filters button:hover {
        background: #c82333;
      }

      .stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
      }

      .stat-card {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        border-left: 5px solid;
      }

      .stat-card.pendiente { border-color: #ffc107; }
      .stat-card.atendida { border-color: #28a745; }
      .stat-card.total { border-color: #007bff; }

      .stat-card h3 {
        color: #666;
        font-size: 14px;
        margin-bottom: 10px;
      }

      .stat-card .number {
        font-size: 36px;
        font-weight: bold;
        color: #333;
      }

      .alertas-grid {
        display: grid;
        gap: 20px;
      }

      .alerta-card {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
        border-left: 5px solid;
      }

      .alerta-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
      }

      .alerta-card.Accidente { border-color: #dc3545; }
      .alerta-card.Robo { border-color: #fd7e14; }
      .alerta-card.Daño { border-color: #ffc107; }

      .alerta-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
      }

      .alerta-tipo {
        display: inline-block;
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
      }

      .tipo-Accidente { background: #ffe5e5; color: #dc3545; }
      .tipo-Robo { background: #fff3e5; color: #fd7e14; }
      .tipo-Daño { background: #fff9e5; color: #ffc107; }

      .alerta-estado {
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
      }

      .estado-pendiente { background: #fff3cd; color: #856404; }
      .estado-atendida { background: #d4edda; color: #155724; }
      .estado-rechazada { background: #f8d7da; color: #721c24; }

      .alerta-descripcion {
        color: #666;
        line-height: 1.6;
        margin: 15px 0;
      }

      .alerta-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #eee;
      }

      .alerta-fecha {
        color: #999;
        font-size: 13px;
      }

      .alerta-ubicacion {
        color: #007bff;
        font-size: 13px;
      }

      .alerta-foto {
        margin: 15px 0;
        text-align: center;
      }

      .alerta-foto img {
        max-width: 100%;
        max-height: 300px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      }

      .acciones {
        display: flex;
        gap: 10px;
        margin-top: 15px;
      }

      .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s;
      }

      .btn-atender { background: #28a745; color: white; }
      .btn-atender:hover { background: #218838; }
      .btn-rechazar { background: #dc3545; color: white; }
      .btn-rechazar:hover { background: #c82333; }
      .btn-mapa { background: #007bff; color: white; }
      .btn-mapa:hover { background: #0056b3; }

      .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 10px;
      }

      .empty-state h3 {
        color: #999;
        font-size: 20px;
      }
    </style>
</head>
<body>
    <!-- Notificación emergente -->
    <div id="notificationPopup" class="notification-popup">
      <div class="notification-header">
        <div class="notification-title">
          <span class="notification-icon">🚨</span>
          ¡NUEVA ALERTA!
        </div>
        <button class="notification-close" onclick="cerrarNotificacion()">×</button>
      </div>
      <div class="notification-body">
        <div class="notification-type" id="notifTipo">🚑 Accidente</div>
        <div class="notification-description" id="notifDescripcion"></div>
        <div class="notification-location" id="notifUbicacion">
          📍 Ubicación: -33.3914, -70.6716
        </div>
      </div>
      <div class="notification-actions">
        <button class="notification-btn" onclick="verAlertaActual()">👁️ Ver Detalles</button>
        <button class="notification-btn" onclick="cerrarNotificacion()">✓ Entendido</button>
      </div>
    </div>

    <!-- Audio de alerta -->
    <audio id="alertSound" preload="auto">
      <source src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIGGi78OScTgwOUKvo8LViFQc4kdbxzn0rBSJ2xu/glEIKE1+z5+yrWBIJRp7f8sFxIQQugM/y2Ik2CBhpvO/mnE4MDlCq5++2YRUHPJPc8s15LgYgdcbu3pVEChJes+Xxp1gUCkag4PLEcSMFMIHR8tmJNggYaLvw5pxODA9Qqufs" type="audio/wav" />
    </audio>

    <div class="header">
      <h1>🚨 Panel de Guardia - Sistema de Alertas</h1>
      <div class="status-indicator">
        <div class="status-dot"></div>
        <span>Sistema Activo</span>
      </div>
    </div>

    <div class="container">
      <!-- Filtros -->
      <div class="filters">
        <select id="filtroTipo">
          <option value="">Todos los tipos</option>
          <option value="Accidente">🚑 Accidente</option>
          <option value="Robo">🚔 Robo</option>
          <option value="Daño">⚠️ Daño</option>
        </select>

        <select id="filtroEstado">
          <option value="">Todos los estados</option>
          <option value="pendiente">⏳ Pendiente</option>
          <option value="atendida">✅ Atendida</option>
          <option value="rechazada">❌ Rechazada</option>
        </select>

        <button onclick="cargarAlertas()">🔄 Actualizar</button>
      </div>

      <!-- Estadísticas -->
      <div class="stats">
        <div class="stat-card total">
          <h3>TOTAL ALERTAS</h3>
          <div class="number" id="totalAlertas">0</div>
        </div>
        <div class="stat-card pendiente">
          <h3>PENDIENTES</h3>
          <div class="number" id="totalPendientes">0</div>
        </div>
        <div class="stat-card atendida">
          <h3>ATENDIDAS</h3>
          <div class="number" id="totalAtendidas">0</div>
        </div>
      </div>

      <!-- Lista de alertas -->
      <div id="alertasContainer" class="alertas-grid">
        <div class="empty-state">
          <h3>Cargando alertas...</h3>
        </div>
      </div>
    </div>

    <script>
      let ultimoIdConocido = 0;
      let alertasNotificadas = new Set();
      let alertaActualId = null;

      // Cargar alertas al iniciar
      cargarAlertas();

      // Auto-refresh cada 5 segundos
      setInterval(verificarNuevasAlertas, 5000);

      function cargarAlertas() {
        const filtroTipo = document.getElementById("filtroTipo").value;
        const filtroEstado = document.getElementById("filtroEstado").value;

        let url = "obtener_alertas.php?";
        if (filtroTipo) url += `tipo=${filtroTipo}&`;
        if (filtroEstado) url += `estado=${filtroEstado}`;

        fetch(url)
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              mostrarAlertas(data.data);
              actualizarEstadisticas(data.data);

              if (data.data.length > 0) {
                ultimoIdConocido = Math.max(...data.data.map((a) => parseInt(a.id)));
              }
            }
          })
          .catch((error) => console.error("Error:", error));
      }

      function verificarNuevasAlertas() {
        fetch(`obtener_alertas.php?desde_id=${ultimoIdConocido}&estado=pendiente`)
          .then(response => response.json())
          .then(data => {
            if (data.success && data.data.length > 0) {
              const alertasPendientes = data.data.filter(a => a.estado === 'pendiente');
              
              if (alertasPendientes.length > 0) {
                const nuevaAlerta = alertasPendientes.find(a => !alertasNotificadas.has(a.id));
                
                if (nuevaAlerta) {
                  mostrarNotificacionEmergente(nuevaAlerta);
                  alertasNotificadas.add(nuevaAlerta.id);
                }
                
                ultimoIdConocido = Math.max(...data.data.map(a => parseInt(a.id)));
                cargarAlertas();
              }
            }
          })
          .catch(error => console.error('Error:', error));
      }

      function mostrarNotificacionEmergente(alerta) {
        if (alertasNotificadas.has(alerta.id)) return;
        
        alertasNotificadas.add(alerta.id);
        
        const popup = document.getElementById("notificationPopup");
        const iconos = { Accidente: "🚑", Robo: "🚔", Daño: "⚠️" };

        document.getElementById("notifTipo").innerHTML = `${iconos[alerta.tipo]} ${alerta.tipo}`;
        document.getElementById("notifDescripcion").textContent = alerta.descripcion || "Sin descripción";
        document.getElementById("notifUbicacion").innerHTML = `📍 Ubicación: ${alerta.latitud}, ${alerta.longitud}`;

        alertaActualId = alerta.id;
        
        popup.classList.add("show", "blink");
        reproducirSonido();

        setTimeout(() => popup.classList.remove("blink"), 3000);
        setTimeout(() => cerrarNotificacion(), 15000);
      }

      function reproducirSonido() {
        const sound = document.getElementById("alertSound");
        sound.currentTime = 0;
        sound.play().catch(e => console.log("No se pudo reproducir"));

        let count = 0;
        const interval = setInterval(() => {
          count++;
          if (count < 3) {
            sound.currentTime = 0;
            sound.play().catch(e => console.log("Error al reproducir"));
          } else {
            clearInterval(interval);
          }
        }, 1000);
      }

      function cerrarNotificacion() {
        document.getElementById("notificationPopup").classList.remove("show", "blink");
      }

      function verAlertaActual() {
        cerrarNotificacion();
        const elemento = document.querySelector(`[data-id="${alertaActualId}"]`);
        if (elemento) {
          elemento.scrollIntoView({ behavior: "smooth", block: "center" });
        }
      }

      function mostrarAlertas(alertas) {
        const container = document.getElementById("alertasContainer");

        if (!alertas || alertas.length === 0) {
          container.innerHTML = `
            <div class="empty-state">
              <h3>📭 No hay alertas registradas</h3>
              <p style="color: #999; margin-top: 10px;">Las alertas aparecerán aquí cuando se envíen desde la app móvil</p>
            </div>
          `;
          return;
        }

        container.innerHTML = alertas.map(alerta => `
          <div class="alerta-card ${alerta.tipo}" data-id="${alerta.id}">
            <div class="alerta-header">
              <span class="alerta-tipo tipo-${alerta.tipo}">
                ${alerta.tipo === "Accidente" ? "🚑" : alerta.tipo === "Robo" ? "🚔" : "⚠️"}
                ${alerta.tipo}
              </span>
              <span class="alerta-estado estado-${alerta.estado}">
                ${alerta.estado.toUpperCase()}
              </span>
            </div>
            
            <div class="alerta-descripcion">
              ${alerta.descripcion || "Sin descripción"}
            </div>
            
            ${alerta.foto ? `
              <div class="alerta-foto">
                <img src="../api/uploads/${alerta.foto}" alt="Foto de la alerta">
              </div>
            ` : ""}
            
            <div class="alerta-footer">
              <span class="alerta-fecha">📅 ${alerta.fecha_hora}</span>
              <span class="alerta-ubicacion">📍 ${alerta.latitud}, ${alerta.longitud}</span>
            </div>
            
            ${alerta.estado === "pendiente" ? `
              <div class="acciones">
                <button class="btn btn-atender" onclick="cambiarEstado(${alerta.id}, 'atendida')">✅ Atender</button>
                <button class="btn btn-rechazar" onclick="cambiarEstado(${alerta.id}, 'rechazada')">❌ Rechazar</button>
                <button class="btn btn-mapa" onclick="verEnMapa('${alerta.latitud}', '${alerta.longitud}')">🗺️ Ver Mapa</button>
              </div>
            ` : ""}
          </div>
        `).join("");
      }

      function actualizarEstadisticas(alertas) {
        document.getElementById("totalAlertas").textContent = alertas.length;
        document.getElementById("totalPendientes").textContent = alertas.filter(a => a.estado === "pendiente").length;
        document.getElementById("totalAtendidas").textContent = alertas.filter(a => a.estado === "atendida").length;
      }

      function cambiarEstado(id, nuevoEstado) {
        if (!confirm(`¿Confirmar cambio a ${nuevoEstado}?`)) return;

        fetch("cambiar_estado.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id, estado: nuevoEstado }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              alert("Estado actualizado correctamente");
              cargarAlertas();
            } else {
              alert("Error: " + data.message);
            }
          });
      }

      function verEnMapa(lat, lng) {
        const latitud = parseFloat(lat);
        const longitud = parseFloat(lng);
        
        if (isNaN(latitud) || isNaN(longitud)) {
          alert('⚠️ Coordenadas inválidas');
          return;
        }
        
        window.open(`https://www.google.com/maps?q=${latitud},${longitud}`, '_blank');
      }
    </script>
</body>
</html>