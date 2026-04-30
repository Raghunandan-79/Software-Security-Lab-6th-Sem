<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost","webuser","password123","securitylab");

if($conn->connect_error){
die("Database connection failed");
}

$username=$_POST['username'];
$password=$_POST['password'];

$ip=$_SERVER['REMOTE_ADDR'];
$time=date("Y-m-d H:i:s");

$logfile="/var/www/html/access.log";

$sql="SELECT * FROM users WHERE username='$username'";
$result=$conn->query($sql);

if($result->num_rows==0){

$status="FAILED";

file_put_contents($logfile,"$time | IP: $ip | Username: $username | Status: $status\n",FILE_APPEND);

echo "<script>alert('User not found'); window.location.href='index.php';</script>";

exit();

}

$row=$result->fetch_assoc();

if($row['account_locked']==1){

$status="ACCOUNT LOCKED";

file_put_contents($logfile,"$time | IP: $ip | Username: $username | Status: $status\n",FILE_APPEND);

echo "<script>alert('Account Locked! Contact Admin'); window.location.href='index.php';</script>";

exit();

}

if($row['password']==$password){

$conn->query("UPDATE users SET failed_attempts=0 WHERE username='$username'");

$status="SUCCESS";

file_put_contents($logfile,"$time | IP: $ip | Username: $username | Status: $status\n",FILE_APPEND);

echo "<script>alert('Login Successful'); window.location.href='index.php';</script>";

}

else{

$attempts=$row['failed_attempts']+1;

$conn->query("UPDATE users SET failed_attempts=$attempts WHERE username='$username'");

$remaining=5-$attempts;

if($attempts>=5){

$conn->query("UPDATE users SET account_locked=1 WHERE username='$username'");

$status="ACCOUNT LOCKED";

file_put_contents($logfile,"$time | IP: $ip | Username: $username | Status: $status\n",FILE_APPEND);

echo "<script>alert('Account Locked After 5 Failed Attempts'); window.location.href='index.php';</script>";

}

else{

$status="FAILED";

file_put_contents($logfile,"$time | IP: $ip | Username: $username | Status: $status\n",FILE_APPEND);

echo "<script>alert('Login Failed. Attempts Remaining: $remaining'); window.location.href='index.php';</script>";

}

}

?>
