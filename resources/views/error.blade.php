<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .error-container {
            text-align: center;
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 4rem;
            color: #e74c3c;
            margin-bottom: 0;
        }
        p {
            font-size: 1.2rem;
            color: #333;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .subtitle{
            font-size: 12px;
            font-style: italic;
        }
    </style>
</head>
<body>
<div class="error-container">
    <h1>{{$title}}</h1>
    <p>{{$message}}</p>
    @if (!is_null($messageOriginal))
    <p class="subtitle">{{$messageOriginal}}</p>
    @endif
    <a class="btn" href="/login">Go Login</a>
</div>
</body>
</html>
