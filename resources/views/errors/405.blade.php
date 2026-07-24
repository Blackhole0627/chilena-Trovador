@include('errors._trovador', [
    'code' => 405,
    'title' => 'Acción no permitida',
    'message' => 'Esta acción no está disponible.',
    'button' => 'Volver al inicio',
    'buttonUrl' => url('/'),
])
