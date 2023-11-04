  const orderlist = JSON.parse(window.localStorage.getItem("order list"));
  let sum = 0;
  orderlist.forEach((el) => {
    sum += el.price * el.amount;
  });

  document.querySelector("#sum").innerHTML = "Сума замовлення: " + sum + "грн";
