<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>503 | Maintenance</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fdfcfb, #e2d1c3);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .error-container {
            text-align: center;
            max-width: 600px;
            padding: 40px;
            background: #fff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }

        .error-code {
            font-size: 50px;
            color: #f0ad4e;
            margin: 0;
        }

        .error-title {
            font-size: 28px;
            margin: 10px 0;
        }

        .icon {
            font-size: 28px;
            margin-right: 8px;
        }

        .error-message {
            font-size: 16px;
            color: #555;
            margin-bottom: 30px;
        }

        .error-image {
            max-width: 100%;
            height: auto;
            margin-bottom: 30px;
        }

        .btn-home {
            padding: 10px 20px;
            background-color: #f0ad4e;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-home:hover {
            background-color: #ec971f;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1 class="error-code">Server Dalam Perbaikan</h1>
        <p class="error-message">
            Maaf, saat ini website sedang dalam pemeliharaan. Kami akan segera kembali.
        </p>
        <img src="{{ asset('error-image/error-503.png') }}" alt="Maintenance Image" class="error-image">

    </div>


</body>
</html>
