// Funkcje JavaScript

// Funkcja walidacji formularza logowania
function handleLoginSubmit() {
    var username = document.getElementById('username').value.trim();
    var password = document.getElementById('password').value;

    if (username === "" || password === "") {
        alert("Wszystkie pola muszą być wypełnione!");
        return false;
    }
    alert("Logowanie zakończone sukcesem!");
    window.location.href = 'strona_glowna.html';
}


// Funkcja przełączania widoczności koszyka
function toggleCartVisibility() {
    var cart = document.getElementById('cartContainer');
    if (cart.style.display === 'none' || cart.style.display === '') {
        cart.style.display = 'block';
    } else {
        cart.style.display = 'none';
    }
    calculateTotal();
}

// Funkcja aktualizująca cenę dla pozycji w koszyku
function updateCart(inputElement) {
    const row = inputElement.closest("tr");
    const unitPrice = parseFloat(row.querySelector(".unit-price").textContent);
    const amount = parseInt(inputElement.value, 10);
    const totalPriceCell = row.querySelector(".total-price");

    // Oblicz nową cenę całkowitą dla danej pozycji
    const totalPrice = unitPrice * amount;
    totalPriceCell.textContent = totalPrice.toFixed(2);

    // Aktualizuj sumę całkowitą
    calculateTotal();
}

// Funkcja obliczająca całkowity koszt koszyka
function calculateTotal() {
    let total = 0;
    const items = document.querySelectorAll(".cart-item");
    items.forEach(item => {
        const totalPrice = parseFloat(item.querySelector(".total-price").textContent);
        total += totalPrice;
    });
    document.getElementById("total").textContent = total.toFixed(2);
}

// Funkcja podświetlania elementu w koszyku
function highlightItem(element) {
    element.style.backgroundColor = '#FFD700';
    setTimeout(function () {
        element.style.backgroundColor = '';
    }, 500);
}

//AJAX
function loadDoc() {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        document.getElementById("demo").innerHTML = this.responseText;
    }
    xhttp.open("GET", "ajax.txt", true);
    xhttp.send();
}

// Dodawanie do koszyka (alert)
function addToCart() {
    alert("Dodano do koszyka!");
}

// Funkcje jQuery
$(document).ready(function () {

    // Obsługa przycisku "Przejdź do płatności"
    $('#checkout').on('click', function () {
        alert('Przechodzisz dalej!');
        window.location.href = 'Strona_glowna.html';
    });

    // Efekt najechania na przyciski z klasą .button
    $('.button').hover(
        function () { $(this).css('transform', 'scale(1.1)'); },
        function () { $(this).css('transform', 'scale(1)'); }
    );

    // Obsługa przycisku "Usuń"
    $('.remove').on('click', function (event) {
        event.stopPropagation();
        $(this).closest('tr').fadeOut(300, function () {
            $(this).remove();
            calculateTotal();
        });
    });
    $(document).ready(function () {
        // Funkcja walidacji formularza
        $("#registrationForm").on("submit", function (event) {
            event.preventDefault(); // Zapobiega domyślnemu wysłaniu formularza

            let isValid = true; 

            const username = $("#reg-username").val().trim();
            const password = $("#reg-password").val();
            const passwordConfirm = $("#reg-password-confirm").val();
            const street = $("#reg-street").val().trim();
            const houseNumber = $("#reg-house-number").val().trim();
            const postalCode = $("#reg-postal-code").val().trim();
            const city = $("#reg-city").val().trim();

            // Walidacja nazwy użytkownika
            if (username === "") {
                alert("Nazwa użytkownika jest wymagana!");
                isValid = false;
            }

            // Walidacja hasła
            if (password.length < 6) {
                alert("Hasło musi mieć co najmniej 6 znaków!");
                isValid = false;
            }

            // Sprawdzenie zgodności hasła z potwierdzeniem
            if (password !== passwordConfirm) {
                alert("Hasła nie są zgodne!");
                isValid = false;
            }

            // Walidacja ulicy
            if (street === "") {
                alert("Ulica jest wymagana!");
                isValid = false;
            }

            // Walidacja numeru domu/mieszkania
            if (houseNumber === "") {
                alert("Numer domu/mieszkania jest wymagany!");
                isValid = false;
            }

            // Walidacja kodu pocztowego
            const postalCodePattern = /^\d{2}-\d{3}$/; // Wzorzec kodu pocztowego (XX-XXX)
            if (!postalCodePattern.test(postalCode)) {
                alert("Kod pocztowy musi mieć format XX-XXX!");
                isValid = false;
            }

            // Walidacja miasta
            if (city === "") {
                alert("Miasto jest wymagane!");
                isValid = false;
            }

            // Jeśli wszystkie pola są poprawne
            if (isValid) {
                alert("Rejestracja zakończona sukcesem!")
                window.location.href = "logowanie.html";
            }
        });
    });
 

});

function fetchProductsByCategory(category) {
    $.ajax({
        url: "fetch_products.php",
        type: "GET",
        data: { category: category },
        success: function(data) {
            $("#product-gallery").html(data);
        }
    });
}
