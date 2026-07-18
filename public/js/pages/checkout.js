/**
 * Component used for handling checkout dialog actions
 */
"use strict";
/* global app, trans, trans_choice, launchToast, getWebsiteFormattedAmount, getTaxDescription */

$(function () {
    // Deposit amount change event listener
    $('#checkout-amount').on('change', function () {
        $(".credit-payment-provider").css("pointer-events", "auto");
        if (!checkout.checkoutAmountValidation()) {
            return false;
        }

        // update payment amount
        checkout.paymentData.amount = parseFloat($('#checkout-amount').val());
        // fetch taxes from BE and update UI
        checkout.updatePaymentSummaryData();
    });

    // Checkout proceed button event listener
    $('.checkout-continue-btn').on('click', function () {
        checkout.initPayment();
    });

    $('.custom-control').on('change', function () {
        $('.error-message').hide();
    });

    $('#headingOne').on('click', function () {
        if ($('#headingOne').hasClass('collapsed')) {
            $('.card-header .label-icon').html('<ion-icon name="chevron-up-outline"></ion-icon>');
        } else {
            $('.card-header .label-icon').html('<ion-icon name="chevron-down-outline"></ion-icon>');
        }
    });

    $('#checkout-center').on('show.bs.modal', function (e) {
        var postId = $(e.relatedTarget).data('post-id');
        var recipientId = $(e.relatedTarget).data('recipient-id');
        var amount = $(e.relatedTarget).data('amount');
        var type = $(e.relatedTarget).data('type');
        var username = $(e.relatedTarget).data('username');
        var firstName = $(e.relatedTarget).data('first-name');
        var lastName = $(e.relatedTarget).data('last-name');
        var billingAddress = $(e.relatedTarget).data('billing-address');
        var name = $(e.relatedTarget).data('name');
        var avatar = $(e.relatedTarget).data('avatar');
        var country = $(e.relatedTarget).data('country');
        var city = $(e.relatedTarget).data('city');
        var state = $(e.relatedTarget).data('state');
        var postcode = $(e.relatedTarget).data('postcode');
        var availableCredit = $(e.relatedTarget).data('available-credit');
        var streamId = $(e.relatedTarget).data('stream-id');
        var userMessageId = $(e.relatedTarget).data('message-id');

        checkout.initiatePaymentData(
            type,
            amount,
            postId,
            recipientId,
            firstName,
            lastName,
            billingAddress,
            country,
            city,
            state,
            postcode,
            availableCredit,
            streamId,
            userMessageId
        );

        checkout.updateUserDetails(avatar, username, name);

        // Populate select, preselect if checkout.paymentData.country is set,
        // and then auto-fetch BE quote from fillCountrySelectOptions success callback.
        checkout.fillCountrySelectOptions();

        // Prefill billing inputs (including address fields)
        checkout.prefillBillingDetails();

        let paymentTitle = '';
        let paymentDescription = '';

        if (type === 'tip' || type === 'chat-tip') {
            $('.checkout-amount-input').removeClass('d-none');
            paymentTitle = trans("Send a tip");
            paymentDescription = trans("Send a tip to this user");
            checkout.togglePaymentProviders(true, checkout.oneTimePaymentProcessorClasses);
        } else if (
            type === 'one-month-subscription' ||
            type === 'three-months-subscription' ||
            type === 'six-months-subscription' ||
            type === 'yearly-subscription'
        ) {
            let numberOfMonths = 1;
            let showStripeProvider = !app.stripeRecurringDisabled;
            let showPaypalProvider = !app.paypalRecurringDisabled;
            let showCCBillProvider = !app.ccBillRecurringDisabled;
            let showCreditProvider = !app.localWalletRecurringDisabled;
            let verotelProvider = !app.verotelRecurringDisabled;

            // handles ccbill provider as they only allow 30 or 90 days subscriptions
            if (showCCBillProvider) {
                if (type === 'three-months-subscription') {
                    numberOfMonths = 3;
                } else if (type === 'six-months-subscription') {
                    numberOfMonths = 6;
                    showCCBillProvider = false;
                } else if (type === 'yearly-subscription') {
                    numberOfMonths = 12;
                    showCCBillProvider = false;
                }
            }

            checkout.togglePaymentProviders(false, checkout.oneTimePaymentProcessorClasses);
            checkout.togglePaymentProvider(showCCBillProvider, '.ccbill-payment-method');
            checkout.togglePaymentProvider(showStripeProvider, '.stripe-payment-method');
            checkout.togglePaymentProvider(showPaypalProvider, '.paypal-payment-method');
            checkout.togglePaymentProvider(showCreditProvider, '.credit-payment-method');
            checkout.togglePaymentProvider(verotelProvider, '.verotel-payment-method');

            $('.checkout-amount-input').addClass('d-none');
            paymentTitle = trans(type);
            let subscriptionInterval = trans_choice('months', numberOfMonths, {'number': numberOfMonths});
            let key = app.currencyPosition === 'left' ? 'Subscribe to' : 'Subscribe to rightAligned';
            paymentDescription = trans(key, {
                'amount': amount,
                'currency': app.currencySymbol,
                'username': name,
                'subscription_interval': subscriptionInterval
            });
            checkout.toggleCryptoPaymentProviders(false);

        } else if (type === 'post-unlock') {
            $('.checkout-amount-input').addClass('d-none');
            paymentTitle = trans('Unlock post');
            paymentDescription = trans('Unlock post for') + ' ' + getWebsiteFormattedAmount(amount);
            checkout.togglePaymentProviders(true, checkout.oneTimePaymentProcessorClasses);

        } else if (type === 'stream-access') {
            $('.checkout-amount-input').addClass('d-none');
            paymentTitle = trans('Join streaming');
            paymentDescription = trans('Join streaming now for') + ' ' + getWebsiteFormattedAmount(amount);
            checkout.togglePaymentProviders(true, checkout.oneTimePaymentProcessorClasses);

        } else if (type === 'message-unlock') {
            $('.checkout-amount-input').addClass('d-none');
            paymentTitle = trans('Unlock message');
            paymentDescription = trans('Unlock message for') + ' ' + getWebsiteFormattedAmount(amount);
            checkout.togglePaymentProviders(true, checkout.oneTimePaymentProcessorClasses);
        }

        if (paymentTitle !== '' || paymentDescription !== '') {
            $('#payment-title').text(paymentTitle);
            $('.payment-body .payment-description').removeClass('d-none');
            $('.payment-body .payment-description').text(paymentDescription);
        }

        if (!firstName || !lastName || !billingAddress || !city || !state || !postcode || !country) {
            $('#billingInformation').collapse('show');
        } else {
            $('#billingInformation').collapse('hide');
        }

        $('#checkout-amount').val(amount);
    });

    $('#checkout-center').on('hidden.bs.modal', function () {
        $(this).find('#billing-agreement-form').trigger('reset');
        $('.payment-error').addClass('d-none');
    });

    // Radio button
    $('.radio-group .radio').on('click', function () {
        $(this).parent().parent().find('.radio').removeClass('selected');
        $(this).addClass('selected');
        $('.payment-error').addClass('d-none');
    });

    // Country change triggers BE-driven quote
    $('.country-select').on('change', function () {
        // keep paymentData.country in sync with selected name
        checkout.validateCountryField();
        checkout.updatePaymentSummaryData();
    });
});

/**
 * Checkout class
 */
var checkout = {
    allowedPaymentProcessors: ['stripe', 'paypal', 'credit', 'nowpayments', 'ccbill', 'paystack', 'yookassa', 'mollie', 'flutterwave', 'coingate', 'xendit', 'paddle', 'cryptocom', 'oxxo', 'mercado', 'verotel', 'razorpay'],
    paymentData: {},

    // keep a reference to last quote request (abort on fast changes)
    _taxQuoteXhr: null,

    oneTimePaymentProcessorClasses: [
        '.nowpayments-payment-method',
        '.ccbill-payment-method',
        '.stripe-payment-method',
        '.paypal-payment-method',
        '.paystack-payment-method',
        '.yookassa-payment-method',
        '.mollie-payment-method',
        '.flutterwave-payment-method',
        '.coingate-payment-method',
        '.xendit-payment-method',
        '.paddle-payment-method',
        '.cryptocom-payment-method',
        '.oxxo-payment-method',
        '.mercado-payment-method',
        '.credit-payment-method',
        '.verotel-payment-method',
        '.razorpay-payment-method',
    ],

    /**
     * Initiates the payment data payload
     */
    initiatePaymentData: function (type, amount, post, recipient, firstName, lastName, billingAddress, country, city, state, postcode, availableCredit, streamId, messageId) {
        checkout.paymentData = {
            type: type,
            amount: amount,
            post: post,
            recipient: recipient,
            firstName: firstName,
            lastName: lastName,
            billingAddress: billingAddress,
            country: country,
            city: city,
            state: state,
            postcode: postcode,
            availableCredit: availableCredit,
            stream: streamId,
            messageId: messageId,

            // defaults
            taxes: { data: [], subtotal: "0.00", total: "0.00", taxesTotalAmount: "0.00" },
            totalAmount: typeof amount !== 'undefined' ? parseFloat(amount).toFixed(2) : "0.00",
        };
    },

    /**
     * Updates the payment form
     */
    updatePaymentForm: function () {
        $('#payment-type').val(checkout.paymentData.type);
        $('#post').val(checkout.paymentData.post);
        $('#recipient').val(checkout.paymentData.recipient);
        $('#provider').val(checkout.paymentData.provider);

        $('#paymentFirstName').val(checkout.paymentData.firstName);
        $('#paymentLastName').val(checkout.paymentData.lastName);
        $('#paymentBillingAddress').val(checkout.paymentData.billingAddress);
        $('#paymentCountry').val(checkout.paymentData.country); // name
        $('#paymentState').val(checkout.paymentData.state);
        $('#paymentPostcode').val(checkout.paymentData.postcode);
        $('#paymentCity').val(checkout.paymentData.city);

        // total amount comes from BE quote
        $('#payment-deposit-amount').val(checkout.paymentData.totalAmount);

        // this is BE quote not FE calculated
        $('#paymentTaxes').val(JSON.stringify(checkout.paymentData.taxes));

        $('#stream').val(checkout.paymentData.stream);
        $('#userMessage').val(checkout.paymentData.messageId);
    },

    stripe: null,

    /**
     * Instantiates the payment session
     */
    initPayment: function () {
        if (!checkout.checkoutAmountValidation()) {
            return false;
        }

        let processor = checkout.getSelectedPaymentMethod();
        if (!processor) {
            $('.payment-error').removeClass('d-none');
        }

        if (processor) {
            $('.paymentProcessorError').hide();
            $('.error-message').hide();
            if (checkout.allowedPaymentProcessors.includes(processor)) {
                checkout.updatePaymentForm();
                $('.checkout-continue-btn .spinner-border').removeClass('d-none');
                checkout.validateAllFields(() => { $('.payment-button').trigger('click'); });
            }
        }
    },

    /**
     * Runs backend validation check for billing data
     * @param callback
     */
    validateAllFields: function (callback) {
        checkout.clearFormErrors();
        $.ajax({
            type: 'POST',
            data: $('#pp-buyItem').serialize(),
            url: app.baseUrl + '/payment/initiate/validate',
            success: function () {
                callback();
            },
            error: function (result) {
                $('.checkout-continue-btn .spinner-border').addClass('d-none');
                if (result.status === 500) {
                    launchToast('danger', trans('Error'), result.responseJSON.message);
                }
                $.each(result.responseJSON.errors, function (field, error) {
                    let fieldElement = $('.uifield-' + field);
                    fieldElement.addClass('is-invalid');
                    fieldElement.parent().append(
                        `
                            <span class="invalid-feedback" role="alert">
                                <strong>${error}</strong>
                            </span>
                        `
                    );
                });
            }
        });
    },

    /**
     * Clears up dialog (all) form errors
     */
    clearFormErrors: function () {
        $('.invalid-feedback').remove();
        $('input').removeClass('is-invalid');
    },

    /**
     * Returns currently selected payment method
     */
    getSelectedPaymentMethod: function () {
        const paypalProvider = $('.paypal-payment-provider').hasClass('selected');
        const stripeProvider = $('.stripe-payment-provider').hasClass('selected');
        const creditProvider = $('.credit-payment-provider').hasClass('selected');
        const nowPaymentsProvider = $('.nowpayments-payment-provider').hasClass('selected');
        const ccbillProvider = $('.ccbill-payment-provider').hasClass('selected');
        const paystackProvider = $('.paystack-payment-provider').hasClass('selected');
        const yookassaProvider = $('.yookassa-payment-provider').hasClass('selected');
        const mollieProvider = $('.mollie-payment-provider').hasClass('selected');
        const flutterwaveProvider = $('.flutterwave-payment-provider').hasClass('selected');
        const coingateProvider = $('.coingate-payment-provider').hasClass('selected');
        const xenditProvider = $('.xendit-payment-provider').hasClass('selected');
        const paddleProvider = $('.paddle-payment-provider').hasClass('selected');
        const cryptocomProvider = $('.cryptocom-payment-provider').hasClass('selected');
        const oxxoProvider = $('.oxxo-payment-provider').hasClass('selected');
        const mercadoProvider = $('.mercado-payment-provider').hasClass('selected');
        const verotelProvider = $('.verotel-payment-provider').hasClass('selected');
        const razorpayProvider = $('.razorpay-payment-provider').hasClass('selected');

        let val = null;
        if (paypalProvider) val = 'paypal';
        else if (stripeProvider) val = 'stripe';
        else if (creditProvider) val = 'credit';
        else if (nowPaymentsProvider) val = 'nowpayments';
        else if (ccbillProvider) val = 'ccbill';
        else if (paystackProvider) val = 'paystack';
        else if (yookassaProvider) val = 'yookassa';
        else if (mollieProvider) val = 'mollie';
        else if (flutterwaveProvider) val = 'flutterwave';
        else if (coingateProvider) val = 'coingate';
        else if (xenditProvider) val = 'xendit';
        else if (paddleProvider) val = 'paddle';
        else if (cryptocomProvider) val = 'cryptocom';
        else if (oxxoProvider) val = 'oxxo';
        else if (mercadoProvider) val = 'mercado';
        else if (verotelProvider) val = 'verotel';
        else if (razorpayProvider) val = 'razorpay';

        if (val) {
            checkout.paymentData.provider = val;
            return val;
        }
        return false;
    },

    /**
     * Validates the amount field
     * @returns {boolean}
     */
    checkoutAmountValidation: function () {
        const checkoutAmount = $('#checkout-amount').val();

        // Tips min-max validation
        if (checkout.paymentData.type === 'tip') {
            if ((checkoutAmount.length > 0 && checkoutAmount >= app.tipMinAmount && checkoutAmount <= app.tipMaxAmount)) {
                $('#checkout-amount').removeClass('is-invalid');
                $('#paypal-deposit-amount').val(checkoutAmount);

                // credit gating will be updated after BE quote too
                if (checkout.paymentData.availableCredit < checkoutAmount) {
                    $(".credit-payment-provider").css("pointer-events", "none");
                }
                return true;
            } else {
                $('#checkout-amount').addClass('is-invalid');
                return false;
            }
        }

        return true;
    },

    /**
     * Validates FN field
     */
    validateFirstNameField: function () {
        let firstNameField = $('input[name="firstName"]');
        checkout.paymentData.firstName = firstNameField.val();
    },

    /**
     * Validates LN field
     */
    validateLastNameField: function () {
        let lastNameField = $('input[name="lastName"]');
        checkout.paymentData.lastName = lastNameField.val();
    },

    /**
     * Validates Adress field
     */
    validateBillingAddressField: function () {
        let billingAddressField = $('textarea[name="billingAddress"]');
        checkout.paymentData.billingAddress = billingAddressField.val();
    },

    /**
     * Validates city field
     */
    validateCityField: function () {
        let cityField = $('input[name="billingCity"]');
        checkout.paymentData.city = cityField.val();
    },

    /**
     * Validates state field
     */
    validateStateField: function () {
        let stateField = $('input[name="billingState"]');
        checkout.paymentData.state = stateField.val();
    },

    /**
     * Validates the ZIP code
     */
    validatePostcodeField: function () {
        let postcodeField = $('input[name="billingPostcode"]');
        checkout.paymentData.postcode = postcodeField.val();
    },

    /**
     * Validates the country field
     */
    validateCountryField: function () {
        var $countryField = $('.country-select');
        var selected = $countryField.find('option:selected');
        var name = (selected && selected.length) ? (selected.text() || '').trim() : '';

        if (name) {
            $countryField.removeClass('is-invalid');
            checkout.paymentData.country = name;
        } else {
            checkout.paymentData.country = '';
        }
    },

    /**
     * Prefills user billing data, if available
     */
    prefillBillingDetails: function () {
        $('input[name="firstName"]').val(checkout.paymentData.firstName);
        $('input[name="lastName"]').val(checkout.paymentData.lastName);
        $('input[name="billingCity"]').val(checkout.paymentData.city);
        $('input[name="billingState"]').val(checkout.paymentData.state);
        $('input[name="billingPostcode"]').val(checkout.paymentData.postcode);
        $('textarea[name="billingAddress"]').val(checkout.paymentData.billingAddress);
    },

    /**
     * Updates user details
     */
    updateUserDetails: function (userAvatar, username, name) {
        $('.payment-body .user-avatar').attr('src', userAvatar);
        $('.payment-body .name').text(name);
        $('.payment-body .username').text('@' + username);
    },

    /**
     * Fetches list of countries (no need to attach taxes to options anymore)
     * Preselects by checkout.paymentData.country (name) if provided
     * Then triggers BE quote once options exist
     */
    fillCountrySelectOptions: function () {
        $.ajax({
            type: 'GET',
            url: app.baseUrl + '/countries',
            success: function (result) {
                if (result && result.countries && result.countries.length > 0) {
                    let $select = $('.country-select');

                    // destroy selectize before rebuilding options
                    if ($select[0] && $select[0].selectize) {
                        $select[0].selectize.destroy();
                    }

                    $select.empty();

                    $.each(result.countries, function (i, item) {
                        const isSelected = checkout.paymentData.country && checkout.paymentData.country === item.name;
                        $select.append($('<option>', {
                            value: item.id, // keep id as value
                            text: item.name,
                            selected: isSelected
                        }));
                    });

                    checkout.initCountrySelectize();

                    // sync paymentData.country + update quote
                    checkout.updatePaymentSummaryData();
                }
            }
        });
    },

    /**
     * BE-driven: fetch quote and render taxes/totals.
     * - Called on amount change, country change, and after countries load.
     */
    updatePaymentSummaryData: function () {
        const $countrySelect = $('.country-select');
        const countryName = $countrySelect.val()
            ? ($countrySelect.find('option:selected').text() || '').trim()
            : null;

        // Keep paymentData.country in sync (important for form submit)
        checkout.paymentData.country = countryName || '';

        // Base amount: tips/deposits use input; for other types BE overrides internally
        const baseAmount = parseFloat(checkout.paymentData.amount || 0);

        // If no country selected, clear taxes UI and show base totals only
        if (!countryName) {
            checkout.paymentData.taxes = { data: [], subtotal: baseAmount.toFixed(2), total: baseAmount.toFixed(2), taxesTotalAmount: "0.00" };
            checkout.paymentData.totalAmount = baseAmount.toFixed(2);

            $('.taxes-details').empty();
            $('.subtotal-amount b').html(getWebsiteFormattedAmount(baseAmount.toFixed(2)));
            $('.total-amount b').html(getWebsiteFormattedAmount(baseAmount.toFixed(2)));

            $('.available-credit').html('(' + getWebsiteFormattedAmount(checkout.paymentData.availableCredit) + ')');
            if (parseFloat(checkout.paymentData.availableCredit || 0) < baseAmount) {
                $(".credit-payment-provider").css("pointer-events", "none");
            } else {
                $(".credit-payment-provider").css("pointer-events", "auto");
            }
            return;
        }

        const payload = {
            transaction_type: checkout.paymentData.type,
            amount: baseAmount, // used only for tip/deposit; ignored otherwise
            recipient_user_id: checkout.paymentData.recipient,
            post_id: checkout.paymentData.post,
            user_message_id: checkout.paymentData.messageId,
            stream: checkout.paymentData.stream,
            country: countryName,
        };

        // Abort previous quote request (avoid race conditions)
        if (checkout._taxQuoteXhr && checkout._taxQuoteXhr.readyState !== 4) {
            checkout._taxQuoteXhr.abort();
        }

        checkout._taxQuoteXhr = $.ajax({
            type: 'POST',
            url: app.baseUrl + '/payment/taxes/quote',
            data: payload,
            success: function (res) {
                const quote = (res && res.quote) ? res.quote : null;
                if (!quote) return;

                checkout.paymentData.taxes = quote;
                checkout.paymentData.totalAmount = baseAmount;

                $('.taxes-details').empty();

                (quote.data || []).forEach(function (t) {
                    if (t.hidden) return;
                    const item =
                        `<div class="row ml-2">
                            <span class="col-sm left">${getTaxDescription(t.taxName, t.taxPercentage, t.taxType)}</span>
                            <span class="country-tax col-sm right text-right"><b>${getWebsiteFormattedAmount(t.taxAmount)}</b></span>
                         </div>`;
                    $('.taxes-details').append(item);
                });

                $('.subtotal-amount b').html(getWebsiteFormattedAmount(quote.subtotal));
                $('.total-without-tax-amount b').html(getWebsiteFormattedAmount(quote.netSubtotal));
                $('.total-amount b').html(getWebsiteFormattedAmount(quote.total));

                // Credit gating uses total
                $('.available-credit').html('(' + getWebsiteFormattedAmount(checkout.paymentData.availableCredit) + ')');
                if (parseFloat(checkout.paymentData.availableCredit || 0) < parseFloat(quote.total || 0)) {
                    $(".credit-payment-provider").css("pointer-events", "none");
                } else {
                    $(".credit-payment-provider").css("pointer-events", "auto");
                }
            }
        });
    },

    toggleCryptoPaymentProviders: function (toggle) {
        let nowPaymentsPaymentMethod = $('.nowpayments-payment-method');
        if (toggle) {
            if (nowPaymentsPaymentMethod.hasClass('d-none')) nowPaymentsPaymentMethod.removeClass('d-none');
        } else {
            if (!nowPaymentsPaymentMethod.hasClass('d-none')) nowPaymentsPaymentMethod.addClass('d-none');
        }
    },

    togglePaymentProvider: function (toggle, paymentMethodClass) {
        let paymentMethod = $(paymentMethodClass);
        if (toggle) {
            if (paymentMethod.hasClass('d-none')) paymentMethod.removeClass('d-none');
        } else {
            if (!paymentMethod.hasClass('d-none')) paymentMethod.addClass('d-none');
        }
    },

    togglePaymentProviders: function (toggle, paymentMethodClasses) {
        paymentMethodClasses.forEach(function (paymentMethodClass) {
            checkout.togglePaymentProvider(toggle, paymentMethodClass);
        });
    },

    initCountrySelectize: function () {
        let $select = $('.country-select');
        if (!$select.length) return;
        if (typeof $select.selectize !== 'function') return;

        // already initialized
        if ($select[0] && $select[0].selectize) return;

        $select.selectize({
            placeholder: trans("Select a country"),
            allowEmptyOption: true,
            searchField: ['text'],
            sortField: [{ field: 'text', direction: 'asc' }],
            closeAfterSelect: true,
            onChange: function () {
                // sync paymentData.country (uses selected text)
                checkout.validateCountryField();
                // fetch BE quote + update UI
                checkout.updatePaymentSummaryData();
            }
        });
    },

};
