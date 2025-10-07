<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>AutoStream |Login and Registration</title>
</head>
<body>
 <div class="wrapper">
    <nav class="nav">
        <div class="nav-logo">
            <!--<p>AutoStream</p>-->
        </div>
        <div class="nav-menu" id="navMenu">
            <ul>
                <!--<li><a href="#" class="link active">Home</a></li>
                <li><a href="#" class="link">Blog</a></li>
                <li><a href="#" class="link">Services</a></li>
                <li><a href="#" class="link">About</a></li>-->
            </ul>
        </div>
        <div class="nav-button">
           <!--<button class="btn white-btn" id="loginBtn" onclick="login()">Sign In</button>
            <button class="btn" id="registerBtn" onclick="register()">Sign Up</button>-->
        </div>
        <div class="nav-menu-btn">
            <i class="bx bx-menu" onclick="myMenuFunction()"></i>
        </div>
    </nav>

<!----------------------------- Form box ----------------------------------->    
    <div class="form-box">
        
        <!------------------- login form -------------------------->

        <div class="login-container" id="login">
            <form action="adminlogin.php" method="POST">
            <div class="top">
                <header>ADMINISTRATOR LOGIN</header>
            </div>
            <div class="input-box">
                <input type="text" class="input-field" name="user" placeholder="Username" required>
                <i class="bx bx-user"></i>
            </div>
            <div class="input-box">
                <input type="password" class="input-field" name="pass" placeholder="Password" required>
                <i class="bx bx-lock-alt"></i>
            </div>
            <div class="input-box">
                <input type="submit" class="submit" value="Sign In">
            </div>
            <div class="two-col">
                <div class="one">
                    <!--<input type="checkbox" id="login-check">
                    <label for="login-check"> Remember Me</label>-->
                </div>
                <div class="two">
                   <!-- <a href="otp/forgotPassword.php">Forgot password?</a> -->
                </div>
            </div>
            <div class="top">
                <!--<span>Don't have an account? <a href="#" onclick="register()">Sign Up</a></span>-->
                </div>
            </form>
        </div>
</body>
</html>