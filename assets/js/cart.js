import axios from "axios";

export default class Cart {
  updateTotal(quantity, price) {
    return quantity * price;
  }
}
