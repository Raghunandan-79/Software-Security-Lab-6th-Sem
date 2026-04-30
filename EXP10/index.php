<!DOCTYPE html>
<html>
<head>
<title>Secure Login</title>

<style>

body{
background:#121921;
font-family:Arial;
display:flex;
justify-content:center;
align-items:center;
height:100vh;
}

.login-box{
background:white;
padding:40px;
border-radius:10px;
box-shadow:0px 0px 15px rgba(0,0,0,0.3);
width:320px;
}

h2{
text-align:center;
margin-bottom:20px;
}

input{
width:100%;
padding:10px;
margin:10px 0;
border-radius:5px;
border:1px solid gray;
}

button{
width:100%;
padding:10px;
background:#007BFF;
color:white;
border:none;
border-radius:5px;
font-size:16px;
cursor:pointer;
}

button:hover{
background:#0056b3;
}

</style>

</head>

<body>

<div class="login-box">

<h2>Secure Login</h2>

<form method="POST" action="login.php">

<input type="text" name="username" placeholder="Username" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit">Login</button>

</form>

</div>

</body>
</html>
