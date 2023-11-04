// Отримуємо посилання на мета-теги в HTML-документі
const metaTitle = document.querySelector('meta[property="og:title"]');
const metaDescription = document.querySelector('meta[property="og:description"]');
const metaImage = document.querySelector('meta[property="og:image"]');

// Змінюємо значення мета-тегів на власні дані
metaTitle.setAttribute("content", "Головна | Вейп шоп Tom&Smok - інтернет магазин електронних сигарет");
metaDescription.setAttribute("content", "Купити одноразки тепер можливо у нашому вейп-шопі. Кращі одноразки за найнижчими цінами. Одноразові електронні сигарети в інтернет-магазині. Найкращі ціни на одноразки та рідини, ✈ швидка доставка, ☑ гарантія!");
metaImage.setAttribute("content", "img/preview.jpg");
