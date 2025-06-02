<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiPTA</title>
    <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
        }
        .login-box {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 350px;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .input-group {
            text-align: left;
            margin-bottom: 15px;
        }
        label {
            font-size: 14px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .password-note {
            font-size: 12px;
            color: gray;
            margin-bottom: 10px;
        }
        .forgot-password {
            text-align: right;
            font-size: 12px;
            margin-bottom: 10px;
        }
        .forgot-password a {
            color: #007bff;
            text-decoration: none;
        }
        .login-button {
            width: 100%;
            padding: 10px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .login-button:hover {
            background-color: #333;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>SiPTA</h1>
        <div class="login-box">
            <h2>Welcome</h2>
            <p>Please log in to continue</p>

            {{-- Menampilkan pesan error jika email atau password salah --}}
            @if ($errors->any())
                <div class="error-message">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                        <button type="button" id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: none; background: none; cursor: pointer;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <p class="password-note">It must be a combination of minimum letters, numbers, and symbols.</p>
                <div class="forgot-password">
                    <a href="{{ route('password.request') }}">Forgot Password?</a>
                </div>
                <button type="submit" class="login-button">Log In</button>
            </form>
        </div>
    </div>
</body>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        if(togglePassword && password) {
            togglePassword.addEventListener('click', function (e) {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        }
    </script>
</html>
