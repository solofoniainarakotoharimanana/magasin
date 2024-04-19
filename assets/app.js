/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./styles/app.scss";
/*import "./styles/header.css";*/
import Cart from "./js/cart";
import Header from "./js/header";

import * as bootstrap from "bootstrap";

//import "./bootstrap";
const $ = require("jquery");

document.addEventListener("DOMContentLoaded", () => {
  const inputQuantity = document.querySelector("#product-quantity");
  const inputPrice = document.querySelector("#product-price");
  var spanTotal = document.querySelector("#product-total-price");
  spanTotal.textContent =
    parseInt(inputQuantity.value) * parseFloat(inputPrice.value).toFixed(2);
  //const total = parseInt(inputQuantity.value) * parseFloat(inputPrice.value);
  inputQuantity.addEventListener("change", () => {
    const cart = new Cart();
    const total = cart.updateTotal(
      parseInt(inputQuantity.value),
      parseFloat(inputPrice.value)
    );
    spanTotal.textContent = total.toFixed(2);
  });
});
