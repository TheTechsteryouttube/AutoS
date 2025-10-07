<?php
include("includes/db_connect.php");

if ($_SERVER["REQUEST_METHOD"] =="POST") 
{
    $name=$_POST['fname'];
    $username= $_POST['uname'];
    $email     = $_POST['email'];
    $password  = password_hash($_POST['passw'], PASSWORD_DEFAULT);

    // Check if username or email already exists
    $check = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = $con->query($check);

    if ($result->num_rows > 0) 
    {
        echo "<script>alert('User Already exist');window.location='CustomerLogin.php'</script>";	
    } 
    else 
    {
        $sql = "INSERT INTO users (name,username,email,password)VALUES ('$name', '$username', '$email', '$password')";

        if ($con->query($sql) === TRUE)
        {
            echo "<script>alert('customer details saved sucessfully!!');window.location='CustomerLogin.php'</script>";
        }
        else 
        {
            echo "Error: ". $con->error;
        }
    }

    $con->close();
}
?>
