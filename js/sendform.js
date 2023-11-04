$("#form-contact").submit(function (event) {
  event.preventDefault();

  // Сообщения формы
  let successSendText = "Сообщение успешно отправлено";
  let errorSendText = "Сообщение не отправлено. Попробуйте еще раз!";
  let requiredFieldsText = "Заполните поля с именем и телефоном";

  // Сохраняем в переменную класс с параграфом для вывода сообщений об отправке
  let message = $(this).find(".contact-form__message");

  let form = $("#" + $(this).attr("id"))[0];
  let fd = new FormData(form);

  let productData = window.localStorage.getItem("order list");
  fd.append("productData", productData);

  $.ajax({
    url: "./sendform.php",
    type: "POST",
    data: fd,
    //data: fd+productData,
    processData: false,
    contentType: false,
    beforeSend: () => {
      $(".preloader").addClass("preloader_active");
    },
    success: function success(res) {
      $(".preloader").removeClass("preloader_active");

      // Посмотреть на статус ответа, если ошибка
      console.log(res);
      let respond = $.parseJSON(res);

      if (respond === "SUCCESS") {
        message.text(successSendText).css("color", "#21d4bb");
        setTimeout(() => {
          message.text("");
        }, 4000);
      } else if (respond === "NOTVALID") {
        message.text(requiredFieldsText).css("color", "#d42121");
        setTimeout(() => {
          message.text("");
        }, 3000);
      } else {
        message.text(errorSendText).css("color", "#d42121");
        setTimeout(() => {
          message.text("");
        }, 4000);
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(
        thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
      );
    },
  });
  location.assign("./form.html");
});
