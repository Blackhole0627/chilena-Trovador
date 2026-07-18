<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Active gateways for Trovador
        'payment/paypalStatusUpdate',
        'payment/mercadoPaymentStatusUpdate',
        'transcoding/coconut/update',
        // Unused gateways — left commented in case we enable them later
        // 'payment/stripeStatusUpdate',
        // 'payment/yookassaStatusUpdate',
        // 'payment/mollieStatusUpdate',
        // 'payment/flutterwaveStatusUpdate',
        // 'payment/coingateStatusUpdate',
        // 'payment/xenditStatusUpdate',
        // 'payment/paddleStatusUpdate',
        // 'payment/cryptocomStatusUpdate',
        // 'payment/nowPaymentsStatusUpdate',
        // 'payment/paystackPaymentStatusUpdate',
        // 'payment/razorPayPaymentStatusUpdate',
        // 'payment/stripeConnectStatusUpdate',
        // CCBill and Verotel removed entirely (adult processors)
    ];
}
