<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Koperasi - Platform Manajemen Koperasi Modern</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            max-width: 1200px;
            width: 90%;
            align-items: center;
        }

        .content-left {
            color: white;
            animation: slideInLeft 0.8s ease-out;
        }

        .content-left h1 {
            font-size: 3.5rem;
            line-height: 1.2;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .accent {
            color: #ffd700;
        }

        .content-left p {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 30px;
            opacity: 0.95;
        }

        .features {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 40px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 1rem;
        }

        .feature-icon {
            width: 30px;
            height: 30px;
            background: rgba(255, 215, 0, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideInRight 0.8s ease-out;
        }

        .card h2 {
            color: #667eea;
            margin-bottom: 30px;
            font-size: 1.8rem;
            text-align: center;
        }

        .auth-buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-register {
            background: #f0f0f0;
            color: #333;
            border: 2px solid #667eea;
        }

        .btn-register:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .or-divider {
            text-align: center;
            margin: 20px 0;
            color: #999;
            position: relative;
        }

        .or-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #ddd;
            z-index: 0;
        }

        .or-divider span {
            background: white;
            padding: 0 10px;
            position: relative;
            z-index: 1;
        }

        .dashboard-link {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background: #e8f4f8;
            border-radius: 8px;
            color: #667eea;
        }

        .dashboard-link a {
            font-weight: 600;
            text-decoration: none;
            color: #667eea;
        }

        .dashboard-link a:hover {
            text-decoration: underline;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .content-left h1 {
                font-size: 2.5rem;
            }

            .card {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content-left">
            <h1>Sistem <span class="accent">Koperasi</span></h1>
            <p>Platform manajemen koperasi modern yang dirancang untuk memudahkan pengelolaan anggota, simpanan, pinjaman, dan operasional koperasi Anda secara keseluruhan.</p>

            <div class="features">
                <div class="feature-item">
                    <div class="feature-icon">👥</div>
                    <span>Manajemen data anggota yang komprehensif</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">💰</div>
                    <span>Pengelolaan simpanan dan pinjaman</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">📊</div>
                    <span>Dashboard analitik realtim</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🔒</div>
                    <span>Keamanan data tingkat enterprise</span>
                </div>
            </div>
        </div>

        <div class="card">
            @auth
                <h2>Selamat Datang</h2>
                <div class="dashboard-link">
                    <p>Anda sudah login</p>
                    <a href="{{ url('/dashboard') }}" class="btn">Ke Dashboard</a>
                </div>
            @else
                <h2>Masuk ke Sistem</h2>
                <div class="auth-buttons">
                    <a href="{{ route('login') }}" class="btn btn-login">Masuk</a>
                    <div class="or-divider">
                        <span>atau</span>
                    </div>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-register">Daftar Akun Baru</a>
                    @endif
                </div>
                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; color: #666; font-size: 0.9rem; line-height: 1.6;">
                    <p><strong>Demo Account:</strong></p>
                    <p>Admin: admin@example.com</p>
                    <p>Password: password</p>
                    <hr style="margin: 10px 0; border: none; border-top: 1px solid #ddd;">
                    <p><strong>User: user@example.com</strong></p>
                    <p>Password: password</p>
                </div>
            @endauth
        </div>
    </div>
</body>
</html>

