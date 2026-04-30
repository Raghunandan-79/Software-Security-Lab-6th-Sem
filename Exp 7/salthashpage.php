<?php
$conn = new mysqli("127.0.0.1","root","","test");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

/* ---------- CREATE USER (RUN ONLY ONCE) ---------- */
//f(isset($_GET['create'])){
    //$username = "diya";
    //$password = "diya123";

   // $hash = password_hash($password, PASSWORD_DEFAULT);

   // $stmt = $conn->prepare("INSERT INTO login (username, password) VALUES (?, ?)");
   // $stmt->bind_param("ss", $username, $hash);
   // $stmt->execute();
//}
/*
   // echo "User created successfully";
//}
/* ------------------------------------------------ */


/* ---------------- LOGIN ---------------- */

if(isset($_POST['login'])){

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT password FROM login WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if($row = $result->fetch_assoc()){

        if(password_verify($password, $row['password'])){
            $message = "<p style='color:green;text-align:center;'>Login Successful</p>";
        } else {
            $message = "<p style='color:red;text-align:center;'>Wrong Password</p>";
        }

    } else {
        $message = "<p style='color:red;text-align:center;'>User Not Found</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>

<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: Arial, sans-serif;
    background: #eef2f7;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Card */
.container {
    background: #ffffff;
    padding: 30px 25px;
    width: 340px;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    text-align: center;
}

/* Heading */
h2 {
    margin-bottom: 10px;
}

/* Message */
.message {
    font-weight: bold;
    margin-bottom: 15px;
}
.success { color: green; }
.error { color: red; }

/* Inputs */
input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
}

input:focus {
    border-color: #2d89ef;
    outline: none;
}

/* Button */
button {
    width: 100%;
    padding: 12px;
    background: #2d89ef;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 15px;
    cursor: pointer;
    margin-top: 10px;
}

button:hover {
    background: #1c6ed5;
}
</style>
</head>

<body>

<div class="container">
    <h2>Login</h2>

    <!-- dynamic message -->
    <?php echo $message; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit" name="login">Login</button>
    </form>
</div>

</body>
</html>