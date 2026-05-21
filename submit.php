<?php

date_default_timezone_set('Asia/Kolkata');

$name = isset($_POST['name']) ? $_POST['name'] : 'Unknown';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$campaign = isset($_POST['campaign']) ? $_POST['campaign'] : '';

$data = array(
    "name" => $name,
    "email" => $email,
    "phone" => $phone,
    "campaign" => $campaign,
    "timestamp" => date("Y-m-d H:i:s")
);

$json_data = json_encode($data, JSON_PRETTY_PRINT);

$file_name = $name . "_" . time() . ".json";

$temp_file = "/tmp/" . $file_name;

file_put_contents($temp_file, $json_data);

$bucket_name = "capstone-project-milestone-v";

// FIXED: Added HOME=/tmp to give the AWS CLI a valid runtime working directory
$command = "HOME=/tmp /usr/bin/aws s3 cp \"$temp_file\" \"s3://$bucket_name/logs/$file_name\" > /var/www/html/aws_error.txt 2>&1";

shell_exec($command);

echo "
<!DOCTYPE html>
<html>
<head>
<title>Success</title>
<style>
body{
    font-family:Arial;
    background:linear-gradient(135deg,#1e3c72,#2a5298);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}
.box{
    background:white;
    padding:40px;
    border-radius:20px;
    text-align:center;
    width:420px;
}
h1{
    color:green;
    margin-bottom:20px;
}
a{
    display:inline-block;
    margin-top:20px;
    background:#1e3c72;
    color:white;
    padding:12px 20px;
    text-decoration:none;
    border-radius:10px;
}
</style>
</head>
<body>
<div class='box'>
<h1>Registration Successful</h1>
<p>Campaign data uploaded to S3 successfully.</p>
<p><b>Name:</b> $name</p>
<p><b>Campaign:</b> $campaign</p>
<a href='index.html'>Back to Home</a>
</div>
</body>
</html>
";

?>

