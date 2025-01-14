document.addEventListener("DOMContentLoaded", () => {
    // Dodanie event listenera do wszystkich pól ilości w koszyku
    document.querySelectorAll(".amount").forEach(inputElement => {
        inputElement.addEventListener("input", () => updateCart(inputElement));
    });

    // Dodanie event listenera do przycisku przejścia do płatności
    document.getElementById("checkout").addEventListener("click", proceedToPayment);
});

function updateCart(inputElement) {
    const row = inputElement.closest("tr");
    const unitPrice = parseFloat(row.querySelector(".unit-price").textContent.replace(",", ".")); // Konwersja ceny na float
    const amount = parseInt(inputElement.value, 10);
    const totalPriceCell = row.querySelector(".total-price");

    if (isNaN(amount) || amount <= 0) {
        alert("Podaj prawidłową ilość (minimum 1).");
        inputElement.value = 1; // Przywróć minimalną wartość
        return;
    }

    const totalPrice = unitPrice * amount;
    totalPriceCell.textContent = totalPrice.toFixed(2).replace(".", ",");

    calculateTotal();
}

function calculateTotal() {
    let total = 0;

    document.querySelectorAll(".cart-item").forEach(row => {
        const totalPrice = parseFloat(row.querySelector(".total-price").textContent.replace(",", "."));
        if (!isNaN(totalPrice)) {
            total += totalPrice;
        }
    });

    document.getElementById("total").textContent = total.toFixed(2).replace(".", ",");
}

function toggleCartVisibility() {
    const cartContainer = document.getElementById("cartContainer");
    if (cartContainer.style.display === "none" || cartContainer.style.display === "") {
        cartContainer.style.display = "block";
    } else {
        cartContainer.style.display = "none";
    }
}

function proceedToPayment() {
    if (confirm('Czy na pewno chcesz przejść do płatności i usunąć wszystkie produkty z koszyka?')) {
        fetch('koszyk.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'action=clear_cart'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Koszyk został opróżniony. Przechodzisz do płatności.');
                window.location.href = 'strona_glowna.php';
            } else {
                alert('Wystąpił błąd podczas opróżniania koszyka.');
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function highlightItem(element) {
    element.style.backgroundColor = '#FFD700';
    setTimeout(function () {
        element.style.backgroundColor = '';
    }, 500);
}

$(document).ready(function () {
    $('.button').hover(
        function () { $(this).css('transform', 'scale(1.1)'); },
        function () { $(this).css('transform', 'scale(1)'); }
    );
});

document.addEventListener("DOMContentLoaded", () => {
            // Dodanie event listenera do wszystkich pól ilości w koszyku
            document.querySelectorAll(".amount").forEach(inputElement => {
                inputElement.addEventListener("input", () => updateCart(inputElement));
            });

            // Dodanie event listenera do przycisku przejścia do płatności
            document.getElementById("checkout").addEventListener("click", proceedToPayment);
        });

        function updateCart(inputElement) {
            const row = inputElement.closest("tr");
            const unitPrice = parseFloat(row.querySelector(".unit-price").textContent.replace(",", ".")); // Konwersja ceny na float
            const amount = parseInt(inputElement.value, 10);
            const totalPriceCell = row.querySelector(".total-price");

            if (isNaN(amount) || amount <= 0) {
                alert("Podaj prawidłową ilość (minimum 1).");
                inputElement.value = 1; // Przywróć minimalną wartość
                return;
            }

            const totalPrice = unitPrice * amount;
            totalPriceCell.textContent = totalPrice.toFixed(2).replace(".", ",");

            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;

            document.querySelectorAll(".cart-item").forEach(row => {
                const totalPrice = parseFloat(row.querySelector(".total-price").textContent.replace(",", "."));
                if (!isNaN(totalPrice)) {
                    total += totalPrice;
                }
            });

            document.getElementById("total").textContent = total.toFixed(2).replace(".", ",");
        }

        function toggleCartVisibility() {
            const cartContainer = document.getElementById("cartContainer");
            if (cartContainer.style.display === "none" || cartContainer.style.display === "") {
                cartContainer.style.display = "block";
            } else {
                cartContainer.style.display = "none";
            }
        }

        function proceedToPayment() {
            alert('Przechodzisz do płatności');
            window.location.href = 'strona_glowna.php';
        }

        function highlightItem(element) {
            element.style.backgroundColor = '#FFD700';
            setTimeout(function () {
                element.style.backgroundColor = '';
            }, 500);
        }

        $(document).ready(function () {
            $('.button').hover(
                function () { $(this).css('transform', 'scale(1.1)'); },
                function () { $(this).css('transform', 'scale(1)'); }
            );
        });