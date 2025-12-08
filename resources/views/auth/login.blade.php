<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIM KLINIK</title>
    <link rel="icon" href="{{ asset('template/assets/img/kaiadmin/logo.png') }}" type="image/x-icon" />
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-dark: #1e293b;   
            --primary-light: #334155; 
            --accent-green: #4ade80;  
            --bg-page: #f1f5f9;       
            --text-grey: #64748b;
            --error-red: #ef4444; /* Warna untuk error */
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
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(30, 41, 59, 0.15);
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
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-light));
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0 40px;
            text-align: center;
            border-top-right-radius: 100px;
            border-bottom-right-radius: 100px;
            z-index: 10;
        }

        .logo-area {
            margin-bottom: 20px;
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
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 15px 15px 15px 45px;
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

        /* Style khusus jika terjadi error */
        .input-group input.is-invalid {
            border-color: var(--error-red);
            background-color: #fef2f2;
        }

        .error-message {
            color: var(--error-red);
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 42px; /* Disesuaikan dengan posisi input */
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

        /* Alert Box untuk General Error */
        .alert-box {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            width: 100%;
            border: 1px solid #fecaca;
        }

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
                    src="{{ asset('template/assets/img/kaiadmin/logo.png') }}"
                    alt="Logo Klinik"
                    height="80"
                /> 
            </div>
            <h1>SIM KLINIK</h1>
            <p>Sistem Informasi Manajemen Klinik. Silakan masuk untuk mengakses dashboard layanan kesehatan.</p>
            <button class="ghost">Info Layanan</button>
        </div>

        <div class="form-container">
            <div class="header-text">
                <h2>Selamat Datang</h2>
                <span>Masukkan email dan password Anda</span>
            </div>
            
            @if ($errors->any())
                <div class="alert-box">
                    <ul style="margin-left: 15px; margin-bottom: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.process') }}" style="width: 100%;" novalidate>
                @csrf
                
                <div class="input-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Contoh: user@klinik.com" 
                        value="{{ old('email') }}"
                        class="@error('email') is-invalid @enderror"
                        required 
                        autofocus
                    >
                    <i class="fa-solid fa-envelope"></i>
                    
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••" 
                        class="@error('password') is-invalid @enderror"
                        required
                    >
                    <i class="fa-solid fa-lock"></i>
                    
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                
                <button type="submit" class="login-btn">MASUK DASHBOARD</button>
            </form>
        </div>
    </div>

</body>
</html>