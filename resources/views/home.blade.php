<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kopi Kenangan Senja</title>


    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,300;1,700&display=swap"
        rel="stylesheet">
    <!-- Feather Icon -->
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet">

    <!-- My Style  -->
    <link rel="stylesheet" href="{{asset('css/home.style.css')}}">

    <!-- app -->
    <script src="{{asset('js/app.js')}}" defer></script>

    <!-- midtrans -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="SB-Mid-client-lqG5F2zZDGCm8Uaa"></script>

</head>

<body>

    <!-- navbar start -->
    <nav class="navbar" x-data>
        <a href="#" class="navbar-logo">Kenangan<span>Senja</span></a>

        <div class="navbar-nav">
            <a href="#home">Home</a>
            <a href="#about">Tentang Kami</a>
            <a href="#menu">Menu</a>
            <a href="#products">produk</a>
            <a href="#contact">Kontak</a>
        </div>

        <div class="navbar-exstra">
            <a href="#" id="search-button"><i data-feather="search"></i></a>
            <a href="#" id="shopping-cart-button">
                <i data-feather="shopping-cart"></i>
                <span class="quantity-badge">0</span>
            </a>
            <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
        </div>

        <!-- Search form start -->

        <div class="search-form">
            <input type="search-button" id="search-box" placeholder="search here...">
            <label for="search-box"><i data-feather="search"></i></label>
        </div>

        <!-- Search form end -->

        <!-- shopping cart start -->
        <div class="shoppingcart">
            <h4 style="margin-top: 1rem;">Keranjang Belanja</h4>
            <h4>Total : <span id="cartTotalPrice"></span></h4>
            <div class="form-container">
                <form method="POST" action="/order" id="checkoutForm">
                    @csrf
                    <div id="item-list"></div>
                    <h5>Customer Detail</h5>
                    <label for="name">
                        <span>Name</span>
                        <input type="text" name="name" placeholder="Nama pemesan" id="name">
                    </label>
                    <label for="email">
                        <span>Email</span>
                        <input type="email" name="email" placeholder="Email pemesan" id="email">
                    </label>
                    <label for="phone">
                        <span>Phone</span>
                        <input type="number" name="phone" id="phone" placeholder="Nomor pemesan | Tanpa 0 diawal nomor" autocomplete="off">
                    </label>
                    <label for="address">
                        <span>Address</span>
                        <input type="text" name="address" placeholder="Alamat pemesan" id="address">
                    </label>
                    <input type="hidden" name="items" id="cart-items" value="[]">
                    <button class="checkout-button disabled" type="submit" id="checkout-button"
                        value="">checkout</button>
                </form>
            </div>
        </div>
        <!-- shopping cart end -->

    </nav>
    <!-- navbar end -->


    <!-- Hero Section start -->
    <section class="hero" id="home">
        <main class="content">
            <h1>Mari Nikmati Secangkir <span> Kopi </span></h1>
            <p>Lorem ipsum dolor, sit amet
                consectetur adipisicing elit.
                Ea, itaque!</p>
        </main>
    </section>
    <!-- Hero Section end -->


    <!-- about section start -->
    <section id="about" class="about">
        <h2><span>Tentang</span> Kami</h2>

        <div class="row">
            <div class="about-img">
                <img src="{{asset('img/gambarAbout/kp1.jpeg')}}" alt="Tentang Kami">
            </div>
            <div class="content">
                <h3>Kenapa Memilih Kopi Kami</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Natus nesciunt facilis quis quam! A,
                    veritatis?</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ratione corrupti adipisci, quo quia eius
                    vero dignissimos? Accusantium, blanditiis facilis. Suscipit? </p>
            </div>
        </div>
    </section>
    <!-- about section end -->


    <!-- menu section start -->
    <section id="menu" class="menu">
        <h2><span>Menu</span> Kami</h2>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Possimus mollitia recusandae aliquam a illum. </p>
        <div class="row">
            @foreach ($menus as $menu)
            <div class="menu-card">
                <img width="200" height="200" src="{{$menu->image}}" alt="{{$menu->namamenu}}" class="menu-card-image">
                <h3 class="menu-card-tittle">{{$menu->namamenu}}</h3>
                <p class="menu-price">{{ (new NumberFormatter('id_ID', NumberFormatter::CURRENCY))->formatCurrency($menu->harga, "IDR") }}</p>
            </div>
            @endforeach
        </div>
    </section>
    <!-- menu section end -->


    <!-- produk section start -->
    <section class="products" id="products" x-data="products">
        <h2><span>Produk Unggulan</span>Kami</h2>
        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ullam, ad?</p>

        <div class="row">
                @foreach ( $produks as $produk )
                <div class="product-card">
                    <div class="product-icons">
                        <a onclick="addToCart({{$produk->id}}, '{{$produk->namaproduk}}', {{$produk->harga}}, '{{$produk->image}}')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                style="fill: rgb(255, 255, 255);transform: msFilter">
                                <path
                                    d="M5 22h14c1.103 0 2-.897 2-2V9a1 1 0 0 0-1-1h-3V7c0-2.757-2.243-5-5-5S7 4.243 7 7v1H4a1 1 0 0 0-1 1v11c0 1.103.897 2 2 2zM9 7c0-1.654 1.346-3 3-3s3 1.346 3 3v1H9V7zm-4 3h2v2h2v-2h6v2h2v-2h2l.002 10H5V10z">
                                </path>
                            </svg></a>
                        <a href="#" class="item-detail-button"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" style="fill: rgb(255, 255, 255);transform: msFilter">
                                <path
                                    d="M12 4.998c-1.836 0-3.356.389-4.617.971L3.707 2.293 2.293 3.707l3.315 3.316c-2.613 1.952-3.543 4.618-3.557 4.66l-.105.316.105.316C2.073 12.382 4.367 19 12 19c1.835 0 3.354-.389 4.615-.971l3.678 3.678 1.414-1.414-3.317-3.317c2.614-1.952 3.545-4.618 3.559-4.66l.105-.316-.105-.316c-.022-.068-2.316-6.686-9.949-6.686zM4.074 12c.103-.236.274-.586.521-.989l5.867 5.867C6.249 16.23 4.523 13.035 4.074 12zm9.247 4.907-7.48-7.481a8.138 8.138 0 0 1 1.188-.982l8.055 8.054a8.835 8.835 0 0 1-1.763.409zm3.648-1.352-1.541-1.541c.354-.596.572-1.28.572-2.015 0-.474-.099-.924-.255-1.349A.983.983 0 0 1 15 11a1 1 0 0 1-1-1c0-.439.288-.802.682-.936A3.97 3.97 0 0 0 12 7.999c-.735 0-1.419.218-2.015.572l-1.07-1.07A9.292 9.292 0 0 1 12 6.998c5.351 0 7.425 3.847 7.926 5a8.573 8.573 0 0 1-2.957 3.557z">
                                </path>
                            </svg></a>
                    </div>
                    <div class="product-image">
                        <img src="{{$produk->image}}" alt="{{$produk->namaproduk}}">
                    </div>
                    <div class="product-conten">
                        <h3 >{{$produk->namaproduk}}</h3>
                        <div class="product-star">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                style="fill: #513c28 ;transform: msFilter">
                                <path
                                    d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z">
                                </path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                style="fill: #513c28 ;transform: msFilter">
                                <path
                                    d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z">
                                </path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                style="fill: #513c28 ;transform: msFilter">
                                <path
                                    d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z">
                                </path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                style="fill: #513c28 ;transform: msFilter">
                                <path
                                    d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z">
                                </path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                style="fill: #513c28 ;transform: msFilter">
                                <path
                                    d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z">
                                </path>
                            </svg>
                        </div>
                        <div class="product-price"><span>{{$produk->harga}}</span></div>
                    </div>
                </div>
                @endforeach
        </div>
    </section>
    <!-- produk section end -->

    <!-- contact section start -->
    <section id="contact" class="contact">
        <h2><span>kontak</span> Kami</h2>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa, dolorem!</p>

        <div class="row">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63836.58141976395!2d100.59155271614354!3d-0.22940955764657844!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2fd54c3c6f83a013%3A0x4039d80b2210dd0!2sPayakumbuh%2C%20Kota%20Payakumbuh%2C%20Sumatera%20Barat!5e0!3m2!1sid!2sid!4v1701974034865!5m2!1sid!2sid" " allowfullscreen="" loading="
                lazy" referrerpolicy="no-referrer-when-downgrade" class="map"></iframe>

            <form action="">
                <div class="input-group">
                    <i data-feather="user"></i>
                    <input type="text" placeholder="nama">
                </div>
                <div class="input-group">
                    <i data-feather="mail"></i>
                    <input type="text" placeholder="email">
                </div>
                <div class="input-group">
                    <i data-feather="phone"></i>
                    <input type="text" placeholder="no Hp">
                </div>
                <button type="submit" class="btn">Kirim Pesan</button>
            </form>

        </div>


    </section>
    <!-- contact section end -->


    <!-- foother start -->
    <footer>
        <div class="socials">
            <a href="#"><i data-feather="instagram"></i></a>
            <a href="#"><i data-feather="twitter"></i></a>
            <a href="#"><i data-feather="facebook"></i></a>
        </div>

        <div class="links">
            <a href="#home">Home</a>
            <a href="#about">Tentang Kami</a>
            <a href="#contact">Kontak</a>
        </div>

        <div class="credit">
            <p>Created by <a href="">Aditia Prabowo</a>. | &copy 2023. </p>
        </div>
    </footer>
    <!-- foother end -->

    <!-- Modal Box Item Detai star-->

    <div class="modal" id="item-detail-modal">
        <div class="modal-container">
            <a href="#" class="close-icon"><i data-feather="x"></i></a>
            <div class="modal-content">
                <img src="{{asset('img/product/1.jpg')}}" alt="Product 1">
                <div class="product-content">
                    <h3>Product 1</h3>
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Provident, tenetur cupiditate facilis
                        obcaecati
                        ullam maiores minima quos perspiciatis similique itaque, esse rerum eius repellendus
                        voluptatibus!</p>
                    <div class="product-stars">
                        <i data-feather="star" class="star-full"></i>
                        <i data-feather="star" class="star-full"></i>
                        <i data-feather="star" class="star-full"></i>
                        <i data-feather="star" class="star-full"></i>
                        <i data-feather="star"></i>
                    </div>
                    <div class="product-price">IDR 30K <span>IDR 55K</span></div>
                    <a href="#"><i data-feather="shopping-cart"></i> <span>add to cart</span></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Box Item Detai end-->

    <!-- Feather Icon  -->
    <script>
        console.log('{{$snap_token}}')
        feather.replace();
    </script>

    <script src="js/script.js"></script>
    @if ($snap_token)
    <script>
        window.snap.pay('{{ $snap_token }}');
    </script>
    @endif
</body>

</html>