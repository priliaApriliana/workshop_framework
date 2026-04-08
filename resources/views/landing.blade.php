<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodOrder - Pesan Makanan Online</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* ========================================
           CSS VARIABLES & RESET
        ======================================== */
        :root {
            --pink-50: #fef2f4;
            --pink-100: #fde6ea;
            --pink-200: #facdd6;
            --pink-300: #f7a4b5;
            --pink-400: #f06e8e;
            --pink-500: #e63e6d;
            --pink-600: #d42a5b;
            --cream-50: #fffdf7;
            --cream-100: #fdf6e9;
            --cream-200: #f9ecd3;
            --brown-600: #6b4226;
            --brown-700: #523318;
            --gold-400: #d4a855;
            --gold-500: #c4943f;
            --text-dark: #2d1f14;
            --text-muted: #7a6b5f;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            background: var(--cream-50);
            overflow-x: hidden;
        }

        /* ========================================
           NAVBAR
        ======================================== */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 18px 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 2px 30px rgba(0,0,0,0.06);
            padding: 12px 0;
        }

        .navbar .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--brown-700);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand .brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--pink-400), var(--pink-500));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        .navbar-links {
            display: flex;
            align-items: center;
            gap: 12px;
            list-style: none;
        }

        .navbar-links a {
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-dark);
            padding: 8px 18px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .navbar-links a:hover {
            color: var(--pink-500);
        }

        .btn-nav-vendor {
            background: var(--white) !important;
            border: 2px solid var(--pink-400) !important;
            color: var(--pink-500) !important;
            font-weight: 600 !important;
        }

        .btn-nav-vendor:hover {
            background: var(--pink-50) !important;
            transform: translateY(-1px);
        }

        .btn-nav-order {
            background: linear-gradient(135deg, var(--pink-400), var(--pink-500)) !important;
            color: white !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 15px rgba(240, 110, 142, 0.35);
        }

        .btn-nav-order:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(240, 110, 142, 0.45);
        }

        /* ========================================
           CONTAINER
        ======================================== */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ========================================
           HERO SECTION
        ======================================== */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, var(--cream-50) 0%, var(--pink-50) 50%, var(--cream-100) 100%);
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(247, 164, 181, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(212, 168, 85, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero .container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .hero-content {
            animation: fadeInUp 1s ease;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--white);
            border: 1px solid var(--pink-200);
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--pink-500);
            margin-bottom: 24px;
            box-shadow: 0 2px 10px rgba(240, 110, 142, 0.08);
        }

        .hero-badge i {
            font-size: 0.7rem;
            animation: pulse 2s infinite;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 3.8rem;
            font-weight: 800;
            line-height: 1.15;
            color: var(--brown-700);
            margin-bottom: 20px;
        }

        .hero-title .highlight {
            color: var(--pink-500);
            position: relative;
        }

        .hero-title .highlight::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 0;
            width: 100%;
            height: 8px;
            background: rgba(240, 110, 142, 0.2);
            border-radius: 4px;
            z-index: -1;
        }

        .hero-desc {
            font-size: 1.1rem;
            line-height: 1.7;
            color: var(--text-muted);
            margin-bottom: 36px;
            max-width: 460px;
        }

        .hero-buttons {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .btn-primary-lg {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 36px;
            background: linear-gradient(135deg, var(--pink-400), var(--pink-500));
            color: white;
            text-decoration: none;
            border-radius: 60px;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 6px 25px rgba(240, 110, 142, 0.35);
            border: none;
            cursor: pointer;
        }

        .btn-primary-lg:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 40px rgba(240, 110, 142, 0.45);
        }

        .btn-secondary-lg {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 36px;
            background: var(--white);
            color: var(--brown-700);
            text-decoration: none;
            border-radius: 60px;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.4s ease;
            border: 2px solid var(--cream-200);
        }

        .btn-secondary-lg:hover {
            border-color: var(--pink-300);
            background: var(--pink-50);
            transform: translateY(-2px);
        }

        .hero-image {
            position: relative;
            animation: fadeInRight 1s ease 0.3s both;
        }

        .hero-image img {
            width: 100%;
            border-radius: 30px;
            box-shadow: 0 30px 80px rgba(107, 66, 38, 0.15);
        }

        .hero-image::before {
            content: '';
            position: absolute;
            top: -15px;
            right: -15px;
            width: 100%;
            height: 100%;
            border: 3px solid var(--pink-200);
            border-radius: 30px;
            z-index: -1;
        }

        .hero-stat {
            position: absolute;
            background: var(--white);
            border-radius: 20px;
            padding: 16px 22px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: float 3s ease-in-out infinite;
        }

        .hero-stat.stat-1 {
            bottom: 30px;
            left: -30px;
        }

        .hero-stat.stat-2 {
            top: 30px;
            right: -20px;
            animation-delay: 1.5s;
        }

        .hero-stat .stat-icon {
            width: 45px;
            height: 45px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .hero-stat .stat-icon.pink { background: var(--pink-100); color: var(--pink-500); }
        .hero-stat .stat-icon.gold { background: var(--cream-200); color: var(--gold-500); }

        .hero-stat .stat-text h4 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--brown-700);
        }

        .hero-stat .stat-text p {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin: 0;
        }

        /* ========================================
           HOW IT WORKS
        ======================================== */
        .how-it-works {
            padding: 100px 0;
            background: var(--white);
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-label {
            display: inline-block;
            padding: 6px 20px;
            background: var(--pink-50);
            color: var(--pink-500);
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--brown-700);
            margin-bottom: 16px;
        }

        .section-desc {
            font-size: 1.05rem;
            color: var(--text-muted);
            max-width: 550px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
        }

        .step-card {
            text-align: center;
            padding: 40px 30px;
            border-radius: 24px;
            transition: all 0.4s ease;
            position: relative;
        }

        .step-card:hover {
            transform: translateY(-8px);
            background: var(--pink-50);
        }

        .step-number {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px;
        }

        .step-number.pink { background: linear-gradient(135deg, var(--pink-100), var(--pink-200)); color: var(--pink-500); }
        .step-number.cream { background: linear-gradient(135deg, var(--cream-100), var(--cream-200)); color: var(--gold-500); }
        .step-number.brown { background: linear-gradient(135deg, #f0e6dc, #e8d5c4); color: var(--brown-600); }

        .step-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--brown-700);
            margin-bottom: 10px;
        }

        .step-card p {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* ========================================
           FEATURED VENDORS
        ======================================== */
        .featured-vendors {
            padding: 100px 0;
            background: linear-gradient(180deg, var(--cream-50) 0%, var(--pink-50) 100%);
        }

        .vendors-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }

        .vendor-card {
            background: var(--white);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
        }

        .vendor-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 50px rgba(0,0,0,0.1);
        }

        .vendor-card-img {
            width: 220px;
            min-height: 240px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .vendor-card-body {
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .vendor-tag {
            display: inline-block;
            padding: 4px 14px;
            background: var(--pink-100);
            color: var(--pink-500);
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 12px;
            width: fit-content;
        }

        .vendor-card-body h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--brown-700);
            margin-bottom: 8px;
        }

        .vendor-card-body p {
            color: var(--text-muted);
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 18px;
        }

        .vendor-card-body .btn-small {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 22px;
            background: linear-gradient(135deg, var(--pink-400), var(--pink-500));
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s ease;
            width: fit-content;
            box-shadow: 0 4px 15px rgba(240, 110, 142, 0.25);
        }

        .vendor-card-body .btn-small:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(240, 110, 142, 0.4);
        }

        /* ========================================
          CTA SECTION
        ======================================== */
        .cta-section {
            padding: 100px 0;
            background: var(--white);
        }

        .cta-box {
            background: linear-gradient(135deg, var(--pink-400) 0%, var(--pink-500) 50%, var(--pink-600) 100%);
            border-radius: 32px;
            padding: 70px 60px;
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 40px;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .cta-box::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
        }

        .cta-box::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: 30%;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }

        .cta-content {
            position: relative;
            z-index: 2;
        }

        .cta-content h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: white;
            font-weight: 700;
            margin-bottom: 16px;
            line-height: 1.2;
        }

        .cta-content p {
            color: rgba(255,255,255,0.85);
            font-size: 1.05rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .cta-buttons {
            display: flex;
            gap: 16px;
            position: relative;
            z-index: 2;
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-white {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 36px;
            background: var(--white);
            color: var(--pink-500);
            text-decoration: none;
            border-radius: 60px;
            font-size: 1rem;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 6px 25px rgba(0,0,0,0.15);
        }

        .btn-white:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 35px rgba(0,0,0,0.2);
        }

        .btn-outline-white {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 34px;
            background: transparent;
            color: white;
            text-decoration: none;
            border-radius: 60px;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid rgba(255,255,255,0.5);
        }

        .btn-outline-white:hover {
            background: rgba(255,255,255,0.15);
            border-color: white;
        }

        /* ========================================
           FOOTER
        ======================================== */
        .footer {
            padding: 60px 0 30px;
            background: var(--brown-700);
            color: rgba(255,255,255,0.7);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 50px;
            margin-bottom: 40px;
        }

        .footer-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 14px;
        }

        .footer p {
            font-size: 0.9rem;
            line-height: 1.7;
        }

        .footer h4 {
            color: white;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 18px;
        }

        .footer ul {
            list-style: none;
            padding: 0;
        }

        .footer ul li {
            margin-bottom: 10px;
        }

        .footer ul a {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .footer ul a:hover {
            color: var(--pink-300);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 20px;
            text-align: center;
            font-size: 0.85rem;
        }

        /* ========================================
           ANIMATIONS
        ======================================== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(40px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        /* Scroll animations */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ========================================
           RESPONSIVE
        ======================================== */
        @media (max-width: 768px) {
            .hero .container { grid-template-columns: 1fr; gap: 40px; }
            .hero-title { font-size: 2.6rem; }
            .hero-image { order: -1; }
            .hero-stat { display: none; }
            .steps-grid { grid-template-columns: 1fr; gap: 20px; }
            .vendors-grid { grid-template-columns: 1fr; }
            .vendor-card { flex-direction: column; }
            .vendor-card-img { width: 100%; height: 200px; }
            .cta-box { grid-template-columns: 1fr; padding: 40px 30px; }
            .footer-grid { grid-template-columns: 1fr; gap: 30px; }
            .navbar-links { display: none; }
            .hero-buttons { flex-direction: column; }
        }
    </style>
</head>
<body>

    <!-- ==========================================
         NAVBAR
    ========================================== -->
    <nav class="navbar" id="navbar">
        <div class="container">
            <a href="/" class="navbar-brand">
                <span class="brand-icon"><i class="fas fa-utensils"></i></span>
                FoodOrder
            </a>
            <div class="navbar-links">
                <a href="#home">Home</a>
                <a href="#how-it-works">Cara Pesan</a>
                <a href="#vendors">Menu</a>
                <a href="{{ route('login') }}" class="btn-nav-vendor">
                    <i class="fas fa-store"></i> Login 
                </a>
                <a href="{{ route('customer.order.index') }}" class="btn-nav-order">
                    <i class="fas fa-shopping-bag"></i> Order Now
                </a>
            </div>
        </div>
    </nav>

    <!-- ==========================================
         HERO SECTION
    ========================================== -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-circle"></i> Pesan Online, Tanpa Ribet
                </div>
                <h1 class="hero-title">
                    Pesan Makanan<br>
                    <span class="highlight">Favoritmu</span><br>
                    Sekarang.
                </h1>
                <p class="hero-desc">
                    Nikmati kemudahan pesan makanan dari berbagai vendor terbaik.
                    Tanpa perlu login, langsung pesan dan bayar secara online.
                </p>
                <div class="hero-buttons">
                    <a href="{{ route('customer.order.index') }}" class="btn-primary-lg">
                        <i class="fas fa-shopping-bag"></i> Order Now
                    </a>
                    <a href="{{ route('login') }}" class="btn-secondary-lg">
                        <i class="fas fa-store"></i> Login 
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <img src="{{ asset('images/landing/hero-food.png') }}" alt="Makanan Lezat">
                <div class="hero-stat stat-1">
                    <div class="stat-icon pink"><i class="fas fa-heart"></i></div>
                    <div class="stat-text">
                        <h4>500+</h4>
                        <p>Pesanan Hari Ini</p>
                    </div>
                </div>
                <div class="hero-stat stat-2">
                    <div class="stat-icon gold"><i class="fas fa-star"></i></div>
                    <div class="stat-text">
                        <h4>4.9/5</h4>
                        <p>Rating Customer</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================
         HOW IT WORKS
    ========================================== -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <span class="section-label">Cara Pesan</span>
                <h2 class="section-title">Semudah 1, 2, 3!</h2>
                <p class="section-desc">Pesan makanan favoritmu dalam hitungan menit. Tanpa perlu login atau registrasi.</p>
            </div>

            <div class="steps-grid">
                <div class="step-card animate-on-scroll">
                    <div class="step-number pink"><i class="fas fa-store"></i></div>
                    <h3>Pilih Vendor</h3>
                    <p>Pilih dari berbagai vendor makanan dan minuman terbaik yang tersedia.</p>
                </div>
                <div class="step-card animate-on-scroll">
                    <div class="step-number cream"><i class="fas fa-clipboard-list"></i></div>
                    <h3>Pilih Menu</h3>
                    <p>Pilih menu favoritmu, atur jumlah pesanan sesuai keinginan.</p>
                </div>
                <div class="step-card animate-on-scroll">
                    <div class="step-number brown"><i class="fas fa-credit-card"></i></div>
                    <h3>Bayar Online</h3>
                    <p>Bayar via Virtual Account atau QRIS. Mudah, aman, dan cepat.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================
         FEATURED VENDORS
    ========================================== -->
    <section class="featured-vendors" id="vendors">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <span class="section-label">Vendor Kami</span>
                <h2 class="section-title">Vendor Pilihan Terbaik</h2>
                <p class="section-desc">Berbagai pilihan makanan dan minuman dari vendor terpercaya siap diantar untukmu.</p>
            </div>

            <div class="vendors-grid">
                <div class="vendor-card animate-on-scroll">
                    <img src="{{ asset('images/landing/nasi-goreng.png') }}" alt="Warung Nusantara" class="vendor-card-img">
                    <div class="vendor-card-body">
                        <span class="vendor-tag"><i class="fas fa-fire"></i> Popular</span>
                        <h3>Warung Nusantara</h3>
                        <p>Sajian masakan Indonesia autentik — Nasi Goreng, Mie Ayam, Ayam Geprek, dan banyak lagi.</p>
                        <a href="{{ route('customer.order.index') }}" class="btn-small">
                            Pesan Sekarang <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <div class="vendor-card animate-on-scroll">
                    <img src="{{ asset('images/landing/coffee-scene.png') }}" alt="Kopi Senja" class="vendor-card-img">
                    <div class="vendor-card-body">
                        <span class="vendor-tag"><i class="fas fa-coffee"></i> Best Seller</span>
                        <h3>Kopi Senja</h3>
                        <p>Kopi premium, croissant hangat, dan suasana senja yang menenangkan dalam setiap tegukan.</p>
                        <a href="{{ route('customer.order.index') }}" class="btn-small">
                            Pesan Sekarang <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================
         CTA SECTION
    ========================================== -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-box animate-on-scroll">
                <div class="cta-content">
                    <h2>Anda Pemilik Usaha Makanan?</h2>
                    <p>Bergabunglah sebagai vendor dan jangkau lebih banyak pelanggan. Kelola menu, lihat pesanan, dan terima pembayaran secara digital.</p>
                </div>
                <div class="cta-buttons">
                    <a href="{{ route('login') }}" class="btn-white">
                        <i class="fas fa-sign-in-alt"></i> Login sebagai Vendor
                    </a>
                    <a href="{{ route('customer.order.index') }}" class="btn-outline-white">
                        <i class="fas fa-shopping-bag"></i> Pesan sebagai Customer
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================
         FOOTER
    ========================================== -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand"><i class="fas fa-utensils"></i> FoodOrder</div>
                    <p>Platform pemesanan makanan online yang mudah dan aman. Pesan dari vendor favoritmu, bayar via Virtual Account atau QRIS.</p>
                </div>
                <div>
                    <h4>Navigation</h4>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#how-it-works">Cara Pesan</a></li>
                        <li><a href="#vendors">Vendor</a></li>
                        <li><a href="{{ route('customer.order.index') }}">Order Now</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Vendor Area</h4>
                    <ul>
                        <li><a href="{{ route('login') }}">Login Vendor</a></li>
                        <li><a href="{{ route('login') }}">Kelola Menu</a></li>
                        <li><a href="{{ route('login') }}">Dashboard</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} FoodOrder — Payment Gateway Workshop. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- ========================================
         JAVASCRIPT
    ======================================== -->
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });

        // Scroll animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('visible');
                    }, index * 100);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
