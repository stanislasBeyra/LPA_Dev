<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        .container {
            padding: 20px;
            background-color: #f0f2f5;
        }
        .card {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card-header {
            background-color: #008080;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .card-header h3 {
            margin: 0;
        }
        .card-body {
            padding: 20px;
        }
        .alert-info {
            padding: 15px;
            background-color: #e7f0ff;
            border: 1px solid #b8daff;
            border-radius: 4px;
            color: #31708f;
        }
        .card-footer {
            background-color: #f1f1f1;
            padding: 10px;
            text-align: center;
            font-size: 14px;
            color: #777;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Your account has been created successfully</h3>
            </div>
            <div class="card-body">
                <p>Hello {{ $user->firstname }} {{ $user->lastname }},</p>
                <p>We are thrilled to welcome you to the LPA app! Your journey with us starts here, and we want to ensure you have everything you need to get started seamlessly.</p>
                <p>Below are your exclusive login credentials:</p>
                <div class="alert-info">
                    <p><strong>Username:</strong> {{ $user->username }}</p>
                    <p><strong>Password:</strong> {{ $password }}</p>
                </div>
                <p>To begin exploring the full features of the LPA app, simply click the link below to access your personalized dashboard:</p>
                <p><a href="https://frontend-lpa.vercel.app">Access Your Dashboard</a></p>
                <p>Should you have any questions or need any support, our dedicated team is here for you. Don’t hesitate to reach out if you need any assistance.</p>
                <p>Thank you for choosing LPA. We’re excited to have you with us!</p>
            </div>

        </div>
    </div>
</body>
</html>
