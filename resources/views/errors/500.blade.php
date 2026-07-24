@include('errors._trovador', [
    'code' => 500,
    'title' => 'Error del servidor',
    'message' => 'Algo salió mal de nuestro lado. Lo estamos revisando.',
    'button' => 'Reintentar',
    'reload' => true,
])
