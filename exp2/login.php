<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$conn = new mysqli("localhost", "root", "", "test1");
if ($conn->connect_error) die("DB Connection Failed");

if (!isset($_SESSION['attempts'])) $_SESSION['attempts'] = 0;

$message = "";
$queryPreview = "";
$attackDetected = false;
$success = false;

$mode = isset($_POST['mode']) ? "secure" : "vulnerable";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'])) {

    $_SESSION['attempts']++;

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Detect SQL injection patterns
    if (preg_match("/('|--|#|=|;|union|select|drop|insert|update)/i", $username)) {
        $attackDetected = true;
        $message = "Blocked: SQL Injection attempt detected";
    }

    // If attack detected, do not execute query
    if (!$attackDetected) {

        if ($mode === "vulnerable") {

            $queryPreview = "SELECT * FROM user WHERE username='$username' AND password='$password'";
            $result = $conn->query($queryPreview);

            if ($result && $result->num_rows > 0) {
                $message = "Access Granted: Welcome $username";
                $success = true;
            } else {
                $message = "Access Denied";
            }

        } else {

            $queryPreview = "SELECT * FROM users WHERE username=? AND password=?";

            if (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
                $message = "Blocked: Invalid username format";
            } else {

                $stmt = $conn->prepare($queryPreview);
                $stmt->bind_param("ss", $username, $password);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $safe_user = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
                    $message = "Access Granted: Welcome $safe_user";
                    $success = true;
                } else {
                    $message = "Access Denied";
                }

                $stmt->close();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Advanced Cyber Lab</title>

<style>

body{
    margin:0;
    font-family:Consolas, monospace;
    background:#0f2027;
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    color:#00ffcc;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.panel{
    background:rgba(0,0,0,0.6);
    backdrop-filter:blur(10px);
    padding:30px;
    border-radius:15px;
    width:520px;
    box-shadow:0 0 40px rgba(0,255,200,0.2);
}

h2{
    text-align:center;
    margin-bottom:10px;
}

input{
    width:100%;
    padding:10px;
    margin:8px 0;
    background:#111;
    border:1px solid #00ffcc;
    color:#00ffcc;
    border-radius:5px;
}

button{
    width:100%;
    padding:10px;
    margin-top:10px;
    background:#00ffcc;
    border:none;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#00ccaa;
}

.switch{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin:10px 0;
}

.status{
    padding:8px;
    margin:10px 0;
    border-radius:5px;
}

.success{
    background:#003322;
}

.error{
    background:#330000;
}

.warning{
    background:#332200;
    color:#ffcc00;
}

.query-box{
    background:#111;
    padding:10px;
    margin-top:10px;
    font-size:12px;
    border-radius:5px;
    color:#ccc;
    word-wrap:break-word;
}

.footer{
    margin-top:10px;
    font-size:11px;
    text-align:center;
    opacity:0.7;
}

</style>

</head>

<body>

<div class="panel">

<h2>CYBER SECURITY TEST CONSOLE</h2>

<form method="POST">

<input type="text" name="username" placeholder="Username" required>

<input type="password" name="password" placeholder="Password" required>

<div class="switch">
<label>Secure Mode</label>
<input type="checkbox" name="mode" value="secure"
<?php if($mode==="secure") echo "checked"; ?>>
</div>

<button type="submit">Execute Login</button>

</form>

<?php if($message!=""): ?>

<div class="status <?php echo $success?'success':'error'; ?>">
<?php echo $message; ?>
</div>

<?php endif; ?>

<?php if($attackDetected): ?>

<div class="status warning">
⚠ Suspicious Input Pattern Detected
</div>

<?php endif; ?>

<?php if($queryPreview!=""): ?>

<div class="query-box">
<strong>Executed Query:</strong><br>
<?php echo htmlspecialchars($queryPreview); ?>
</div>

<?php endif; ?>

<div class="footer">
Attempts: <?php echo $_SESSION['attempts']; ?> |
Mode: <?php echo strtoupper($mode); ?>
</div>

</div>

</body>
</html>
```
