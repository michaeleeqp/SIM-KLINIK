<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SIM Klinik</title>
    <link
      rel="icon"
      href="{{asset('template/assets/img/kaiadmin/logo.png')}}"
      type="image/x-icon"
    />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            /* Warna diambil dari referensi Dashboard */
            --primary-dark: #1e293b;   /* Warna Sidebar Gelap */
            --primary-light: #334155;  /* Warna Sidebar agak terang */
            --accent-green: #4ade80;   /* Aksen hijau (mirip logo di dashboard) */
            --bg-page: #f1f5f9;        /* Background halaman terang */
            --text-grey: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--bg-page);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 20px; /* Radius lebih modern, tidak terlalu bulat */
            box-shadow: 0 10px 25px rgba(30, 41, 59, 0.15); /* Bayangan halus */
            position: relative;
            overflow: hidden;
            width: 900px;
            max-width: 100%;
            min-height: 550px;
            display: flex;
        }

        /* --- Sisi Kiri (Branding Sidebar) --- */
        .toggle-container {
            width: 45%;
            /* Gradient Navy Gelap (Mengikuti Sidebar) */
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-light));
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0 40px;
            text-align: center;
            /* Lengkungan tetap ada tapi lebih subtle/elegan */
            border-top-right-radius: 100px;
            border-bottom-right-radius: 100px;
            z-index: 10;
        }

        /* Simulasi Logo di Panel Kiri */
        .logo-area {
            font-size: 50px;
            margin-bottom: 20px;
            color: var(--accent-green); /* Hijau logo */
        }

        .toggle-container h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .toggle-container p {
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .toggle-container button.ghost {
            background-color: transparent;
            border: 2px solid rgba(255,255,255,0.5);
            color: #fff;
            padding: 10px 40px;
            border-radius: 50px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s;
        }

        .toggle-container button.ghost:hover {
            background-color: #fff;
            color: var(--primary-dark);
            border-color: #fff;
        }

        /* --- Sisi Kanan (Form) --- */
        .form-container {
            width: 55%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0 60px;
            background-color: #fff;
        }

        .header-text {
            text-align: center;
            margin-bottom: 30px;
        }

        .header-text h2 {
            font-size: 32px;
            color: var(--primary-dark);
            font-weight: 700;
        }
        
        .header-text span {
            font-size: 14px;
            color: var(--text-grey);
        }

        .input-group {
            position: relative;
            width: 100%;
            margin: 15px 0;
        }

        .input-group label {
            font-size: 13px;
            color: var(--primary-dark);
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
        }

        .input-group input {
            background-color: #f8fafc; /* Abu sangat muda */
            border: 1px solid #e2e8f0;
            padding: 15px 15px 15px 45px; /* Padding kiri untuk ikon */
            width: 100%;
            border-radius: 12px;
            outline: none;
            color: var(--primary-dark);
            transition: 0.3s;
        }

        .input-group input:focus {
            border-color: var(--primary-dark);
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(30, 41, 59, 0.1);
        }

        /* Ikon Input (Sekarang di kiri agar lebih rapi) */
        .input-group i {
            position: absolute;
            left: 15px;
            top: 42px; /* Menyesuaikan posisi karena ada Label */
            color: var(--text-grey);
            font-size: 16px;
        }

        .options {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            color: var(--text-grey);
            gap: 5px;
        }

        .forgot-pass {
            color: var(--primary-dark);
            font-weight: 600;
            text-decoration: none;
        }

        button.login-btn {
            background-color: var(--primary-dark);
            color: #fff;
            font-size: 14px;
            padding: 15px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 4px 12px rgba(30, 41, 59, 0.2);
        }

        button.login-btn:hover {
            background-color: var(--primary-light);
            transform: translateY(-2px);
        }

        /* Responsif HP */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                width: 90%;
                height: auto;
            }
            .toggle-container {
                width: 100%;
                padding: 40px 20px;
                border-radius: 20px 20px 0 0;
            }
            .form-container {
                width: 100%;
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="toggle-container">
            <div class="logo-area">
                <img
                src="{{asset('template/assets/img/kaiadmin/logo.png')}}"
                alt="navbar brand"
                class="navbar-brand"
                height="80"
              /> 
            </div>
            <h1>SIM KLINIK</h1>
            <p>Sistem Informasi Manajemen Klinik. Silakan masuk untuk mengakses dashboard.</p>
            <button class="ghost">Info Layanan</button>
        </div>

        <div class="form-container">
            <div class="header-text">
                <h2>Selamat Datang</h2>
                <span>Masukkan kredensial Anda untuk akses sistem</span>
            </div>
            
            <form action="#" style="width: 100%;">
                <div class="input-group">
                    <label>Username / NIP</label>
                    <input type="text" placeholder="Contoh: dr.budi123" required>
                    <i class="fa-solid fa-user-doctor"></i>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" placeholder="••••••••" required>
                    <i class="fa-solid fa-lock"></i>
                </div>

                <div class="options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember">
                        <label for="remember" style="margin:0; font-weight:400;">Ingat Saya</label>
                    </div>
                    <a href="#" class="forgot-pass">Lupa Password?</a>
                </div>

                <button type="submit" class="login-btn">MASUK DASHBOARD</button>
            </form>
        </div>
    </div>

</body>
</html>