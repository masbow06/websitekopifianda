// Cart Functionality
const ID_CART_PRODUKID = `cart-produk-`;
const ID_QUANTITY_PRODUK = `quantity-produk-`;
const ID_TOTAL_HARGA_PRODUK = `total-harga-produk-`;

document.addEventListener('DOMContentLoaded', () => {
    const checkoutButton = document.querySelector('.checkout-button');
    checkoutButton.disabled = true; // Start with the button disabled

    const form = document.querySelector('#checkoutForm');

    // Function to build JSON from cart items
    function buildJson() {
        const cartItems = [];
        const itemElements = document.querySelectorAll('#item-list .cart-item');

        itemElements.forEach((itemElement) => {
            const id = itemElement.dataset.id;
            const quantity = Number(itemElement.querySelector('.item-quantity').textContent);
            cartItems.push({ id, quantity });
        });

        return cartItems;
    }

    // Validation Function
    const validateForm = function () {
        let allFilled = true;

        const json = buildJson();
        form.elements['items'].value = JSON.stringify(json);

        for (let i = 0; i < form.elements.length; i++) {
            if (
                form.elements[i].type !== "submit" &&
                form.elements[i].type !== "button" &&
                form.elements[i].value.trim().length === 0
            ) {
                allFilled = false;
                break;
            }
        }

        if (allFilled && json.length > 0) {
            checkoutButton.disabled = false;
            checkoutButton.classList.remove('disabled');
        } else {
            checkoutButton.disabled = true;
            checkoutButton.classList.add('disabled');
        }
    };
    form.addEventListener('keyup', validateForm);
    form.addEventListener('mouseup', validateForm);

    // Utility Function to Format Number to Rupiah
    const rupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(number);
    };

    window.addToCart = (id, name, price, image) => {
        const cart = document.querySelector("#item-list");
        const cartItem = document.querySelector(`#${ID_CART_PRODUKID}${id}`);

        // If the product is not in the cart
        if (cartItem == null) {
            cart.innerHTML += buildItemCart(id, name, price, image);
            changeCartTotalQuantity(1);
            changeCartTotalPrice(price);
        } else { // If the product is already in the cart
            increaseQuantity(id, price);
        }

        validateForm(); // Validate form after adding to cart
    };

    const changeCartTotalQuantity = (delta) => {
        const totalQuantity = document.querySelector('.quantity-badge');
        totalQuantity.textContent = Number(totalQuantity.textContent) + delta;
    };

    const changeCartTotalPrice = (price) => {
        const cartTotalPrice = document.querySelector('#cartTotalPrice');
        cartTotalPrice.textContent = Number(cartTotalPrice.textContent) + price;
    };

    window.increaseQuantity = (id, price) => {
        const cartItem = document.querySelector(`#${ID_CART_PRODUKID}${id}`);
        const quantity = cartItem.querySelector(`.item-quantity`);
        const totalPrice = cartItem.querySelector(`.item-total`);

        quantity.textContent = Number(quantity.textContent) + 1;
        totalPrice.textContent = rupiah(price * Number(quantity.textContent));
        changeCartTotalQuantity(1);
        changeCartTotalPrice(price);

        validateForm(); // Validate form after increasing quantity
    };

    window.decreaseQuantity = (id, price) => {
        const cartItem = document.querySelector(`#${ID_CART_PRODUKID}${id}`);
        const quantity = cartItem.querySelector(`.item-quantity`);
        const totalPrice = cartItem.querySelector(`.item-total`);

        if (Number(quantity.textContent) === 1) {
            cartItem.remove();
            changeCartTotalQuantity(-1);
            changeCartTotalPrice(-price);
        } else {
            quantity.textContent = Number(quantity.textContent) - 1;
            totalPrice.textContent = rupiah(price * Number(quantity.textContent));
            changeCartTotalQuantity(-1);
            changeCartTotalPrice(-price);
        }

        validateForm(); // Validate form after decreasing quantity
    };

    const buildItemCart = (id, name, price, image) => {
        const idCartProdukId = `${ID_CART_PRODUKID}${id}`;
        const idQuantityProduk = `${ID_QUANTITY_PRODUK}${id}`;
        const idTotalHargaProduk = `${ID_TOTAL_HARGA_PRODUK}${id}`;

        return `
            <div class="cart-item" id="${idCartProdukId}" data-id="${id}">
                <img src="${image}" alt="${name}">
                <div class="item-detail">
                    <h3 class="item-name">${name}</h3>
                    <div class="item-price">
                        <span>${rupiah(price)}</span> &times;
                        <button type="button" onclick="decreaseQuantity(${id}, ${price})">&minus;</button>
                        <span class="item-quantity">1</span>
                        <button type="button" onclick="increaseQuantity(${id}, ${price})">&plus;</button> =
                        <span class="item-total">${rupiah(price)}</span>
                    </div>
                </div>
            </div>`;
    };
});