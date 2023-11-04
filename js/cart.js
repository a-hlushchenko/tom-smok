class Order {
  static #orderlist = [];
  static #cart = document.querySelector(".order-list");
  static #template =
    document.getElementById("template").content.firstElementChild;
  static #NAME = "order list";
  static #count = document.querySelector(".cart-quantity");

  constructor(price, name, amount, option, img) {
    this.price = price;
    this.name = name;
    this.amount = amount;
    this.option = option;
    this.img = img;
    this.id = Order.#orderlist.length;
  }

  static addProduct = (price, productName, amount, option, img) => {
    const product = new Order(price, productName, amount, option, img);
    this.#orderlist.push(product);
    this.#render();
    this.#save();
    this.#count.innerHTML = this.#orderlist.length;
  };

  static #save = () => {
    window.localStorage.setItem(this.#NAME, JSON.stringify(this.#orderlist));
  };

  static #load = () => {
    this.#orderlist = JSON.parse(window.localStorage.getItem(this.#NAME)) || [];
    this.#count.innerHTML = this.#orderlist.length;
  };

  static #render = () => {
    let totalElement = document.querySelector(".total-price");
    let total = 0;
    this.#cart.innerHTML = "";
    let freeDelAmount = document.querySelector(".free-del-amount");
    let getFreeDel = document.querySelector(".get-free-del");
    let haveFreeDel = document.querySelector(".have-free-del");

    this.#orderlist.forEach((data) => {
      const temp = this.#template.cloneNode(true);
      temp.id = data.id;
      const img = temp.querySelector(".order-img");
      img.src = data.img;
      const title = temp.querySelector(".order-title");
      title.innerHTML = data.name;
      const amount = temp.querySelector(".order-amount");
      amount.value = data.amount;

      const price = temp.querySelector(".order-price");
      price.innerHTML = data.price + " грн";
      const option = temp.querySelector(".order-option");
      option.innerHTML = `смак: ${data.option}`;
      const btnRemove = temp.querySelector(".order-remove");
      btnRemove.onclick = () => this.#delProduct(data.id);

      amount.addEventListener("change", (event) => {
        let cart_id = $(amount).closest(".order").attr("id");
        this.#orderlist[cart_id].amount = $(amount).val();
        this.#save();
        this.#render();
      });

      temp.querySelectorAll(".btn-edit").forEach((el) => {
        el.onclick = () => {
          if (el.getAttribute("operator") === "+") {
            amount.stepUp(1);
          } else if (el.getAttribute("operator") === "-" && amount.value > 1) {
            amount.stepDown(1);
          } else if (el.getAttribute("operator") === "-" && amount.value == 1) {
            btnRemove.click();
          }

          let cart_id = $(amount).closest(".order").attr("id");
          this.#orderlist[cart_id].amount = $(amount).val();
          this.#save();
          this.#render();
        };
      });

      total += data.price * data.amount;

      totalElement.innerHTML = total + " грн";

      if (total >= 1000) {
        getFreeDel.classList.toggle("get-free-del-disabled", true);
        haveFreeDel.classList.toggle("have-free-del-disabled", false);
      } else if (total > 0 && total < 1000) {
        freeDelAmount.innerHTML = 1000 - total;
        getFreeDel.classList.toggle("get-free-del-disabled", false);
        haveFreeDel.classList.toggle("have-free-del-disabled", true);
      }

      this.#cart.append(temp);

      this.#count.innerHTML = this.#orderlist.length;
    });
  };

  static #delProduct = (id) => {
    this.#orderlist = this.#orderlist.filter((data) => data.id !== id);

    this.#save();
    this.#render();
  };

  static init = () => {
    this.#load();
    const button = document.querySelector(".buy-button-wrap");
    const toOrderBtn = document.querySelector(
      ".w-commerce-commercecarterrorstate.error-message"
    );

    if (button) {
      button.onclick = (e) => {
        e.preventDefault();

        const option_id = document.querySelector(
          ".select-field.w-select"
        ).value;
        if (!option_id) {
          alert("Оберіть смак");
          return;
        }

        const img = document.querySelector(".product-image").src;
        const amount = document.querySelector(
          ".w-commerce-commerceaddtocartquantityinput.quantity"
        ).value;
        const price = parseInt(
          document.querySelector(".price-wrap div[data-wf-sku-bindings]")
            .innerHTML
        );
        const productName = document.querySelector(".text-h2").innerHTML.trim();

        const option = document
          .querySelector("option[value='" + option_id + "']")
          .innerHTML.trim();

        const cartButton = document.querySelector(
          ".w-commerce-commercecartopenlink.cart-button.w-inline-block"
        );

        Order.addProduct(price, productName, amount, option, img);
        cartButton.click();
      };
    }

    window.addEventListener("click", () => {
      if (this.#orderlist.length === 0) {
        toOrderBtn.classList.toggle("empty", true);
        document.querySelector(".total").style.display = "none";
      } else {
        toOrderBtn.classList.toggle("empty", false);
        document.querySelector(".total").style.display = "flex";
      }
    });

    toOrderBtn.onclick = () => {
      location.assign("./makeorder.html");
    };

    this.#render();
    this.#count.innerHTML = this.#orderlist.length;

    setTimeout(() => {
      this.#count.innerHTML = this.#orderlist.length;
    }, 10);
    setTimeout(() => {
      this.#count.innerHTML = this.#orderlist.length;
    }, 100);
    setTimeout(() => {
      this.#count.innerHTML = this.#orderlist.length;
    }, 500);
    setTimeout(() => {
      this.#count.innerHTML = this.#orderlist.length;
    }, 1000);
    setTimeout(() => {
      this.#count.innerHTML = this.#orderlist.length;
    }, 2000);
  };
}

Order.init();
