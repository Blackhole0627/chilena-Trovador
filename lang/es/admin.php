<?php

return [
    'dashboard' => [

    ],

    'common' => [
        'created_at' => 'Creado el',
        'updated_at' => 'Actualizado el',
        'expiring_at' => 'Expira el',
        'canceled_at' => 'Cancelado el',
        'create' => 'Crear',
        'edit' => 'Actualizar',
        'delete' => 'Eliminar',
        'view' => 'Ver',
        'id' => 'ID',
        'files' => 'Archivos',
    ],

    'navigation' => [
        'dashboard' => 'Panel',
        'groups' => [
            'users' => 'Usuarios',
            'posts' => 'Publicaciones',
            'finances' => 'Finanzas',
            'taxes' => 'Impuestos',
            'stories' => 'Formato corto',
            'streams' => 'Transmisiones',
            'site' => 'Sitio',
            'settings' => 'Configuración',
        ],
    ],

    'filters' => [
        'title' => 'Filtros',
        'start_date' => 'Fecha de inicio',
        'end_date' => 'Fecha de término',
        'today' => 'Hoy',
        'week' => 'Última semana',
        'month' => 'Último mes',
        'year' => 'Este año',
        'last_month' => 'Últimos 30 días',
        'last_year' => 'Últimos 12 meses',

    ],

    'widgets' => [
        'stats_overview' => [
            'title' => 'Resumen de los últimos 7 días',

            'revenue' => [
                'label' => 'Ingresos',
                'description' => 'Ingresos totales generados',
            ],
            'new_users' => [
                'label' => 'Nuevos usuarios',
                'description' => 'Usuarios registrados',
            ],
            'new_payments' => [
                'label' => 'Pagos',
                'description' => 'Transacciones completadas',
            ],
        ],

        'users_chart' => [
            'title' => 'Usuarios',
            'datasets' => [
                'users' => 'Usuarios',
                'user_messages' => 'Mensajes de usuarios',
            ],
        ],

        'posts_chart' => [
            'title' => 'Publicaciones',
            'filters' => [
                'today' => 'Hoy',
                'week' => 'Última semana',
                'month' => 'Último mes',
                'year' => 'Este año',
            ],
            'datasets' => [
                'posts' => 'Publicaciones',
                'comments' => 'Comentarios',
                'reactions' => 'Reacciones',
            ],
        ],

        'transactions_chart' => [
            'title' => 'Pagos',
            'filters' => [
                'today' => 'Hoy',
                'week' => 'Última semana',
                'month' => 'Último mes',
                'year' => 'Este año',
            ],
            'datasets' => [
                'transactions' => 'Pagos',
                'subscriptions' => 'Suscripciones',
            ],
        ],

        'streams_chart' => [
            'title' => 'Transmisiones',
            'filters' => [
                'today' => 'Hoy',
                'week' => 'Última semana',
                'month' => 'Último mes',
                'year' => 'Este año',
            ],
            'datasets' => [
                'streams' => 'Transmisiones',
                'stream_messages' => 'Mensajes de transmisiones',
            ],
        ],

        'product_info' => [
            'title' => 'Inicio rápido',
            'website' => [
                'title' => 'Sitio web',
                'description' => 'Visita la página oficial del producto',
            ],
            'documentation' => [
                'title' => 'Documentación',
                'description' => 'Visita la documentación oficial del producto',
            ],
            'changelog' => [
                'title' => 'Registro de cambios',
                'description' => 'Visita el registro de cambios oficial del producto',
            ],
        ],

        'transaction_stats' => [
            'heading' => 'Pagos de este año',
            'total' => 'Pagos totales',
            'completed' => 'Pagos completados',
            'average' => 'Precio promedio',
        ],

        'subscription_stats' => [
            'heading' => 'Suscripciones de este año',
            'total' => 'Suscripciones totales',
            'active' => 'Suscripciones actualmente activas',
            'average_price' => 'Precio promedio',
        ],

    ],

    'resources' => [
        'user' => [
            'label' => 'Usuario',
            'plural' => 'Usuarios',
            'sections' => [
                'account_info' => 'Información de la cuenta',
                'paywall_info' => 'Información del muro de pago',
                'profile_info' => 'Información del perfil',
                'withdrawals_info' => 'Información de retiros',
                'security_info' => 'Información de seguridad',
                'billing_info' => 'Información de facturación',
            ],
            'fields' => [
                'id' => 'ID',
                'name' => 'Nombre',
                'email' => 'Correo',
                'username' => 'Nombre de usuario',
                'password' => 'Contraseña',
                'roles' => 'Rol',
                'email_verified_at' => 'Correo verificado el',
                'identity_verified_at' => 'Identidad verificada el',
                'birthdate' => 'Fecha de nacimiento',
                'paid_profile' => 'Perfil de pago',
                'public_profile' => 'Perfil público',
                'open_profile' => 'Perfil abierto',
                'profile_access_price' => 'Precio de acceso',
                'profile_access_price_3_months' => 'Precio de acceso por 3 meses',
                'profile_access_price_6_months' => 'Precio de acceso por 6 meses',
                'profile_access_price_12_months' => 'Precio de acceso por 12 meses',
                'current_avatar' => 'Avatar actual',
                'avatar' => 'Avatar',
                'current_cover' => 'Portada actual',
                'cover' => 'Portada',
                'bio' => 'Biografía',
                'location' => 'Ubicación',
                'gender_id' => 'Género',
                'gender_pronoun' => 'Pronombre',
                'website' => 'Sitio web',
                'referral_code' => 'Código de referido',
                'stripe_account_id' => 'ID de Stripe Connect',
                'country_id' => 'País de Stripe Connect',
                'stripe_onboarding_verified' => 'Registro de Stripe verificado',
                'last_ip' => 'Última IP',
                'last_active_at' => 'Última actividad el',
                'enable_geoblocking' => 'Activar bloqueo geográfico',
                'enable_2fa' => 'Activar 2FA',
                'billing_address' => 'Dirección de facturación',
                'first_name' => 'Nombre',
                'last_name' => 'Apellido',
                'city' => 'Ciudad',
                'country' => 'País',
                'state' => 'Región',
                'postcode' => 'Código postal',
                'gender' => 'Género',
            ],
            'actions' => [
                'impersonate' => 'Suplantar',
                'profile_url' => 'URL del perfil',
            ],
        ],

        'user_verify' => [
            'label' => 'Verificación de identidad',
            'plural' => 'Verificaciones de identidad',

            'sections' => [
                'verification_details' => 'Detalles de verificación',
                'verification_details_descr' => 'Administra la solicitud de verificación del usuario.',
            ],

            'tabs' => [
                'all' => 'Todas',
                'pending' => 'Pendiente',
                'approved' => 'Aprobada',
                'rejected' => 'Rechazada',
            ],

            'fields' => [
                'user_id' => 'Usuario',
                'status' => 'Estado',
                'rejectionReason' => 'Motivo de rechazo',
                'files' => 'Vista previa de archivos'
            ],

            'actions' => [
                'profile_url' => 'URL del perfil',
                'approve' => 'Aprobar',
                'reject' => 'Rechazar',
            ],

            'navigation_badge_tooltip' => 'La cantidad de verificaciones de identidad pendientes',
        ],

        'release_form' => [
            'label' => 'Formulario de autorización',
            'plural' => 'Formularios de autorización',

            'sections' => [
                'release_form_details' => 'Detalles del formulario de autorización',
            ],

            'tabs' => [
                'all' => 'Todos',
                'pending' => 'Pendiente',
                'approved' => 'Aprobado',
                'rejected' => 'Rechazado',
            ],

            'status_labels' => [
                'pending' => 'Pendiente',
                'approved' => 'Aprobado',
                'rejected' => 'Rechazado',
            ],

            'fields' => [
                'user_id' => 'Creador',
                'title' => 'Título',
                'status' => 'Estado',
                'files' => 'Archivos',
                'reviewed_by' => 'Revisado por',
                'reviewed_at' => 'Revisado el',
                'notes' => 'Notas del creador',
                'rejection_reason' => 'Motivo de rechazo',
            ],

            'actions' => [
                'approve' => 'Aprobar',
                'reject' => 'Rechazar',
            ],

            'navigation_badge_tooltip' => 'La cantidad de formularios de autorización pendientes',
        ],

        'wallet' => [
            'label' => 'Billetera',
            'plural' => 'Billeteras',

            'sections' => [
                'wallet_details' => 'Detalles de la billetera',
            ],

            'fields' => [
                'id' => 'ID de la billetera',
                'user_id' => 'Usuario',
                'total' => 'Monto total',
                'created_at' => 'Creado el',
                'updated_at' => 'Actualizado el',
            ],

            'helper_texts' => [
                'id' => 'Se prefiere el formato UUID.',
            ],
        ],

        'notification' => [
            'label' => 'Notificación',
            'plural' => 'Notificaciones',

            'sections' => [
                'general_info' => 'Información general',
                'notification_details' => 'Detalles de la notificación',
                'linked_models' => 'Modelos vinculados',
            ],

            'fields' => [
                'id' => 'ID de la notificación',
                'from_user_id' => 'Del usuario',
                'to_user_id' => 'Al usuario',
                'type' => 'Tipo de notificación',
                'read' => 'Marcar como leída',
                'post_id' => 'ID de la publicación',
                'post_comment_id' => 'ID del comentario de la publicación',
                'subscription_id' => 'ID de la suscripción',
                'transaction_id' => 'ID de la transacción',
                'reaction_id' => 'ID de la reacción',
                'withdrawal_id' => 'ID del retiro',
                'user_message_id' => 'ID del mensaje del usuario',
                'stream_id' => 'ID de la transmisión'
            ],

            'helper_texts' => [
                'id' => 'Se prefiere el formato UUID.',
                'read' => 'Indica si el usuario ha visto la notificación.',
            ],

            'types' => [
                'ppv_unlock' => 'Contenido desbloqueado',
                'expiring_stream' => 'Transmisión por expirar',
                'new_message' => 'Nuevo mensaje',
                'withdrawal_action' => 'Actualización de retiro',
                'new_subscription' => 'Nueva suscripción',
                'new_comment' => 'Nuevo comentario',
                'new_reaction' => 'Nueva reacción',
                'new_tip' => 'Nueva propina',
                'mention' => 'Mención',
            ],
        ],

        'user_message' => [
            'label' => 'Mensaje',
            'plural' => 'Mensajes',

            'sections' => [
                'user_message_details' => 'Detalles del mensaje del usuario',
                'user_message_details_descr' => 'Administra los mensajes directos entre usuarios.',
            ],

            'fields' => [
                'sender_id' => 'Remitente',
                'receiver_id' => 'Destinatario',
                'message' => 'Contenido del mensaje',
                'price' => 'Precio (opcional)',
                'replyTo' => 'ID del mensaje al que responde',
                'isSeen' => 'Visto',
                 'story_id' => 'Historia',
            ],

            'attachments' => [
                'title' => 'Ver adjuntos de :name',
                'breadcrumb' => 'Adjuntos',
                'nav_label' => 'Ver adjuntos',
                'file_link' => 'Abrir archivo',
                'actions' => [
                    'create' => 'Agregar nuevo adjunto',
                ],
            ],

            'transactions' => [
                'title' => 'Ver pagos de :record',
                'breadcrumb' => 'Pagos',
                'nav_label' => 'Ver pagos',
                'fields' => [
                    'id' => 'ID',
                    'sender' => 'Remitente',
                    'payer' => 'Pagador',
                    'status' => 'Estado',
                    'type' => 'Tipo',
                    'payment_provider' => 'Proveedor',
                    'amount' => 'Monto',
                ],
                'actions' => [
                    'create' => 'Agregar nueva transacción',
                ],
            ]

        ],

        'reaction' => [
            'label' => 'Reacción',
            'plural' => 'Reacciones',

            'sections' => [
                'reaction_info' => 'Información de la reacción',
                'reaction_info_descr' => 'Detalles sobre el usuario y el tipo de reacción.',
                'target_content' => 'Contenido objetivo',
                'target_content_descr' => 'Especifica el contenido al que está asociada esta reacción.',
            ],

            'fields' => [
                'user_id' => 'Usuario',
                'reaction_type' => 'Tipo de reacción',
                'post_id' => 'ID de la publicación',
                'post_comment_id' => 'ID del comentario'
            ],

            'types' => [
                'like' => 'Me gusta',
            ],
        ],

        'user_list' => [
            'label' => 'Lista',
            'plural' => 'Listas',

            'sections' => [
                'list_details' => 'Detalles de la lista',
                'list_details_descr' => 'Proporciona un nombre y tipo para esta lista de usuarios.',
                'owner' => 'Propietario',
                'owner_descr' => 'Selecciona el usuario propietario de esta lista.',
            ],

            'fields' => [
                'name' => 'Nombre de la lista',
                'type' => 'Tipo de lista',
                'user_id' => 'Propietario de la lista'
            ],

            'placeholders' => [
                'name' => 'Ingresa el nombre de la lista',
            ],

            'types' => [
                'blocked' => 'Usuarios bloqueados',
                'following' => 'Siguiendo',
                'followers' => 'Seguidores',
                'custom' => 'Lista personalizada',
            ],

            'members' => [
                'title' => 'Ver miembros de :name',
                'breadcrumb' => 'Miembros',
                'navigation_label' => 'Ver miembros',
                'fields' => [
                    'id' => 'ID',
                    'username' => 'Usuario',
                    'created_at' => 'Creado el',
                ],
            ],
        ],

        'user_list_member' => [
            'label' => 'Miembro de la lista',
            'plural' => 'Miembros de la lista',

            'actions' => [
                'create' => 'Agregar nuevo miembro',
            ],

            'sections' => [
                'list_association' => 'Asociación a la lista',
                'list_association_descr' => 'Asigna un usuario a una lista específica.',
            ],

            'fields' => [
                'list_id' => 'ID de la lista de usuarios',
                'user_id' => 'Usuario',
            ],

            'placeholders' => [
                'list_id' => 'Selecciona una lista',
                'user_id' => 'Selecciona un usuario',
            ],
        ],

        'user_bookmark' => [
            'label' => 'Marcador',
            'plural' => 'Marcadores',

            'sections' => [
                'bookmark_details' => 'Detalles del marcador',
                'bookmark_details_descr' => 'Vincula un usuario a una publicación marcada.',
            ],

            'fields' => [
                'user_id' => 'Usuario',
                'post_id' => 'ID de la publicación',
                'reel_id' => 'ID del reel',
                'username' => 'Usuario'
            ],
        ],

        'user_report' => [
            'label' => 'Reporte',
            'plural' => 'Reportes',

            'sections' => [
                'reporter_reported' => 'Usuarios que reportan y reportados',
                'reporter_reported_descr' => 'Identifica al usuario que envía el reporte y al usuario reportado.',

                'reported_content' => 'Contenido reportado (opcional)',
                'reported_content_descr' => 'Vincula este reporte a un contenido específico.',

                'report_details' => 'Detalles del reporte',
            ],

            'tabs' => [
                'all' => 'Todos',
                'received' => 'Recibidos',
                'seen' => 'Vistos',
                'solved' => 'Resueltos',
            ],

            'fields' => [
                'from_user_id' => 'Quien reporta',
                'user_id' => 'Usuario reportado',
                'post_id' => 'ID de la publicación',
                'message_id' => 'ID del mensaje',
                'stream_id' => 'ID de la transmisión',
                'type' => 'Motivo del reporte',
                'status' => 'Estado',
                'details' => 'Detalles adicionales',
                'story_id' => 'ID de la historia',
                'reel_id' => 'ID del reel',
                'reel_comment_id' => 'ID del comentario del reel',
            ],

            'types' => [
                'i_dont_like' => 'No me gusta esto',
                'spam' => 'Spam',
                'dmca' => 'DMCA',
                'offensive_content' => 'Contenido ofensivo',
                'abuse' => 'Abuso',
            ],

            'statuses' => [
                'received' => 'Recibido',
                'seen' => 'Visto',
                'solved' => 'Resuelto',
            ],

            'actions' => [
                'view_admin' => 'Ver página de administración',
                'view_public' => 'Ver página pública',
            ],

            'navigation_badge_tooltip' => 'La cantidad de reportes pendientes',
        ],

        'featured_user' => [
            'label' => 'Usuario destacado',
            'plural' => 'Usuarios destacados',

            'sections' => [
                'main' => 'Destacar un usuario',
                'main_descr' => 'Selecciona un usuario para destacar en la plataforma.',
            ],

            'fields' => [
                'user_id' => 'Usuario destacado',
                'username' => 'Nombre de usuario',
                'created_at' => 'Creado el',
                'updated_at' => 'Actualizado el',
            ],
        ],

        'user_tax' => [
            'label' => 'Información tributaria',
            'plural' => 'Información tributaria',

            'sections' => [
                'user' => 'Asociación de usuario',
                'user_descr' => 'Vincula la información tributaria a un usuario y su país emisor.',

                'tax' => 'Identificación tributaria',
                'tax_descr' => 'Detalles de identificación legal y tributaria.',

                'personal' => 'Datos personales',
                'personal_descr' => 'Información personal y de dirección adicional.',
            ],

            'fields' => [
                'user_id' => 'Usuario',
                'issuing_country_id' => 'País emisor',
                'legal_name' => 'Nombre legal',
                'tax_identification_number' => 'Número de identificación tributaria',
                'vat_number' => 'Número de IVA',
                'tax_type' => 'Tipo de impuesto',
                'date_of_birth' => 'Fecha de nacimiento',
                'primary_address' => 'Dirección principal',
                'earnings_ytd' => 'Ganancias del año hasta la fecha (brutas)',
            ],

            'filters' => [
                'min_earnings' => 'Ganancias mínimas',
            ],

            'descriptions' => [
                'primary_address' => 'Ingresa la dirección completa',
            ],

            'placeholders' => [
                'user_id' => 'Selecciona un usuario',
                'issuing_country_id' => 'Selecciona un país',
            ],

            'options' => [
                'types' => [
                    'dac7' => 'DAC7',
                ],
            ],
        ],

        'post_comment' => [
            'label' => 'Comentario',
            'plural' => 'Comentarios',

            'sections' => [
                'post_comment_details' => 'Detalles del comentario de la publicación',
                'post_comment_details_descr' => 'Detalles del comentario de la publicación.',
            ],

            'fields' => [
                'id' => 'ID',
                'author' => 'Usuario',
                'message' => 'Mensaje',
                'post_id' => 'Publicación'
            ],
        ],

        'attachment' => [
            'label' => 'Adjunto',
            'plural' => 'Adjuntos',

            'sections' => [
                'file_and_metadata' => 'Archivo y metadatos',
                'associations' => 'Asociaciones',
                'attachment_details' => 'Detalles del adjunto',
                'attachment_details_descr' => 'Configura o revisa los detalles del adjunto.',
            ],

            'fields' => [
                'id' => 'ID',
                'filename' => 'Nombre del archivo',
                'file' => 'Archivo',
                'driver' => 'Controlador de almacenamiento',
                'type' => 'Tipo',
                'user_id' => 'Usuario',
                'post_id' => 'ID de la publicación',
                'message_id' => 'ID del mensaje',
                'payment_request_id' => 'ID de la solicitud de pago',
                'coconut_id' => 'ID de Coconut',
                'has_thumbnail' => 'Tiene miniatura',
                'has_blurred_preview' => 'Tiene vista previa difuminada',
                'open' => 'Abrir archivo',
                'story_id' => 'Historia',
                'reel_id' => 'Reel',
                'sound_id' => 'Sonido',
                'length'   => 'Duración',
            ],

            'help' => [
                'id' => 'Se prefiere el formato UUID.',
                'driver' => 'Selecciona qué controlador de almacenamiento usar para los recursos del usuario.',
                'length' => 'Duración del archivo multimedia en segundos.',
            ],
        ],

        'poll' => [
            'label' => 'Encuesta',
            'plural' => 'Encuestas',

            'sections' => [
                'post_details' => 'Detalles de la encuesta',
                'post_details_descr' => 'Configura los detalles de la encuesta.',
            ],

            'fields' => [
                'user_id' => 'Usuario',
                'post_id' => 'ID de la publicación',
                'ends_at' => 'Termina el',
                'answer_id' => 'Respuesta seleccionada',
                'answer' => 'Opción',
                'id' => 'Id',
            ],

            'filters' => [
                'poll.id' => 'ID de la encuesta',
                'user.username' => 'Nombre de usuario',
            ],

            'poll_answers' => [
                'poll_choices' => 'Opciones de la encuesta',
                'choices' => 'Opciones',
                'actions' => [
                    'create' => 'Agregar nueva opción',
                    'edit' => 'Editar opción',
                    'delete' => 'Eliminar opción',
                ]
            ],

            'user_poll_answers' => [
                'label' => 'Respuestas de usuarios',
                'fields' => [
                    'user_id' => 'Usuario',
                    'answer_id' => 'Respuesta seleccionada',
                    'answer' => 'Respuesta',
                ],
                'actions' => [
                    'create' => 'Agregar respuesta',
                    'edit' => 'Editar respuesta',
                    'delete' => 'Eliminar respuesta',
                ],
            ],
        ],

        'transaction' => [

            'label' => 'Transacción',
            'plural' => 'Transacciones',

            'sections' => [
                'participants' => 'Participantes',
                'participants_descr' => 'Define el remitente y el destinatario involucrados en la transacción.',

                'details' => 'Detalles de la transacción',
                'details_descr' => 'Establece el estado, tipo, proveedor y datos principales.',

                'related' => 'Entidades relacionadas',
                'related_descr' => 'Asocia esta transacción con contenido o suscripciones.',

                'provider_info' => 'Información específica del proveedor',
                'provider_info_descr' => 'Agrega IDs o tokens opcionales de proveedores externos.',
            ],

            'fields' => [
                'sender_user_id' => 'Comprador',
                'recipient_user_id' => 'Vendedor',

                'status' => 'Estado',
                'type' => 'Tipo',
                'payment_provider' => 'Proveedor de pago',
                'currency' => 'Código de moneda',
                'amount' => 'Monto',
                'taxes' => 'Impuestos',

                'subscription_id' => 'Suscripción',
                'post_id' => 'Publicación',
                'stream_id' => 'Transmisión',
                'invoice_id' => 'Factura',
                'user_message_id' => 'Mensaje',

                'paypal_payer_id' => 'ID de pagador de PayPal',
                'paypal_transaction_id' => 'ID de transacción de PayPal',
                'paypal_transaction_token' => 'Token de transacción de PayPal',

                'stripe_transaction_id' => 'ID de transacción de Stripe',
                'stripe_session_id' => 'ID de sesión de Stripe',

                'coinbase_charge_id' => 'ID de cargo de Coinbase',
                'coinbase_transaction_token' => 'Token de transacción de Coinbase',

                'nowpayments_payment_id' => 'ID de pago de NowPayments',
                'nowpayments_order_id' => 'ID de orden de NowPayments',

                'ccbill_transaction_token' => 'Token de transacción de CCBill',
                'ccbill_transaction_id' => 'ID de transacción de CCBill',
                'ccbill_subscription_id' => 'ID de suscripción de CCBill',

                'verotel_payment_token' => 'Token de transacción de Verotel',
                'verotel_sale_id' => 'ID de venta de Verotel',

                'paystack_payment_token' => 'Token de pago de Paystack',

                'mercado_payment_token' => 'Token de pago de Mercado Pago',
                'mercado_payment_id' => 'ID de pago de Mercado Pago',
                'yookassa_payment_id' => 'ID de pago de YooMoney',
                'yookassa_payment_token' => 'Token de pago de YooMoney',
                'mollie_payment_id' => 'ID de pago de Mollie',
                'mollie_payment_token' => 'Token de pago de Mollie',
                'flutterwave_payment_id' => 'ID de pago de Flutterwave',
                'flutterwave_payment_token' => 'Token de pago de Flutterwave',
                'coingate_order_id' => 'ID de orden de CoinGate',
                'coingate_payment_token' => 'Token de callback de CoinGate',
                'xendit_payment_id' => 'ID de sesión de pago de Xendit',
                'xendit_payment_token' => 'Token de pago de Xendit',
                'paddle_transaction_id' => 'ID de transacción de Paddle',
                'paddle_transaction_token' => 'Token de transacción de Paddle',
                'cryptocom_payment_id' => 'ID de pago de Crypto.com',
                'cryptocom_payment_token' => 'Token de pago de Crypto.com',

                'sender' => 'Remitente',
                'receiver' => 'Destinatario',
                'receiver_user_id' => 'Vendedor',
                'id' => 'ID'
            ],

            'helpers' => [
                'taxes' => 'Se requiere JSON. Los ejemplos pueden tomarse de transacciones creadas por la aplicación.',
                'taxes_placeholder' => 'Ingresa el desglose de impuestos o notas',
            ],

            'status_labels' => [
                'pending' => 'Pendiente',
                'refunded' => 'Reembolsada',
                'partially_paid' => 'Parcialmente pagada',
                'declined' => 'Rechazada',
                'initiated' => 'Iniciada',
                'canceled' => 'Cancelada',
                'approved' => 'Aprobada',
            ],

            'type_labels' => [
                'tip' => 'Propina',
                'deposit' => 'Depósito',
                'withdrawal' => 'Retiro',
                'chat_tip' => 'Propina de chat',
                'stream_access' => 'Acceso a transmisión',
                'message_unlock' => 'Desbloqueo de mensaje',
                'post_unlock' => 'Desbloqueo de publicación',
                'one_month_subscription' => 'Suscripción de 1 mes',
                'three_months_subscription' => 'Suscripción de 3 meses',
                'six_months_subscription' => 'Suscripción de 6 meses',
                'yearly_subscription' => 'Suscripción anual',
                'subscription_renewal' => 'Renovación de suscripción',
            ],

            'tabs' => [
                'all' => 'Todas',
                'pending' => 'Pendiente',
                'approved' => 'Aprobada',
                'declined' => 'Rechazada',
            ],

        ],

        'post' => [
            'label' => 'Publicación',
            'plural' => 'Publicaciones',

            'sections' => [
                'details' => 'Detalles de la publicación',
                'details_descr' => 'Configura los detalles de la publicación.',
                'settings' => 'Configuración de la publicación',
                'settings_descr' => 'Configuración de precios, estado y tiempos.',
            ],

            'fields' => [
                'user_id' => 'Usuario',
                'text' => 'Texto de la publicación',
                'price' => 'Precio',
                'status' => 'Estado',
                'release_date' => 'Fecha de publicación',
                'expire_date' => 'Fecha de expiración',
                'is_pinned' => 'Fijar esta publicación',
            ],

            'actions' => [
                'post_url' => 'URL de la publicación',
            ],

            'status_labels' => [
                '0' => 'Pendiente',
                '1' => 'Aprobada',
                '2' => 'Rechazada',
            ],
        ],

        'hashtag' => [
            'label' => 'Hashtag',
            'plural' => 'Hashtags',

            'sections' => [
                'hashtag_info' => 'Información del hashtag',
                'hashtag_info_descr' => 'Crea y administra los hashtags usados en publicaciones y comentarios.',
            ],

            'fields' => [
                'tag' => 'Etiqueta',
                'tag_helper' => 'Ingresa el hashtag sin el “#”. Solo se permiten letras, números y guiones bajos (máx. 64). Se almacena en minúsculas.',
            ],
        ],

        'hashtag_link' => [
            'label' => 'Enlace de hashtag',
            'plural' => 'Enlaces de hashtag',
            'fields' => [
                'post_id' => 'ID de la publicación',
                'post_comment_id' => 'ID del comentario',
            ],
        ],

        'subscription' => [
            'label' => 'Suscripción',
            'plural' => 'Suscripciones',

            'sections' => [
                'user_info' => 'Información del usuario',
                'subscription_details' => 'Detalles de la suscripción',
                'platform_identifiers' => 'Identificadores de la plataforma',
                'timestamps' => 'Marcas de tiempo',
            ],

            'fields' => [
                'sender_user_id' => 'Suscriptor',
                'recipient_user_id' => 'Creador',

                'subscriber.username' => 'Suscriptor',
                'creator.username' => 'Creador',

                'type' => 'Tipo',
                'status' => 'Estado',
                'provider' => 'Proveedor de pago',
                'amount' => 'Monto',

                'paypal_agreement_id' => 'ID de acuerdo de PayPal',
                'paypal_plan_id' => 'ID de plan de PayPal',
                'stripe_subscription_id' => 'ID de suscripción de Stripe',
                'ccbill_subscription_id' => 'ID de suscripción de CCBill',
                'verotel_sale_id' => 'ID de venta de Verotel',

                'expires_at' => 'Expira el',
                'canceled_at' => 'Cancelada el',
            ],

            'status_labels' => [
                'active' => 'Activa',
                'completed' => 'Completada',
                'canceled' => 'Cancelada',
                'suspended' => 'Suspendida',
                'expired' => 'Expirada',
                'failed' => 'Fallida',
                'pending' => 'Pendiente',
            ],

            'tabs' => [
                'all' => 'Todas',
                'pending' => 'Pendiente',
                'active' => 'Activa',
                'canceled' => 'Cancelada',
            ],

            'type_labels' => [
                'one_month_subscription' => 'Suscripción de 1 mes',
                'three_months_subscription' => 'Suscripción de 3 meses',
                'six_months_subscription' => 'Suscripción de 6 meses',
                'yearly_subscription' => 'Suscripción de 1 año',
            ],
        ],

        'withdrawal' => [
            'label' => 'Retiro',
            'plural' => 'Retiros',

            'sections' => [
                'details' => 'Detalles del retiro',
                'details_descr' => 'Configura o revisa los detalles de la solicitud de retiro.',
                'payout_summary' => 'Resumen del pago',
                'payout_details' => 'Detalles del pago',
            ],

            'fields' => [
                'id' => 'ID',
                'username' => 'Usuario',
                'amount' => 'Monto',
                'requested_amount' => 'Monto solicitado',
                'fee' => 'Comisión',
                'net_payout' => 'Pago neto',
                'status' => 'Estado',
                'processed' => 'Procesado',
                'payment_method' => 'Método de pago',
                'payout_method_key' => 'Clave del método de pago',
                'payment_identifier' => 'Identificador de pago',
                'stripe_payout_id' => 'ID de pago de Stripe',
                'stripe_transfer_id' => 'ID de transferencia de Stripe',
                'user_id' => 'Usuario',
                'message' => 'Mensaje',
                'notes' => 'Notas',
                'details_label' => 'Detalles',
                'iban' => 'IBAN',
                'paypal_email' => 'Correo de PayPal',
                'wallet_address' => 'Dirección de billetera',
                'payout_destination' => 'Destino del pago',
                'stripe_account' => 'Cuenta de Stripe',
                'account_label' => 'Etiqueta de la cuenta',
                'account_holder' => 'Titular de la cuenta',
                'swift_bic' => 'SWIFT/BIC',
                'bank' => 'Banco',
                'bank_address' => 'Dirección del banco',
                'country' => 'País',
                'method' => 'Método',
            ],

            'helpers' => [
                'stripe_connect_warning' => 'Los retiros con Stripe Connect solo pueden ser creados por los creadores',
                'status_creation_rule' => 'Un nuevo retiro debe crearse con el estado solicitado.',
                'processed_warning' => 'Esta solicitud de retiro ya ha sido procesada',
                'amount_overflow' => "El saldo de crédito de este usuario es menor que el monto del retiro. Intenta con un monto menor",
                'fees_info' => "Las comisiones se calculan automáticamente si las comisiones de retiro están habilitadas en la configuración de pagos.",
                'summary_empty' => 'El resumen del pago aparecerá después de crear el retiro.',
                'payout_details_empty' => 'No hay detalles de pago guardados en este retiro.',
                'stored_notes' => 'Notas del usuario guardadas enviadas con este retiro.',
                'stored_method_reference' => 'Método de retiro guardado solo como referencia.',
                'stored_payout_reference' => 'Destino de pago guardado solo como referencia.',
                'stored_payout_used' => 'Destino de pago guardado usado para este retiro.',
                'stripe_payout_reference' => 'Referencia de pago generada por Stripe.',
                'stripe_transfer_reference' => 'Referencia de transferencia generada por Stripe.',
                'processed_flag' => 'Marca este retiro como ya gestionado. Normalmente se establece automáticamente cuando el retiro es aprobado o rechazado.',
            ],

            'status_labels' => [
                'approved' => 'Aprobado',
                'requested' => 'Solicitado',
                'rejected' => 'Rechazado',
            ],

            'actions' => [
                'approve' => 'Aprobar',
                'reject' => 'Rechazar',
            ],

            'tabs' => [
                'all' => 'Todos',
                'requested' => 'Solicitado',
                'approved' => 'Aprobado',
                'rejected' => 'Rechazado',
            ],

            'export' => [
                'csv' => 'CSV de pagos',
                'gross' => 'Bruto',
                'net' => 'Neto',
                'method' => 'Método',
                'identifier' => 'Identificador',
                'saved_account' => 'Cuenta guardada',
                'payout_details' => 'Detalles del pago',
                'yes' => 'Sí',
                'no' => 'No',
            ],

            'navigation_badge_tooltip' => 'La cantidad de retiros pendientes',
        ],

        'payment_request' => [
            'label' => 'Solicitud de pago',
            'plural' => 'Solicitudes de pago',

            'sections' => [
                'payment_request' => 'Solicitud de pago',
            ],

            'fields' => [
                'user_id' => 'Usuario',
                'transaction_id' => 'ID de la transacción',
                'amount' => 'Monto',
                'status' => 'Estado',
                'type' => 'Tipo',
                'reason' => 'Motivo de rechazo',
                'message' => 'Mensaje',
            ],

            'status_labels' => [
                'approved' => 'Aprobada',
                'pending' => 'Pendiente',
                'rejected' => 'Rechazada',
            ],

            'type_labels' => [
                'deposit' => 'Depósito',
            ],

            'actions' => [
                'approve' => 'Aprobar',
                'reject' => 'Rechazar',
            ],

            'tabs' => [
                'all' => 'Todas',
                'pending' => 'Pendiente',
                'approved' => 'Aprobada',
                'rejected' => 'Rechazada',
            ],
        ],

        'invoice' => [
            'label' => 'Factura',
            'plural' => 'Facturas',

            'sections' => [
                'invoice_info' => 'Información de la factura',
                'invoice_info_descr' => 'Aquí puedes ver los datos codificados de una factura generada.',
            ],

            'fields' => [
                'invoice_id' => 'ID de la factura',
                'transaction_id' => 'ID de la transacción',
                'data' => 'Datos',
            ],

            'actions' => [
                'invoice_url' => 'URL de la factura',
            ],
        ],

        'tax' => [
            'label' => 'Impuesto',
            'plural' => 'Impuestos',

            'sections' => [
                'details' => 'Detalles del impuesto',
                'details_descr' => 'Edita los detalles de las comisiones de tu sitio.',
            ],

            'fields' => [
                'name' => 'Nombre',
                'type' => 'Tipo',
                'percentage' => 'Valor',
                'country_name' => 'País',
                'countries_name' => 'Países',
                'hidden' => 'Oculto',
            ],

            'type_labels' => [
                'fixed' => 'Fijo',
                'exclusive' => 'Exclusivo',
                'inclusive' => 'Inclusivo',
            ],
        ],

        'country' => [
            'label' => 'País',
            'plural' => 'Países',

            'sections' => [
                'country_details' => 'Detalles del país',
                'country_details_descr' => 'Detalles del país/región.',
            ],

            'fields' => [
                'name' => 'Nombre',
                'country_code' => 'Código de país',
                'phone_code' => 'Código telefónico',
                'created_at' => 'Creado el',
                'updated_at' => 'Actualizado el',
            ],
        ],

        'stream' => [
            'label' => 'Transmisión',
            'plural' => 'Transmisiones',

            'sections' => [
                'stream_details' => 'Detalles de la transmisión',
                'stream_details_descr' => 'Detalles básicos sobre la transmisión.',
                'stream_source' => 'Fuente y reproducción de la transmisión',
                'stream_source_descr' => 'Configuración para la entrega de la transmisión y RTMP.',
                'advanced_metadata' => 'Avanzado y metadatos',
            ],

            'fields' => [
                'name' => 'Nombre de la transmisión',
                'slug' => 'Slug',
                'price' => 'Precio de acceso',
                'user_id' => 'Usuario',
                'poster' => 'Imagen de póster',
                'status' => 'Estado',
                'requires_subscription' => 'Requiere suscripción',
                'is_public' => 'Transmisión pública',
                'sent_expiring_reminder' => 'Recordatorio de expiración enviado',

                'driver' => 'Controlador de transmisión',
                'pushr_id' => 'ID de Pushr',
                'rtmp_key' => 'Clave RTMP',
                'rtmp_server' => 'Servidor RTMP',
                'hls_link' => 'Enlace de reproducción HLS',
                'vod_link' => 'Enlace VOD',

                'settings' => 'Configuración de la transmisión (JSON)',
                'ended_at' => 'Finalizada el',
                'created_at' => 'Creada',
                'updated_at' => 'Actualizada',
            ],

            'status_labels' => [
                'all' => 'Todas',
                'in_progress' => 'En progreso',
                'ended' => 'Finalizada',
                'deleted' => 'Eliminada',
            ],

            'driver_labels' => [
                1 => 'PushrCDN',
                2 => 'LiveKit',
            ],
        ],

        'stream_message' => [
            'label' => 'Mensaje de transmisión',
            'plural' => 'Mensajes de transmisión',

            'sections' => [
                'message_details' => 'Detalles del mensaje',
            ],

            'fields' => [
                'user_id' => 'Usuario',
                'stream_id' => 'Transmisión',
                'message' => 'Contenido del mensaje',
                'created_at' => 'Creado el',
                'updated_at' => 'Actualizado el',
            ],

            'help' => [
                'user_id' => 'Selecciona el usuario que envió el mensaje.',
                'stream_id' => 'Elige la transmisión con la que asociar este mensaje.',
                'message' => 'El contenido del mensaje del chat.',
            ],
        ],

        'public_page' => [
            'label' => 'Página pública',
            'plural' => 'Páginas públicas',

            'sections' => [
                'page_details' => 'Detalles de la página',
                'page_details_descr' => 'Configura el contenido y la estructura de esta página pública.',
                'display_settings' => 'Configuración de visualización',
                'display_settings_descr' => 'Controla cómo y dónde aparece esta página.',
            ],

            'fields' => [
                'title' => 'Título',
                'title_helper' => 'Título de la página que se muestra en el encabezado y la lista.',
                'short_title' => 'Título corto',
                'short_title_helper' => 'Título más corto alternativo usado para navegación o menús.',
                'slug' => 'Slug',
                'content' => 'Contenido',
                'slug_helper' => 'Identificador único usado en la URL (sin espacios ni caracteres especiales).',
                'shown_in_footer' => 'Mostrado en el pie de página',
                'shown_in_footer_helper' => 'Actívalo para mostrar esta página en el pie de página del sitio.',
                'is_tos' => 'Términos de servicio',
                'is_tos_helper' => 'Actívalo si esta página representa los Términos de servicio.',
                'is_privacy' => 'Política de privacidad',
                'is_privacy_helper' => 'Actívalo si esta página representa la Política de privacidad.',
                'show_last_update_date' => 'Mostrar fecha de última actualización',
                'show_last_update_date_helper' => 'Si está activado, muestra la fecha de última modificación en la página.',
                'page_order' => 'Orden de la página',
                'page_order_helper' => 'Define el orden en que aparece esta página en los listados.',
                'page_url' => 'URL de la página',
            ],
        ],

        'contact_message' => [
            'label' => 'Mensaje de contacto',
            'plural' => 'Mensajes de contacto',

            'fields' => [
                'email' => 'Correo',
                'subject' => 'Asunto',
                'message' => 'Mensaje',
                'status' => 'Estado',
                'is_replied' => 'Respondido',
                'replied_at' => 'Respondido el',
                'replied_by' => 'Respondido por',
                'reply_details' => 'Detalles de la respuesta',
                'created_at' => 'Creado el',
                'updated_at' => 'Actualizado el',
            ],

            'status' => [
                'pending' => 'Pendiente',
                'replied' => 'Respondido',
                'unknown_replier' => 'administrador desconocido',
            ],

            'helpers' => [
                'is_replied' => 'Usa esto después de responder desde tu cliente de correo.',
            ],

            'reply_details' => 'Marcado como respondido el :date por :user.',

            'filters' => [
                'reply_status' => 'Estado de respuesta',
            ],

            'actions' => [
                'mark_replied' => 'Marcar como respondido',
                'mark_unreplied' => 'Marcar como pendiente',
            ],
        ],

        'global_announcement' => [
            'label' => 'Anuncio',
            'plural' => 'Anuncios',

            'fields' => [
                'content' => 'Contenido',
                'size' => 'Tamaño',
                'expiring_at' => 'Expira el',
                'is_published' => 'Publicado',
                'is_dismissible' => 'Descartable',
                'is_sticky' => 'Fijo',
                'is_global' => 'Global',
                'id_verified_only' => 'Solo con identidad verificada',
            ],

            'helpers' => [
                'is_published' => 'Si el anuncio es visible para los usuarios.',
                'is_dismissible' => 'Permite a los usuarios cerrar u ocultar este anuncio.',
                'is_sticky' => 'Mantiene el anuncio fijado en la parte superior.',
                'is_global' => 'Muestra el anuncio a todos los usuarios del sistema.',
                'id_verified_only' => 'Visible solo para los usuarios que han verificado su identidad.',
            ],

            'sections' => [
                'content' => 'Contenido',
                'content_descr' => 'Detalles del anuncio.',
                'visibility' => 'Visibilidad',
                'visibility_descr' => 'Activa/desactiva los comportamientos de visualización.',
            ],

            'size_labels' => [
                'regular' => 'Normal',
                'small' => 'Pequeño',
            ],
        ],

        'reward' => [
            'label'  => 'Referido',
            'plural' => 'Referidos',

            'sections' => [
                'referral_info'       => 'Información de recompensa por referido',
                'referral_info_descr' => 'Asigna recompensas generadas por la actividad de referidos.',
            ],

            'fields' => [
                'id'                     => 'ID',
                'from_user_id'           => 'Referente',
                'to_user_id'             => 'Usuario referido',
                'referral_code_usage_id' => 'Uso del código de referido',
                'amount'                 => 'Monto de la recompensa',
                'transaction_id'         => 'ID de la transacción',
                'reward_type'            => 'Tipo de recompensa',
            ],

            'help' => [
                'reward_type' => 'Código de tipo para la recompensa.',
            ],
        ],

        'story' => [
            'label'  => 'Historia',
            'plural' => 'Historias',

            'sections' => [
                'details'       => 'Detalles de la historia',
                'details_descr' => 'Campos principales de la historia y propiedad.',
                'settings'      => 'Configuración de la historia',
                'settings_descr'=> 'Opciones de visibilidad, expiración, enlaces y visualización.',
                'overlay'       => 'Superposición',
                'overlay_descr' => 'Carga útil de la superposición (JSON) usada por el visor (por ejemplo, x/y).',
            ],

            'fields' => [
                'user_id'     => 'Usuario',
                'mode'        => 'Modo',
                'text'        => 'Texto',
                'overlay'     => 'Superposición',
                'bg_preset'   => 'Preajuste de fondo',
                'is_public'   => 'Pública',
                'is_highlight'=> 'Destacada',
                'expires_at'  => 'Expira el',
                'sound_id'    => 'Sonido',
                'views'       => 'Vistas',
                'link_url'    => 'URL del enlace',
                'link_text'   => 'Etiqueta del enlace',
            ],

            'mode_labels' => [
                'media' => 'Foto / Video',
                'text'  => 'Texto',
            ],

            'help' => [
                'overlay'     => 'Se almacena como JSON (x/y).',
                'sound_id'    => 'Opcional: sonido asociado a esta historia.',
                'bg_preset'   => 'Solo se aplica a las historias de texto.',
                'link_url'    => 'Debe comenzar con http:// o https://',
                'link_text'   => 'Se muestra como la etiqueta del CTA en el visor.',
            ],

            'actions' => [
                'view_in_app' => 'Ver en la aplicación',
            ],
        ],

        'reel' => [
            'label'  => 'Reel',
            'plural' => 'Reels',

            'sections' => [
                'details'       => 'Detalles del reel',
                'details_descr' => 'Campos principales del reel y propiedad.',
                'settings'      => 'Configuración del reel',
                'settings_descr'=> 'Opciones de visibilidad y visualización.',
                'overlay'       => 'Superposición',
                'overlay_descr' => 'Carga útil de la superposición (JSON) usada por el visor.',
            ],

            'fields' => [
                'user_id'   => 'Usuario',
                'caption'   => 'Descripción',
                'overlay'   => 'Superposición',
                'is_public' => 'Público',
                'sound_id'  => 'Sonido',
                'views'     => 'Vistas',
                'comments'  => 'Comentarios',
                'reactions' => 'Reacciones',
                'bookmarks' => 'Marcadores',
            ],

            'help' => [
                'overlay'  => 'Se almacena como JSON.',
                'sound_id' => 'Opcional: sonido asociado a este reel.',
            ],

            'actions' => [
                'view_in_app' => 'Ver en la aplicación',
            ],
        ],

        'reel_comment' => [
            'label'  => 'Comentario de reel',
            'plural' => 'Comentarios de reel',

            'fields' => [
                'id'        => 'ID',
                'user_id'   => 'Usuario',
                'parent_id' => 'Comentario principal',
                'message'   => 'Mensaje',
                'reactions' => 'Reacciones',
            ],
        ],

        'sound' => [
            'label'  => 'Sonido',
            'plural' => 'Sonidos',

            'sections' => [
                'details'        => 'Detalles del sonido',
                'details_descr'  => 'Información básica sobre el sonido.',
                'settings'       => 'Configuración',
                'settings_descr' => 'Controles de visibilidad y disponibilidad del sonido.',
                'media'          => 'Multimedia',
                'media_descr'    => 'Archivo de audio e imagen de portada asociados a este sonido.',
            ],

            'fields' => [
                'title'       => 'Título',
                'artist'      => 'Artista',
                'description' => 'Descripción',
                'is_active'   => 'Activo',
                'cover'       => 'Portada',
                'audio'       => 'Archivo de audio',
                'length'      => 'Duración',
                'attachments' => 'Adjuntos'
            ],

            'help' => [
                'title'       => 'Nombre mostrado del sonido.',
                'artist'      => 'Artista o autor del sonido.',
                'description' => 'Descripción opcional para uso administrativo.',
                'is_active'   => 'Solo los sonidos activos pueden seleccionarse en las historias.',
                'cover'       => 'Imagen de portada mostrada en el selector de sonidos.',
                'audio'       => 'Archivo de audio principal asociado a este sonido.',
            ],

            'actions' => [
                'view_attachments' => 'Ver adjuntos',
            ],
        ],

    ],

    'settings_forms' => [
        'security' => [
            'email_domains' => [
                'tab' => 'Dominios de correo',
                'fields' => [
                    'domain_policy' => 'Política de dominios',
                    'allowedlist_domains' => 'Dominios en lista de permitidos',
                    'blocklist_domains' => 'Dominios en lista de bloqueados',
                ],
                'options' => [
                    'allow_all' => 'Permitir todos los dominios',
                    'allowlist_only' => 'Permitir solo los dominios en la lista de permitidos',
                    'blocklist_only' => 'Bloquear solo los dominios en la lista de bloqueados',
                ],
                'helpers' => [
                    'domain_policy' => 'Controla qué dominios de correo pueden usarse durante el registro.',
                    'allowedlist_domains' => 'Se usa cuando la política es “Permitir solo los dominios en la lista de permitidos”. Ingresa dominios como: example.com (sin esquema).',
                    'blocklist_domains' => 'Se usa cuando la política es “Bloquear solo los dominios en la lista de bloqueados”. Ingresa dominios como: bad.com (sin esquema).',
                ],
                'placeholders' => [
                    'domains' => 'Agrega un dominio y presiona Enter',
                ],
            ],
            'rate_limits' => [
                'tab' => 'Límites de frecuencia',
                'fields' => [
                    'enable_feature_rate_limits' => 'Activar límites de frecuencia por endpoint',
                    'enabled' => 'Activado',
                    'max_attempts' => 'Intentos máximos',
                    'window' => 'Ventana',
                ],
                'helpers' => [
                    'enable_feature_rate_limits' => 'Agrega límites anti-abuso configurables por el administrador a las acciones de escritura y generación seleccionadas.',
                    'enabled' => 'Activa o desactiva este límite específico de la función.',
                    'max_attempts' => 'Cuántas solicitudes se permiten dentro de la ventana de tiempo.',
                    'window' => 'Cuánto tiempo mantener el conteo de intentos antes de que el límite se reinicie, en segundos.',
                ],
                'features' => [
                    'posts_save' => [
                        'title' => 'Guardado de publicaciones',
                        'description' => 'Se aplica cuando los usuarios crean o actualizan publicaciones.',
                    ],
                    'posts_comments_add' => [
                        'title' => 'Agregar comentarios a publicaciones',
                        'description' => 'Se aplica cuando los usuarios agregan comentarios a las publicaciones.',
                    ],
                    'stories_store' => [
                        'title' => 'Guardado de historias',
                        'description' => 'Se aplica cuando los usuarios publican una historia.',
                    ],
                    'reels_store' => [
                        'title' => 'Guardado de reels',
                        'description' => 'Se aplica cuando los usuarios publican un reel.',
                    ],
                    'reels_comments_add' => [
                        'title' => 'Agregar comentarios a reels',
                        'description' => 'Se aplica cuando los usuarios agregan comentarios a los reels.',
                    ],
                    'streams_init' => [
                        'title' => 'Inicio de transmisiones',
                        'description' => 'Se aplica cuando los creadores inician una nueva transmisión.',
                    ],
                    'stream_comments_add' => [
                        'title' => 'Agregar comentarios a transmisiones',
                        'description' => 'Se aplica cuando los espectadores envían mensajes en el chat de la transmisión.',
                    ],
                    'suggestions_generate' => [
                        'title' => 'Generar sugerencias de IA',
                        'description' => 'Se aplica cuando los usuarios generan sugerencias impulsadas por IA.',
                    ],
                    'profile_asset_generate' => [
                        'title' => 'Recursos de IA del perfil',
                        'description' => 'Se aplica cuando los usuarios generan imágenes de avatar o portada con IA.',
                    ],
                    'messenger_send' => [
                        'title' => 'Envío de mensajes',
                        'description' => 'Se aplica cuando los usuarios envían mensajes directos.',
                    ],
                ],
            ],
        ],
        'payments' => [
            'withdrawals' => [
                'fields' => [
                    'manual_payout_methods' => 'Métodos de pago manuales',
                    'custom_withdrawal_message' => 'Mensaje de retiro personalizado',
                ],
                'helpers' => [
                    'manual_payout_methods' => 'Elige qué métodos de pago manuales pueden solicitar los usuarios. Las etiquetas provienen de tus archivos de traducción.',
                    'custom_withdrawal_message' => 'Muestra información adicional junto al formulario de retiro para los métodos de pago manuales.',
                ],
            ],
        ],
    ],

    'settings' => [
        'general' => 'General',
        'users' => 'Usuarios',
        'feed' => 'Feed',
        'media' => 'Multimedia',
        'storage' => 'Almacenamiento',
        'runtime' => 'Tiempo de ejecución',
        'payments' => 'Pagos',
        'websockets' => 'Websockets',
        'emails' => 'Correos',
        'streams' => 'Transmisiones',
        'stories' => 'Historias',
        'reels' => 'Reels',
        'compliance' => 'Cumplimiento',
        'security' => 'Seguridad',
        'referrals' => 'Referidos',
        'ai' => 'IA',
        'admin' => 'Administración',
        'theme' => 'Tema',
        'license' => 'Licencia',
    ],

];
