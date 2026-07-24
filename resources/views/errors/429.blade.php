@include('errors._trovador', [
    'code' => 429,
    'title' => 'Demasiadas solicitudes',
    'message' => 'Espera un momento antes de volver a intentar.',
    'button' => 'Volver a intentar',
    'reload' => true,
])
