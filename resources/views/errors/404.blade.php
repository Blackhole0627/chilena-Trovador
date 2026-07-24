@include('errors._trovador', [
    'code' => 404,
    'title' => 'Página no encontrada',
    'message' => 'Esta página no existe o fue eliminada.',
    'button' => 'Volver al inicio',
    'buttonUrl' => url('/'),
])
