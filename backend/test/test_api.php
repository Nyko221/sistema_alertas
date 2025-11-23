<?php
// filepath: backend/test/test_api.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test API - Cementerio</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        h1 { 
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        label {
            display: block;
            color: #555;
            font-weight: 600;
            margin: 15px 0 5px 0;
        }
        input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border 0.3s;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }
        button {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
            transition: transform 0.2s;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .result {
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            display: none;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 2px solid #28a745;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test API - Sistema de Alertas</h1>
        
        <form method="POST" enctype="multipart/form-data" action="../api/recibir_alerta.php">
            <label>🚨 Tipo de Alerta:</label>
            <select name="tipo" required>
                <option value="Accidente">🚑 Accidente</option>
                <option value="Robo">🚔 Robo</option>
                <option value="Daño">⚠️ Daño a Infraestructura</option>
            </select>
            
            <label>📝 Descripción:</label>
            <textarea name="descripcion" rows="4" placeholder="Describe la situación..." required>Prueba de alerta desde formulario web - Testing API</textarea>
            
            <label>📍 Latitud:</label>
            <input type="text" name="latitud" value="-33.391400" placeholder="-33.391400" required>
            
            <label>📍 Longitud:</label>
            <input type="text" name="longitud" value="-70.671600" placeholder="-70.671600" required>
            
            <label>📷 Foto (opcional):</label>
            <input type="file" name="foto" accept="image/*">
            
            <button type="submit">📤 Enviar Alerta de Prueba</button>
        </form>
    </div>
</body>
</html>