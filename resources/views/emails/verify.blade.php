<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Your Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6fff8;
            color: #064420;
            padding: 40px;
        }
        .container {
            background: white;
            border: 1px solid #cce3dc;
            border-radius: 10px;
            padding: 30px;
            max-width: 600px;
            margin: auto;
        }
        h2 {
            color: #064420;
        }
        .btn {
            display: inline-block;
            background-color: #007BFF;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #666;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            width: 120px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="logo">
        <img src="https://i.postimg.cc/7P3bpYrB/Untitled-design1.png" alt="Medical App Logo">
    </div>
    <h2>👋 Hello {{ $user->name ?? 'User' }}</h2>
    <p>Thanks for joining <strong>Medical App</strong>.</p>
    <p>To complete your registration, please confirm your email by clicking the button below:</p>
    <a href="{{ $url }}" class="btn">✅ Confirm My Email</a>
    <p> {{$url}}</p>
    
    <p class="footer">If you did not create an account, no further action is required.</p>
    <p class="footer"><strong>Medical App Team 💊</strong></p>
</div>
</body>
</html>
