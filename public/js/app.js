document.addEventListener('alpine:init', () => {

    Alpine.data('products', () => ({
        items: [
            { id: 1, name: 'Robusta Brazil', img: '1.jpg', price: 20000 },
            { id: 2, name: 'Arabica', img: '2.jpg', price: 30000 },
            { id: 3, name: 'Gayo', img: '3.jpg', price: 40000 },
            { id: 4, name: 'Suliki', img: '4.jpg', price: 50000 },
            { id: 5, name: 'Kayu Aro', img: '5.jpg', price: 55000 },
        ],

    }));

    Alpine.store('cart', {
        items: [],
        total: 0,
        quantity: 0,
        add(newItem) {
            //cek apakah ada barang yang sama di cart
            const cartItem = this.items.find((item) => item.id === newItem.id);

            //jika belim ada / cart kosong

            if (!cartItem) {

                this.items.push({ ...newItem, quantity: 1, total: newItem.price });
                this.quantity++;
                this.total += newItem.price;

            } else {
                //jika barang sudah ada, cek apakah barang sudah ada atau sama dengan di cart
                this.items = this.items.map((item) => {
                    //jika barang berbeda
                    if (item.id !== newItem.id) {
                        return item;
                    } else {
                        //jika barang sudah ada, tambah quantity dan subtotalnya
                        item.quantity++;
                        item.total = item.price * item.quantity;
                        this.quantity++;
                        this.total += item.price;
                        return item;
                    }
                })
            }
        },

        remove(id) {
            // Ambil item yang mau di-remove berdasarkan id
            const cartItem = this.items.find((item) => item.id === id);

            // Jika item lebih dari satu
            if (cartItem.quantity > 1) {
                // Kurangi quantity item tersebut
                this.items = this.items.map((item) => {
                    if (item.id !== id) {
                        return item;
                    } else {
                        item.quantity--;
                        item.total = item.price * item.quantity;
                        this.quantity--;
                        this.total -= item.price;
                        return item;
                    }
                });
            } else if (cartItem.quantity === 1) {
                // Jika quantity item hanya satu, hapus item dari keranjang
                this.items = this.items.filter((item) => item.id !== id);
                this.quantity--;
                this.total -= cartItem.price;
            }
        }

    });

});

const checkoutButton = document.querySelector('.checkout-button');
checkoutButton.disabled = true; // Mulai dengan tombol dinonaktifkan

// Validasi
const form = document.querySelector('#checkoutForm');
form.addEventListener('keyup', function () {
    let allFilled = true;

    for (let i = 0; i < form.elements.length; i++) {
        if (form.elements[i].type !== "submit" && form.elements[i].value.length === 0) {
            allFilled = false;
            break;
        }
    }

    if (allFilled) {
        checkoutButton.disabled = false;
        checkoutButton.classList.remove('disabled');
    } else {
        checkoutButton.disabled = true;
        checkoutButton.classList.add('disabled');
    }
});

// kirimd ata ketika checkout di klik

// checkoutButton.addEventListener('click', function (e) {
// e.preventDefault(); // Mencegah form melakukan submit default

//     const formData = new FormData(form); // Mengambil data form
//     const data = new URLSearchParams(formData); // Mengonversi FormData menjadi URLSearchParams
//     const objData = Object.fromEntries(data); // Mengonversi URLSearchParams menjadi objek

//     console.log(objData); // Menampilkan objek data di console
// });

checkoutButton.addEventListener('click', async function (e) {
    e.preventDefault();
    const formData = new FormData(form);
    const data = new URLSearchParams(formData);
    const objData = Object.fromEntries(data);
    console.log(objData);


    //mintak transaksi token menggunakan ajax/fetch
    try {

        const response = await fetch('php/placeOrder.php', {
            method: 'POST',
            body: data,
        });

        const token = await response.text();
        //console.log(token);
        window.snap.pay(token);

    } catch (err) {
        console.log(err.formatMessage)
    }



    //const message = formatMessage(objData);
    //window.open('http://wa.me//6282258537227?text=' + encodeURIComponent(message));
});

//format pesan whatsapp

const formatMessage = (obj) => {
    return `Data Customer
Nama: ${obj.name}
Email: ${obj.email}
No HP: ${obj.phone}
Data Pesanan 
${JSON.parse(obj.items).map((item) => `${item.name} (${item.quantity} x ${rupiah(item.total)})`).join('\n')}
TOTAL: ${rupiah(obj.total)}
Terima Kasih;
`;
}

// const formatMessage = (obj) => {
//     return `Data Customer
//     Nama: ${obj.name}
//     Email: ${obj.email}
//     No HP: ${obj.phone}
//     Data Pesanan 
//     ${JSON.parse(obj.items).map((item) => `${item.name} (${item.quantity} x ${rupiah(item.total)}) \n `)}
//     TOTAL: ${rupiah(obj.total)}
//     Terima Kasih;
//     `;
// }


//konversi ke rupiah

const rupiah = (number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(number);
};


