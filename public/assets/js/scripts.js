M.AutoInit();

//Parallax
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.parallax');
    var instances = M.Parallax.init(elems);
});

//Sidenav
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.sidenav');
    var instances = M.Sidenav.init(elems);
});

//Carousel
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.carousel');
    var options = {
        'dist' : 0,
        'numVisible' : 3,
        'padding' : 20,
        'indicators' : true,
        //'fullWidth' : true
    };

    var instances = M.Carousel.init(elems, options);
});

// stripe
// Set your publishable key: remember to change this to your live publishable key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
var stripe = Stripe('pk_test_51H2XZoC9AgcSjzXz85bhrgVbopaAz1jFsXrHFEDZNR49Vzjik9XoCVv99eDCmwAwyKvSwxfxmn4AfGkrtPNQ7p0m00aVi28mt9');
var elements = stripe.elements();



// Create an instance of the card Element.
var card = elements.create('card');


// Add an instance of the card Element into the `card-element` <div>.
card.mount('#card-element');

