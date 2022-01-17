/* coffer.js */
jQuery(document).ready(function($) {

    // Client secret.
    var clientSecret = '';
    var intentId     = '';

    // Load Stripe elements.
    var stripe = Stripe(coffer.stripe);
    var elements = stripe.elements();
    var style = {
        base: {
            color: '#32325d',
        }
    };
    
    // Create card.
    var card = elements.create( 'card', { style: style });

    // Create payment intent.
    $('.coffer-option').on('click', function() {
        // Clear active.
        $('.coffer-option').removeClass('active');
        // Check.
        if($(this).data('amount')) {
            // Get data.
            var amount = $(this).data('amount');
            // Highlight.
            $(this).addClass('active');
            // Check if open.
            if($('div#coffer-option-other').hasClass('open')) {
                // Close.
                $('div#coffer-option-other').animate({
                    height: '0px',
                }, 300);
            }
            // Update button.
            $('.coffer-panel button#submit').text('Donate $' + amount);
            // Check for existing.
            if($('.coffer-box').data('intent_id')) {
                // Update payment intent.
                updatePaymentIntent(amount, $('.coffer-box').data('intent_id'));
            } else {
                // Create payment intent.
                createPaymentIntent(amount);
            }
        } else {
            // Check for existing.
            if($('.coffer-box').data('intent_id')) {
                // Update button.
                $('div#coffer-option-other button.coffer-btn').text('Update');
            }
            // Open.
            $(this).addClass('active');
            $('div#coffer-option-other').addClass('open');
            $('div#coffer-option-other').animate({
                height: '30px',
            }, 300);
        }
    });

    // Create payment intent with custom amount.
    $('div#coffer-option-other button.coffer-btn').on('click', function() {
        // Get amount.
        var amount = $('#coffer-option-custom').val();
        // Update button.
        $('.coffer-panel button#submit').text('Donate $' + amount);
        // Check for existing.
        if($('.coffer-box').data('intent_id')) {
            // Update payment intent.
            updatePaymentIntent(amount, $('.coffer-box').data('intent_id'));
        } else {
            // Create payment intent.
            createPaymentIntent(amount);
        }
    });

    // Create payment intent.
    function createPaymentIntent(amount) {
        // Send.
        $.ajax({
            type: 'POST',
            url: coffer.ajax_url,
            data: {
                action: 'create_payment_intent',
                nonce: coffer.nonce,
                amount: amount
            },
            success: function( response ) {
                var response = $.parseJSON(response);
                clientSecret = response['client_secret'];
                intentId     = response['intent_id'];
                // Set.
                $('.coffer-box').data('intent_id', intentId);
                loadForm();
                // Animate.
                $('div#coffer-panel-2').animate({
                    height: '140px',
                }, 300);
            }
        });
    }

    // Update payment intent.
    function updatePaymentIntent(amount, id) {
        // Send.
        $.ajax({
            type: 'POST',
            url: coffer.ajax_url,
            data: {
                action: 'update_payment_intent',
                nonce: coffer.nonce,
                amount: amount,
                id: id
            },
            success: function( response ) {
                // Updated.
            }
        });
    }

    // Load form.
    function loadForm() {
        // Mount.
        card.mount('#card-element');
    }

    // Set form.
    var form = document.getElementById('payment-form');

    // On submit.
    form.addEventListener('submit', function(ev) {
        ev.preventDefault();
        $('#payment-form').animate({
            height: '0px',
            margin: '0px',
        }, 300);
        $('.coffer-footer').animate({
            height: '0px',
        }, 300);
        stripe.confirmCardPayment(clientSecret, {
            payment_method: {
                card: card,
                billing_details: {
                    name: 'Jenny Rosen'
                }
            }
        }).then(function(result) {
            if (result.error) {
                // Show error to your customer (for example, insufficient funds)
                console.log(result.error.message);
            } else {
                // The payment has been processed!
                if (result.paymentIntent.status === 'succeeded') {
                    // Animate.
                    $('.coffer-loading').animate({
                        height: '0px',
                        margin: '0px',
                    }, 300);
                }
            }
        });
    });

});