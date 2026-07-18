# T2 — Remove CCBill & Verotel

## Done now (safe, functional removal — CCBill/Verotel are no longer usable)

- **Routes removed** (`routes/web.php`): `/ccbill/status`, `/verotel/status`,
  `payment/ccBillPaymentStatusUpdate`, `payment/verotelPaymentStatusUpdate`.
- **CSRF exceptions removed** (`VerifyCsrfToken`): both webhook paths.
- **Frontend exposure removed** (`JavascriptVariables`): ccBill/verotel recurring flags.
- **Checkout options**: gated by `ccbillCredentialsProvided()` / verotel settings —
  with credentials absent (T9 moves everything to MercadoPago), these never render.

## Remaining (finish during the boot test — needs the app running to verify)

These are either admin-only cosmetic or interwoven dead code; removing them blind
risks breaking the Filament panel or the payment helper, so verify each after boot:

1. **Filament admin tabs** in `app/Filament/Pages/Settings/ManagePaymentsSettings.php`
   — remove the `Tab::make('CCBill')` and `Tab::make('Verotel')` blocks (approx
   lines 325–395) and the two `view('filament.partials.webhooks.*')` includes.
2. **Webhook partials**: delete `resources/views/filament/partials/webhooks/ccbill.blade.php`
   and `verotel.blade.php` **after** the tabs above are removed.
3. **Checkout logos/markup**: remove the `ccbill-payment-method` / `verotel-payment-method`
   blocks in `resources/views/elements/checkout/checkout-box.blade.php`.
4. **PaymentHelper dead code**: `generateCCBill*`, `getCCBill*`, verotel helpers, and the
   `Transaction::CCBILL_PROVIDER` / `VEROTEL_PROVIDER` branches. Keep the model
   **constants** (`Transaction::CCBILL_PROVIDER` etc.) until all references are gone,
   or historical transactions with those providers will fatal on display.
5. **Filament resources**: CCBILL/VEROTEL provider labels/filters in the Subscription
   and Transaction resources — replace with MercadoPago-only options.
6. **Settings keys**: `payments.ccbill_*` / `payments.verotel_*` can be dropped via a
   settings migration once nothing reads them.

## Order of operations at boot test

migrate → load admin panel → remove Filament tabs → confirm panel loads →
remove checkout markup → confirm checkout loads → strip PaymentHelper dead code →
run a MercadoPago test payment end-to-end.
