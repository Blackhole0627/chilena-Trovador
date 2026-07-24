@include('errors._trovador', [
    'code' => 403,
    'title' => 'Sin acceso',
    'message' => 'No tienes permiso para ver este contenido.',
    'button' => 'Volver al inicio',
    'buttonUrl' => url('/'),
])
