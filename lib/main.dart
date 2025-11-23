import 'dart:io'; // Necesario para el tipo 'File'
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:image_picker/image_picker.dart';
import 'package:geolocator/geolocator.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';

void main() {
  runApp(const AlertApp());
}

enum AppScreen { initial, typeSelect, reportForm }

class AlertApp extends StatelessWidget {
  const AlertApp({super.key});
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'Sistema de Alertas',
      // Aplicamos la fuente 'Inter' a toda la app, como en el CSS
      theme: ThemeData(
        textTheme: GoogleFonts.interTextTheme(Theme.of(context).textTheme),
        // Definimos los colores principales para reusar
        colorScheme: ColorScheme.fromSeed(
          seedColor: const Color(0xFF334155), // Tono de 'slate'
          primary: const Color(0xFF334155), // slate-700
          error: const Color(0xFFDC2626), // red-600
          background: const Color(0xFFF3F4F6), // gray-100
        ),
        scaffoldBackgroundColor: const Color(0xFFF3F4F6), // gray-100
      ),
      home: const AlertScreen(),
    );
  }
}

// Usamos un StatefulWidget para manejar el estado (qué pantalla se muestra)
class AlertScreen extends StatefulWidget {
  const AlertScreen({super.key});

  @override
  State<AlertScreen> createState() => _AlertScreenState();
}

// Añadimos 'with TickerProviderStateMixin' para la animación
class _AlertScreenState extends State<AlertScreen>
    with TickerProviderStateMixin {
  // Enum para manejar las pantallas de forma legible

  AppScreen _currentScreen = AppScreen.initial;
  String _currentAlertType = '';

  // Controladores para el formulario
  final TextEditingController _descriptionController = TextEditingController();
  int _charCount = 0;
  File? _imageFile;
  final ImagePicker _picker = ImagePicker();

  // Controlador para la animación de pulso
  late AnimationController _animationController;
  late Animation<double> _animation;

  // Variables para geolocalización
  Position? _currentPosition; // Guarda la ubicación actual
  bool _isLoadingLocation = false; // Indica si está obteniendo ubicación
  String _locationMessage = ''; // Mensaje para mostrar al usuario

  @override
  void initState() {
    super.initState();

    // Configuración del contador de caracteres
    _descriptionController.addListener(() {
      setState(() {
        _charCount = _descriptionController.text.length;
      });
    });

    // Configuración de la animación de pulso (CSS .pulse-danger)
    _animationController = AnimationController(
      vsync: this,
      duration: const Duration(
        milliseconds: 1000,
      ), // Mitad del CSS (2s) porque se revierte
    );

    _animation = Tween(begin: 1.0, end: 1.05).animate(
      CurvedAnimation(parent: _animationController, curve: Curves.easeInOut),
    );

    // Repetir la animación
    _animationController.repeat(reverse: true);
  }

  @override
  void dispose() {
    // Siempre limpiar los controladores
    _descriptionController.dispose();
    _animationController.dispose();
    super.dispose();
  }

  // --- MÉTODOS DE LÓGICA ---

  void _navigateTo(AppScreen screen) {
    setState(() {
      _currentScreen = screen;
    });
  }

  void _selectType(String type) {
    setState(() {
      _currentAlertType = type;
      _currentScreen = AppScreen.reportForm;
    });
  }

  void _resetForm() {
    _descriptionController.clear();
    setState(() {
      _imageFile = null;
      _currentAlertType = '';
      _charCount = 0;
    });
  }

  // Función para obtener la ubicación actual del usuario
  Future<void> _getCurrentLocation() async {
    setState(() {
      _isLoadingLocation = true;
      _locationMessage = 'Obteniendo ubicación...';
    });

    bool serviceEnabled;
    LocationPermission permission;

    // 1. Verificar si el GPS está activado
    serviceEnabled = await Geolocator.isLocationServiceEnabled();
    if (!serviceEnabled) {
      setState(() {
        _isLoadingLocation = false;
        _locationMessage = 'Por favor, activa el GPS';
      });
      return;
    }

    // 2. Verificar permisos de ubicación
    permission = await Geolocator.checkPermission();
    if (permission == LocationPermission.denied) {
      permission = await Geolocator.requestPermission();
      if (permission == LocationPermission.denied) {
        setState(() {
          _isLoadingLocation = false;
          _locationMessage = 'Permiso denegado';
        });
        return;
      }
    }

    if (permission == LocationPermission.deniedForever) {
      setState(() {
        _isLoadingLocation = false;
        _locationMessage =
            'Permiso denegado permanentemente. Ve a configuración.';
      });
      return;
    }

    // 3. Obtener ubicación actual
    try {
      Position position = await Geolocator.getCurrentPosition(
        desiredAccuracy: LocationAccuracy.high, // Precisión alta
      );
      setState(() {
        _currentPosition = position;
        _isLoadingLocation = false;
        _locationMessage = '¡Ubicación obtenida!';
      });
    } catch (e) {
      setState(() {
        _isLoadingLocation = false;
        _locationMessage = 'Error: $e';
      });
    }
  }

  Future<void> _pickImage() async {
    // Usamos 'image_picker' para reemplazar <input type="file">
    // Puedes cambiar ImageSource.gallery por .camera
    final XFile? pickedFile = await _picker.pickImage(
      source: ImageSource.gallery,
    );

    if (pickedFile != null) {
      setState(() {
        _imageFile = File(pickedFile.path);
      });
    }
  }

  Future<void> _submitReport() async {
    // --- SIMULACIÓN DE ENVÍO ---
    // En una app real, aquí es donde enviarías los datos a un backend/API
    // (usando http, dio, etc.)
    debugPrint("--- SIMULACIÓN DE REPORTE ENVIADO ---");
    debugPrint("Tipo: $_currentAlertType");
    debugPrint("Descripción: ${_descriptionController.text}");
    debugPrint("Foto: ${_imageFile?.path ?? 'No adjuntada'}");
    // NUEVO: Imprimir ubicación
    if (_currentPosition != null) {
      debugPrint(
        "Ubicación - Lat: ${_currentPosition!.latitude}, Lng: ${_currentPosition!.longitude}",
      );
    } else {
      debugPrint("Ubicación: No proporcionada");
    }
    // Mostrar el modal de éxito (HTML #modal-success)
    // 'showDialog' es el equivalente de Flutter a un modal
    // 'context' debe estar disponible, por eso no usamos 'await' si el widget puede desaparecer
    if (!mounted) return; // Buena práctica

    showDialog(
      context: context,
      barrierDismissible: false, // No se cierra al tocar fuera
      builder: (BuildContext dialogContext) {
        return AlertDialog(
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(16),
          ),
          title: const Text("Reporte Enviado"),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              // Icono de éxito
              Container(
                width: 64,
                height: 64,
                decoration: BoxDecoration(
                  color: Colors.green[100],
                  shape: BoxShape.circle,
                ),
                child: Icon(Icons.check, color: Colors.green[600], size: 40),
              ),
              const SizedBox(height: 16),
              Text(
                'Su alerta de "$_currentAlertType" ha sido enviada. El personal de seguridad ha sido notificado.',
                textAlign: TextAlign.center,
              ),
            ],
          ),
          actionsAlignment: MainAxisAlignment.center,
          actions: [
            // Botón Aceptar (HTML #btn-modal-close)
            TextButton(
              onPressed: () {
                Navigator.of(dialogContext).pop(); // Cierra el diálogo
                _resetForm();
                _navigateTo(AppScreen.initial); // Vuelve al inicio
              },
              child: const Text("Aceptar", style: TextStyle(fontSize: 16)),
            ),
          ],
        );
      },
    );
  }

  // --- MÉTODOS DE CONSTRUCCIÓN (BUILD) ---

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      // 'body' con 'bg-gray-100 flex items-center justify-center'
      body: Center(
        child: SingleChildScrollView(
          // Para evitar overflow en pantallas pequeñas
          child: Padding(
            padding: const EdgeInsets.all(16.0),
            // El 'div' principal (la tarjeta blanca)
            child: Container(
              // 'bg-white p-6 sm:p-8 rounded-2xl shadow-xl w-full max-w-md'
              width: double.infinity,
              constraints: const BoxConstraints(maxWidth: 500), // max-w-md
              padding: const EdgeInsets.all(24.0), // p-6
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16.0), // rounded-2xl
                boxShadow: const [
                  BoxShadow(
                    color: Color(0x1A000000), // Sombra sutil
                    blurRadius: 25.0,
                    offset: Offset(0, 10),
                  ), // shadow-xl
                ],
              ),
              // Aquí está la lógica de "mostrar/ocultar" divs
              // Reconstruimos el 'child' basado en el estado '_currentScreen'
              child: AnimatedSwitcher(
                duration: const Duration(milliseconds: 300),
                child: _buildCurrentScreen(),
              ),
            ),
          ),
        ),
      ),
    );
  }

  // Este widget actúa como el 'router' que decide qué "pantalla" mostrar
  Widget _buildCurrentScreen() {
    // Usamos el 'key' para que AnimatedSwitcher detecte el cambio
    switch (_currentScreen) {
      case AppScreen.initial:
        return _buildScreenInitial(key: const ValueKey('initial'));
      case AppScreen.typeSelect:
        return _buildScreenTypeSelect(key: const ValueKey('typeSelect'));
      case AppScreen.reportForm:
        return _buildScreenReportForm(key: const ValueKey('reportForm'));
    }
  }

  /// PANTALLA 1: Botón de Alerta Principal
  Widget _buildScreenInitial({Key? key}) {
    return Column(
      key: key,
      mainAxisSize: MainAxisSize.min, // Para que la columna no se expanda
      children: [
        // 'h1'
        const Text(
          "Sistema de Alertas",
          style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
          textAlign: TextAlign.center,
        ),
        const SizedBox(height: 4),
        // 'p'
        Text(
          "Cementerio General",
          style: TextStyle(fontSize: 16, color: Colors.grey[600]),
          textAlign: TextAlign.center,
        ),
        const SizedBox(height: 32),
        // Icono SVG de alerta (reemplazado con Icono de Material)
        Icon(
          Icons.warning_amber_rounded,
          color: Theme.of(context).colorScheme.error, // red-600
          size: 96, // w-24 h-24
        ),
        const SizedBox(height: 32),
        // Botón de Alerta con Animación de Pulso
        ScaleTransition(
          scale: _animation,
          // Botón 'btn-start-alert'
          child: ElevatedButton(
            onPressed: () => _navigateTo(AppScreen.typeSelect),
            style: ElevatedButton.styleFrom(
              backgroundColor: Theme.of(
                context,
              ).colorScheme.error, // bg-red-600
              foregroundColor: Colors.white, // text-white
              minimumSize: const Size(double.infinity, 60), // w-full py-4
              padding: const EdgeInsets.symmetric(vertical: 16, horizontal: 24),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(8.0), // rounded-lg
              ),
            ),
            child: const Text(
              "ALERTA",
              style: TextStyle(
                fontSize: 20,
                fontWeight: FontWeight.bold, // font-bold
              ),
            ),
          ),
        ),
        const SizedBox(height: 16),
        // 'p'
        Text(
          "Presione solo en caso de emergencia.",
          style: TextStyle(fontSize: 14, color: Colors.grey[500]), // text-sm
          textAlign: TextAlign.center,
        ),
      ],
    );
  }

  /// PANTALLA 2: Selección de Tipo de Alerta
  Widget _buildScreenTypeSelect({Key? key}) {
    return Column(
      key: key,
      mainAxisSize: MainAxisSize.min,
      children: [
        const Text(
          "Seleccione el tipo de alerta",
          style: TextStyle(fontSize: 20, fontWeight: FontWeight.w600),
          textAlign: TextAlign.center,
        ),
        const SizedBox(height: 24),
        // 'div' con 'flex flex-col space-y-4'
        // Botón Accidente
        _buildTypeButton(
          icon: Icons.medical_services_outlined, // Icono reemplazado
          label: "Accidente",
          onPressed: () => _selectType("Accidente"),
        ),
        const SizedBox(height: 16), // space-y-4
        // Botón Robo
        _buildTypeButton(
          icon: Icons.security_outlined, // Icono reemplazado
          label: "Robo",
          onPressed: () => _selectType("Robo"),
        ),
        const SizedBox(height: 16),
        // Botón Daño
        _buildTypeButton(
          icon: Icons.construction_outlined, // Icono reemplazado
          label: "Daño al Establecimiento",
          onPressed: () => _selectType("Daño al Establecimiento"),
        ),
        const SizedBox(height: 24),
        // Botón Cancelar ('btn-cancel-type')
        TextButton(
          onPressed: () => _navigateTo(AppScreen.initial),
          style: TextButton.styleFrom(
            minimumSize: const Size(double.infinity, 44),
            backgroundColor: Colors.grey[200],
            foregroundColor: Colors.grey[800],
          ),
          child: const Text("Cancelar"),
        ),
      ],
    );
  }

  // Widget helper para los botones de tipo (reutilizable)
  Widget _buildTypeButton({
    required IconData icon,
    required String label,
    required VoidCallback onPressed,
  }) {
    // 'button' con 'btn-type flex items-center w-full...'
    return ElevatedButton(
      onPressed: onPressed,
      style: ElevatedButton.styleFrom(
        backgroundColor: Theme.of(context).colorScheme.primary, // bg-slate-700
        foregroundColor: Colors.white, // text-white
        minimumSize: const Size(double.infinity, 60),
        padding: const EdgeInsets.all(16), // p-4
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(8.0), // rounded-lg
        ),
        alignment: Alignment.centerLeft, // text-left
      ),
      child: Row(
        children: [
          Icon(icon, size: 32), // w-8 h-8
          const SizedBox(width: 16), // mr-4
          // 'span' con 'text-lg font-medium'
          Expanded(
            child: Text(
              label,
              style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w500),
            ),
          ),
          const Icon(Icons.chevron_right, size: 24), // 'span' con '>'
        ],
      ),
    );
  }

  /// PANTALLA 3: Formulario de Reporte
  Widget _buildScreenReportForm({Key? key}) {
    return Column(
      key: key,
      mainAxisSize: MainAxisSize.min,
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // 'h2' con 'form-title'
        Text(
          "Reportar: $_currentAlertType",
          style: const TextStyle(fontSize: 20, fontWeight: FontWeight.w600),
          textAlign: TextAlign.center,
        ),
        const SizedBox(height: 24),
        // --- SECCIÓN DE GEOLOCALIZACIÓN ---
        // --- UBICACIÓN GPS (funciona sin internet) ---
        Container(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: Colors.orange[50],
            borderRadius: BorderRadius.circular(8.0),
            border: Border.all(color: Colors.orange[300]!, width: 2),
          ),
          child: Column(
            children: [
              Row(
                children: [
                  Icon(
                    Icons.location_on,
                    color: _currentPosition != null
                        ? Colors.green
                        : Colors.orange[700],
                    size: 32,
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text(
                          'Ubicación de la emergencia',
                          style: TextStyle(
                            fontWeight: FontWeight.bold,
                            fontSize: 16,
                          ),
                        ),
                        Text(
                          _locationMessage.isEmpty
                              ? 'Obtén tu ubicación GPS'
                              : _locationMessage,
                          style: TextStyle(
                            fontSize: 12,
                            color: Colors.grey[700],
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),

              // COORDENADAS GPS (funcionan SIN internet)
              if (_currentPosition != null) ...[
                const SizedBox(height: 12),

                Container(
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: Colors.green[50],
                    borderRadius: BorderRadius.circular(6),
                    border: Border.all(color: Colors.green, width: 2),
                  ),
                  child: Column(
                    children: [
                      const Icon(
                        Icons.check_circle,
                        color: Colors.green,
                        size: 32,
                      ),
                      const SizedBox(height: 8),
                      const Text(
                        '✓ Ubicación GPS obtenida',
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                          color: Colors.green,
                        ),
                      ),
                      const Divider(height: 20),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          const Icon(
                            Icons.share_location,
                            size: 16,
                            color: Colors.grey,
                          ),
                          const SizedBox(width: 8),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  'Lat: ${_currentPosition!.latitude.toStringAsFixed(6)}',
                                  style: const TextStyle(
                                    fontSize: 14,
                                    fontWeight: FontWeight.bold,
                                    fontFamily: 'monospace',
                                  ),
                                ),
                                Text(
                                  'Lng: ${_currentPosition!.longitude.toStringAsFixed(6)}',
                                  style: const TextStyle(
                                    fontSize: 14,
                                    fontWeight: FontWeight.bold,
                                    fontFamily: 'monospace',
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 8),
                      Container(
                        padding: const EdgeInsets.all(8),
                        decoration: BoxDecoration(
                          color: Colors.blue[50],
                          borderRadius: BorderRadius.circular(4),
                        ),
                        child: const Row(
                          children: [
                            Icon(
                              Icons.info_outline,
                              size: 14,
                              color: Colors.blue,
                            ),
                            SizedBox(width: 6),
                            Expanded(
                              child: Text(
                                'El guardia verá tu ubicación en el mapa',
                                style: TextStyle(
                                  fontSize: 11,
                                  color: Colors.blue,
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),

                const SizedBox(height: 8),

                // MAPA (solo en Android/iOS, no en Windows)
                if (Theme.of(context).platform == TargetPlatform.android ||
                    Theme.of(context).platform == TargetPlatform.iOS)
                  Container(
                    height: 150,
                    decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(8),
                      border: Border.all(color: Colors.grey[300]!),
                      color: Colors.grey[100],
                    ),
                    child: ClipRRect(
                      borderRadius: BorderRadius.circular(8),
                      child: Stack(
                        children: [
                          GoogleMap(
                            initialCameraPosition: CameraPosition(
                              target: LatLng(
                                _currentPosition!.latitude,
                                _currentPosition!.longitude,
                              ),
                              zoom: 16,
                            ),
                            markers: {
                              Marker(
                                markerId: const MarkerId('emergency'),
                                position: LatLng(
                                  _currentPosition!.latitude,
                                  _currentPosition!.longitude,
                                ),
                                infoWindow: InfoWindow(
                                  title: '¡Emergencia!',
                                  snippet: _currentAlertType,
                                ),
                              ),
                            },
                            myLocationEnabled: true,
                            myLocationButtonEnabled: false,
                            zoomControlsEnabled: false,
                          ),
                          Positioned(
                            bottom: 4,
                            right: 4,
                            child: Container(
                              padding: const EdgeInsets.symmetric(
                                horizontal: 6,
                                vertical: 2,
                              ),
                              decoration: BoxDecoration(
                                color: Colors.black54,
                                borderRadius: BorderRadius.circular(4),
                              ),
                              child: const Text(
                                'Vista previa',
                                style: TextStyle(
                                  color: Colors.white,
                                  fontSize: 9,
                                ),
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
              ],

              const SizedBox(height: 12),

              // Botón para obtener ubicación GPS
              ElevatedButton.icon(
                onPressed: _isLoadingLocation ? null : _getCurrentLocation,
                icon: _isLoadingLocation
                    ? const SizedBox(
                        width: 16,
                        height: 16,
                        child: CircularProgressIndicator(
                          strokeWidth: 2,
                          color: Colors.white,
                        ),
                      )
                    : Icon(
                        _currentPosition == null
                            ? Icons.my_location
                            : Icons.refresh,
                        size: 18,
                      ),
                label: Text(
                  _currentPosition == null
                      ? 'Obtener mi ubicación GPS'
                      : 'Actualizar ubicación',
                ),
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.orange[700],
                  foregroundColor: Colors.white,
                  minimumSize: const Size(double.infinity, 44),
                ),
              ),

              if (_currentPosition == null)
                const Padding(
                  padding: EdgeInsets.only(top: 8),
                  child: Text(
                    '(No necesita internet)',
                    style: TextStyle(fontSize: 11, color: Colors.grey),
                  ),
                ),
            ],
          ),
        ),
        const SizedBox(height: 16),
        // --- Input de Foto (HTML #file-upload) ---
        // 'label' para 'file-upload'
        GestureDetector(
          onTap: _pickImage,
          child: Container(
            width: double.infinity,
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.blue[50], // bg-blue-50
              borderRadius: BorderRadius.circular(8.0), // rounded-lg
              // border-2 border-dashed border-blue-300
              // Flutter no soporta 'dashed' por defecto, usamos sólido
              border: Border.all(color: Colors.blue[300]!, width: 2),
            ),
            child: Column(
              children: [
                Icon(
                  Icons.add_a_photo_outlined,
                  color: Colors.blue[700],
                  size: 40,
                ), // w-10 h-10
                const SizedBox(height: 8),
                Text(
                  _imageFile == null
                      ? "Adjuntar Foto (Opcional)"
                      : _imageFile!.path.split('/').last, // Muestra el nombre
                  style: TextStyle(
                    color: Colors.blue[700],
                    fontWeight: FontWeight.w500,
                  ),
                  textAlign: TextAlign.center,
                ),
              ],
            ),
          ),
        ),

        // Vista previa de la imagen (HTML #image-preview)
        if (_imageFile != null)
          Padding(
            padding: const EdgeInsets.only(top: 16.0),
            child: ClipRRect(
              borderRadius: BorderRadius.circular(8.0),
              child: Image.file(
                _imageFile!,
                width: double.infinity,
                height: 180,
                fit: BoxFit.cover,
              ),
            ),
          ),
        const SizedBox(height: 16),

        // --- Input de Texto (HTML #text-context) ---
        const Text(
          "Breve descripción",
          style: TextStyle(fontSize: 14, fontWeight: FontWeight.w500),
        ),
        const SizedBox(height: 4),
        TextField(
          controller: _descriptionController,
          maxLines: 4, // rows="4"
          maxLength: 280, // maxlength="280"
          decoration: InputDecoration(
            hintText: "Dé contexto de lo sucedido...",
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(8.0),
              borderSide: BorderSide(color: Colors.grey[300]!),
            ),
            focusedBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(8.0),
              borderSide: BorderSide(
                color: Theme.of(context).colorScheme.primary,
                width: 2,
              ),
            ),
            // El 'counter' de 'maxLength' se muestra automáticamente.
            // Para replicar el "0 / 280" exacto, usamos 'counterText'
            counterText: "$_charCount / 280",
          ),
        ),
        const SizedBox(height: 16),

        // --- Botones de Acción ---
        ElevatedButton(
          onPressed: _submitReport,
          style: ElevatedButton.styleFrom(
            backgroundColor: Colors.green[600], // bg-green-600
            foregroundColor: Colors.white,
            minimumSize: const Size(double.infinity, 50),
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(8.0),
            ),
          ),
          child: const Text(
            "Enviar Reporte",
            style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
          ),
        ),
        const SizedBox(height: 8),
        TextButton(
          onPressed: () {
            // Cancelar y volver a la selección
            _resetForm(); // Limpia solo el formulario
            setState(() {
              _currentAlertType = ''; // Quita el tipo
              _currentScreen =
                  AppScreen.typeSelect; // Vuelve a la pantalla anterior
            });
          },
          style: TextButton.styleFrom(
            minimumSize: const Size(double.infinity, 44),
            backgroundColor: Colors.grey[200],
            foregroundColor: Colors.grey[800],
          ),
          child: const Text("Cancelar"),
        ),
      ],
    );
  }
}
