<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=0, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="description" content="{{config("app.site_name")}}">
    <meta name="author" content="{{config("app.site_name")}}">
    <link rel="shortcut icon" href="{{asset("admin")}}/images/favicon.ico">
    <title>Register</title>

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
            padding: 40px 20px;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .login-container {
            width: 100%;
            max-width: 500px;
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
            margin-bottom: 20px;
            position: relative;
        }
        .form-control {
            width: 100%;
            padding: 12px 20px 12px 45px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #fff;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
        }
        select.form-control {
            padding-left: 20px; /* Adjust padding since select might not need icon space if we don't have one, but we'll add one */
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }
        select.form-control option {
            color: #333;
            background: #fff;
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
            pointer-events: none;
        }
        .form-control:focus + .form-icon,
        .form-control:valid + .form-icon {
            color: #8fd3f4;
        }
        /* Fix for select icon */
        .select-wrapper {
            position: relative;
        }
        .select-wrapper .form-icon {
            z-index: 10;
        }
        .select-wrapper::after {
            content: '\f0d7';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.4);
            pointer-events: none;
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
            margin-top: 10px;
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
            text-align: center;
            font-size: 14px;
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
            <p>Create a new account</p>
        </div>
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="form-group">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Full Name" required autocomplete="name" autofocus>
                <i class="fas fa-user form-icon"></i>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="form-group">
                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="Mobile Number" required>
                <i class="fas fa-phone-alt form-icon"></i>
                @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="form-group">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="E-Mail Address" required autocomplete="email">
                <i class="fas fa-envelope form-icon"></i>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="form-group select-wrapper">
                @php
                    $university=\App\University::orderBy("name","asc")->get();
                @endphp
                <select name="university_id" id="university_id" required class="form-control @error('university_id') is-invalid @enderror">
                    <option value="" disabled selected>-- Select University --</option> 
                    @foreach ($university as $item)
                        <option value="{{$item->id}}" {{ old('university_id') == $item->id ? 'selected' : '' }}>{{$item->name}}</option> 
                    @endforeach
                </select>
                <i class="fas fa-university form-icon"></i>
                @error('university_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="form-group">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="new-password">
                <i class="fas fa-lock form-icon"></i>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="form-group">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                <i class="fas fa-lock form-icon"></i>
            </div>
            
            <button type="submit" class="btn-login">
                {{ __('Register') }}
            </button>
            
            <div class="bottom-links">
                <a href="{{route("login")}}">Already have an account? Sign In</a>
            </div>
        </form>
    </div>
</body>
</html>
