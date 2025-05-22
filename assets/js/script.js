(function () {

    const addToCartButtons = document.querySelectorAll('.add-to-cart');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const productId = this.getAttribute('data-productid');
            const quantity = this.getAttribute('data-quantity') || 1;

            const formData = new FormData();
            formData.append('productId', productId);
            formData.append('quantity', quantity);

            fetch('cart-process.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Redirect to cart page without alert
                    window.location.href = 'cart.php';
                } else if (data.status === 'auth_required') {
                    // Redirect to login page without alert
                    window.location.href = 'login.php';
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
})();