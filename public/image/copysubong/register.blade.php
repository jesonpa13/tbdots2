<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TB DOTS SYSTEM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(120deg, #D3EE98, #A0D683);
        }

        .container {
            display: flex;
            max-width: 1200px;
            width: 100%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
            background-color: #fff;
        }

        /* Left Branding Side */
        .left-side {
            flex: 1;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px;
            color: #fff;
            background-blend-mode: multiply;
            background-color: rgba(0, 128, 0, 0.4);
        }
        
        .logo {
            background: rgba(255, 255, 255, 0.7);
            padding: 20px;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        /* Right Form Side */
        .right-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: rgba(240, 255, 250, 0.8);
        }

        .form-container {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .form-container h1 {
            font-size: 1.75rem;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .register-button {
            background-color: #34a853;
            color: #fff;
            font-size: 1rem;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s ease;
        }

        .register-button:hover {
            background-color: #2c8e47;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .left-side {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Left Side for Branding -->
        <div class="left-side">
            <div class="logo">
                <img src="image/DOH.png" alt="TB DOTS Logo" width="500">
            </div>
            <h2>Welcome to TB DOTS</h2>
        </div>

        <!-- Right Side for Form -->
        <div class="right-side">
            <div class="form-container">
                <h1>Sign Up Here</h1>
                <form>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">    
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <button type="submit" class="register-button">Register</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
