<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=0, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="description" content="{{config("app.site_name")}}">
    <meta name="author" content="{{config("app.site_name")}}">
    <link rel="shortcut icon" href="{{asset("admin")}}/images/favicon.ico">
    <title>Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(45deg, #0f0c29, #302b63, #24243e);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            color: #fff;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            transform: translateY(0);
            transition: all 0.3s ease;
        }
        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h2 {
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 10px;
            background: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .login-header p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
        }
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        .form-control {
            width: 100%;
            padding: 15px 20px 15px 45px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #fff;
            font-size: 15px;
            outline: none;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #8fd3f4;
            box-shadow: 0 0 15px rgba(143, 211, 244, 0.2);
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }
        .form-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.4);
            transition: all 0.3s ease;
        }
        .form-control:focus + .form-icon,
        .form-control:valid + .form-icon {
            color: #8fd3f4;
        }
        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%);
            border: none;
            border-radius: 10px;
            color: #1a1a2e;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(132, 250, 176, 0.3);
            margin-bottom: 20px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(132, 250, 176, 0.4);
        }
        .btn-login:active {
            transform: translateY(0);
        }
        .bottom-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }
        .bottom-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .bottom-links a:hover {
            color: #8fd3f4;
        }
        .invalid-feedback {
            color: #ff6b6b;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }
        .is-invalid {
            border-color: #ff6b6b !important;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>{{ config('app.name') }}</h2>
            <p>Please sign in to access your portal</p>
        </div>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="Mobile Number" required autofocus>
                <i class="fas fa-phone-alt form-icon"></i>
                @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="form-group">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required>
                <i class="fas fa-lock form-icon"></i>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <button type="submit" class="btn-login">
                {{ __('Login') }}
            </button>
            
            <div class="bottom-links">
                <a href="tel:{{config('app.app_phone')}}"><i class="fas fa-question-circle"></i> Password? Call {{config("app.app_phone")}}</a>
                <a href="{{route("register")}}">Register</a>
            </div>
        </form>
    </div>
</body>
</html>
