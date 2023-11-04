// Отримуємо посилання на вибір товару, кількість і блок для ціни
const productSelect = document.getElementById("productSelect");
const quantityInput = document.getElementById("quantityInput");
const SubtotalDiv = document.getElementById("Subtotal");

// Функція для оновлення відображення ціни
function updatePrice() {
  // Отримуємо вибраний товар та його ціну
  const selectedProduct = productSelect.options[productSelect.selectedIndex];
  const productPrice = parseFloat(selectedProduct.getAttribute("data-price"));

  // Отримуємо вибрану кількість
  const quantity = parseInt(quantityInput.value);

  // Розраховуємо підсумок
  const Subtotal = productPrice * quantity;

  // Оновлюємо відображення ціни з урахуванням кількості
  SubtotalDiv.textContent = ` ${Subtotal.toFixed(2)} грн`;
}

// Додаємо обробники подій для вибору товару і зміни кількості
productSelect.addEventListener("change", updatePrice);
quantityInput.addEventListener("input", updatePrice);

// Викликаємо функцію одразу, щоб показати ціну за замовчуванням
updatePrice();
