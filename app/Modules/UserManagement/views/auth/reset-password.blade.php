<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SiPTA</title>
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
        .reset-box {
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
        .submit-button {
            width: 100%;
            padding: 10px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .submit-button:hover {
            background-color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reset Password</h1>
        <div class="reset-box">
            <p>Enter your new password.</p>

            @if (session('status'))
                <p style="color: green;">{{ session('status') }}</p>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Display error for password mismatch -->
                @error('password')
                    <div style="color: red;">
                        {{ $message }}
                    </div>
                @enderror

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" value="{{ old('email', $email ?? '') }}" placeholder="Enter your email" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="password" placeholder="Enter your new password" required>
                        <button type="button" id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: none; background: none; cursor: pointer;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="input-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm your new password" required>
                        <button type="button" id="togglePasswordConfirm" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: none; background: none; cursor: pointer;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="submit-button">Reset Password</button>
            </form>
        </div>
    </div>
    <script>
        function togglePasswordVisibility(buttonId, inputId) {
            const toggleBtn = document.querySelector(buttonId);
            const input = document.querySelector(inputId);
            if(toggleBtn && input) {
                toggleBtn.addEventListener('click', function (e) {
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('fa-eye');
                    this.querySelector('i').classList.toggle('fa-eye-slash');
                });
            }
        }
        togglePasswordVisibility('#togglePassword', '#password');
        togglePasswordVisibility('#togglePasswordConfirm', '#password_confirmation');
    </script>
</body>
</html>
