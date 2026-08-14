<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #f5f6f8;
        }

        .left-panel {
            background-color: #ff9800;
            height: 100vh;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .left-panel .brand-block {
            text-align: center;
            max-width: 280px;
        }

        .left-panel .brand-logo {
            width: 120px;
            height: 120px;
            margin: 0 auto 24px auto;
            background: #ffffff;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #ff9800;
            border: 2px solid #ffffff;
        }

        .left-panel h2 {
            margin: 0 0 12px 0;
            font-size: 32px;
            line-height: 1.1;
            font-weight: 700;
            color: white;
        }

        .left-panel p {
            margin: 0;
            color: rgba(255,255,255,0.92);
            font-size: 15px;
            line-height: 1.7;
        }

        .right-panel {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f5f6f8;
        }

        .login-card {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 42px 38px;
            width: 100%;
            max-width: 420px;
            border: 1px solid #e6e8eb;
        }

        .logo-placeholder {
            width: 140px;
            height: 70px;
            background-color: #e0e0e0;
            margin: 0 auto 30px auto;
            border-radius: 8px;
        }

        .login-title {
            text-align: center;
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 28px;
            color: #111;
        }

        .form-control {
            font-size: 14px;
            padding: 12px 14px;
            border: 1px solid #dde2e8;
            border-radius: 10px;
            box-shadow: none !important;
            margin-bottom: 16px;
        }

        .form-control:focus {
            border-color: #f57c00;
            box-shadow: none !important;
        }

        .btn-login {
            width: 100%;
            background: #f57c00;
            border: none;
            color: white;
            font-size: 15px;
            padding: 12px 0;
            border-radius: 10px;
            transition: background-color 0.2s ease;
            font-weight: 700;
            margin-top: 10px;
            cursor: pointer;
        }

        .btn-login:hover {
            background: #ff9800;
        }

        .alert-error {
            background-color: #fff2f0;
            border: 1px solid #f5c6cb;
            color: #a83326;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        @media (max-width: 992px) {
            .left-panel {
                display: none;
            }
            .right-panel {
                height: 100vh;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0 h-100">
            
            <!-- Panel Izquierdo (Naranja) -->
            <div class="col-lg-6 left-panel">
                <!-- Espacio para branding o imagen -->
            </div>
            
            <!-- Panel Derecho (Formulario) -->
            <div class="col-lg-6 right-panel">
                
                <div class="login-card">
                    <!-- Logo -->
                    <div class="text-center mb-3">
                        <a href="{{ route('dashboard') }}">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="max-width:180px; height:auto; display:inline-block;">
                        </a>
                    </div>
                    
                    <!-- Título -->
                    <h2 class="login-title">Iniciar sesión</h2>
                    
                    <!-- Errores -->
                    @if ($errors->any())
                        <div class="alert-error">
                            {{ $errors->first('login') }}
                        </div>
                    @endif
                    
                    <!-- Formulario -->
                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        
                        <label for="login" class="form-label">Usuario</label>
                        <input 
                            type="text" 
                            id="login"
                            name="login" 
                            class="form-control"
                            placeholder="Ingrese su usuario"
                            value="{{ old('login') }}"
                            required
                            autofocus
                        >
                        
                        <label for="password" class="form-label">Contraseña</label>
                        <input 
                            type="password" 
                            id="password"
                            name="password" 
                            class="form-control"
                            placeholder="Ingrese su contraseña"
                            required
                        >
                        
                        <button type="submit" class="btn-login">Iniciar Sesión</button>
                    </form>
                </div>
                
            </div>
            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>