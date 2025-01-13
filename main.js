// Funkcja przełączania widoczności koszyka
function toggleCartVisibility() {
    const cart = document.getElementById('cartContainer');
    cart.style.display = cart.style.display === 'none' || cart.style.display === '' ? 'block' : 'none';
    calculateTotal();
}

// Funkcja aktualizująca ilość produktu w koszyku (AJAX)
function updateCart(inputElement) {
    const row = inputElement.closest("tr");
    const cartId = row.getAttribute("data-cart-id");
    const newQuantity = parseInt(inputElement.value, 10);

    if (newQuantity <= 0) {
        alert("Ilość musi być większa od zera!");
        return;
    }

    $.ajax({
        url: "update_cart.php",
        method: "POST",
        data: { cart_id: cartId, quantity: newQuantity },
        success: function () {
            const unitPrice = parseFloat(row.getAttribute("data-price"));
            row.querySelector(".total-price").textContent = (unitPrice * newQuantity).toFixed(2);
            calculateTotal();
        },
        error: function () {
            alert("Błąd podczas aktualizacji koszyka.");
        }
    });
}

// Funkcja usuwania produktu z koszyka (AJAX)
function removeFromCart(cartId) {
    $.ajax({
        url: "remove_from_cart.php",
        method: "POST",
        data: { cart_id: cartId },
        success: function () {
            $(`tr[data-cart-id="${cartId}"]`).fadeOut(300, function () {
                $(this).remove();
                calculateTotal();
            });
        },
        error: function () {
            alert("Błąd podczas usuwania produktu z koszyka.");
        }
    });
}

// Funkcja obliczająca całkowity koszt koszyka
function calculateTotal() {
    let total = 0;
    document.querySelectorAll(".cart-item").forEach(item => {
        const totalPrice = parseFloat(item.querySelector(".total-price").textContent);
        total += totalPrice;
    });
    document.getElementById("total").textContent = total.toFixed(2);
}

// Funkcja dodawania do koszyka (AJAX)
function addToCart(productId, quantity) {
    $.ajax({
        url: "add_to_cart.php", // Ścieżka do skryptu
        method: "POST",
        data: { product_id: productId, quantity: quantity }, // Dane wysyłane w żądaniu
        success: function (response) {
            alert(response); // Wyświetla wiadomość zwróconą przez serwer
        },
        error: function (xhr, status, error) {
            alert("Błąd: " + xhr.responseText); // Wyświetla komunikat błędu
        }
    });
}


