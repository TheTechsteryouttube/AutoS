<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="../assets/css/style.css"> <!-- Use your main CSS -->
  <style>
    .otp-container {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    .otp-box {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 30px;
        width: 400px;
        text-align: center;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
    }

    .otp-box header {
        color: #fff;
        font-size: 26px;
        margin-bottom: 20px;
    }

    .otp-box input[type="email"] {
        font-size: 15px;
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        height: 50px;
        width: 100%;
        padding: 0 15px;
        border: none;
        border-radius: 10px;
        outline: none;
        margin-bottom: 20px;
        transition: .2s ease;
    }

    .otp-box input[type="email"]:hover,
    .otp-box input[type="email"]:focus {
        background: rgba(255, 255, 255, 0.25);
    }

    .otp-box input[type="submit"] {
        font-size: 15px;
        font-weight: 500;
        color: black;
        height: 45px;
        width: 100%;
        border: none;
        border-radius: 30px;
        outline: none;
        background: rgba(255, 255, 255, 0.7);
        cursor: pointer;
        transition: .3s ease-in-out;
    }

    .otp-box input[type="submit"]:hover {
        background: rgba(255, 255, 255, 0.5);
        box-shadow: 1px 5px 7px 1px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="otp-container">
      <form class="otp-box" action="sendOtp.php" method="post">
        <header>Forgot Password</header>
        <input type="email" name="email" placeholder="Enter Your Registered Email" required>
        <input type="submit" value="Send OTP">
      </form>
    </div>
  </div>
</body>
</html>
