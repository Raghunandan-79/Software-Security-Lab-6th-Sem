<?php

$conn = mysqli_connect("localhost","root","","test1");

if(!$conn){
    die("Database Connection Failed");
}

// 🔹 REGISTER
if(isset($_POST['register'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Generate salt
    $salt = bin2hex(random_bytes(16));

    // Hash password + salt
    $hashed_password = hash('sha256', $password . $salt);

    $stmt = mysqli_prepare($conn,
        "INSERT INTO user (username, password, salt) VALUES (?, ?, ?)");

    mysqli_stmt_bind_param($stmt, "sss", $username, $hashed_password, $salt);

    if(mysqli_stmt_execute($stmt)){
        echo "<p style='color:green;'>User Registered Successfully</p>";
    } else {
        echo "<p style='color:red;'>Username already exists</p>";
    }
}

// 🔹 LOGIN
if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn,
        "SELECT password, salt FROM user WHERE username = ?");

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($result)){

        $stored_hash = $row['password'];
        $salt = $row['salt'];

        // Hash input password with stored salt
        $login_hash = hash('sha256', $password . $salt);

        if($login_hash === $stored_hash){
            echo "<p style='color:green;'>Login Successful</p>";
        } else {
            echo "<p style='color:red;'>Invalid Password</p>";
        }

    } else {
        echo "<p style='color:red;'>User Not Found</p>";
    }

    // Prevent XSS
    echo "<p>Welcome " . htmlspecialchars($username) . "</p>";
}

?>

<!DOCTYPE html>
<html>
<head>
<title>SHA256 Auth System</title>
</head>
<body>

<h2>Register</h2>
<form method="POST">
    <input type="text" name="username" placeholder="Enter Username" required><br><br>
    <input type="password" name="password" placeholder="Enter Password" required><br><br>
    <button type="submit" name="register">Register</button>
</form>

<hr>

<h2>Login</h2>
<form method="POST">
    <input type="text" name="username" placeholder="Enter Username" required><br><br>
    <input type="password" name="password" placeholder="Enter Password" required><br><br>
    <button type="submit" name="login">Login</button>
</form>

</body>
</html>
