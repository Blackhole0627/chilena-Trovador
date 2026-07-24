@include('errors._trovador', [
    'code' => 401,
    'title' => 'Sin autorización',
    'message' => 'Necesitas iniciar sesión para ver esto.',
    'button' => 'Iniciar sesión',
    'buttonUrl' => url('/login'),
])
