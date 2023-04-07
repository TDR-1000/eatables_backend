<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="forgot.php"  method="post">
        <input type="email" name="email" id="email">
        <input type="submit" name="submit">
    </form>
    <?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    if (isset($_POST['submit'])) {
session_start();

require 'vendor/autoload.php';
 
$mail = new PHPMailer(true);
$mail->SMTPDebug = 4;                                      
    $mail->isSMTP();                                           
    $mail->Host       = 'smtp.gmail.com;';                   
    $mail->SMTPAuth   = true;                            
    $mail->Username   = 'eatables.bitdrag@gmail.com';                
    $mail->Password   = 'eyxkaivssngazwha';                       
    $mail->SMTPSecure = 'ssl';                             
    $mail->Port       = 465; 
 

// Include database connection code
$con = new mysqli("localhost", "root", "", "eatables");


  // Get email address entered by user
  $email = $_POST['email'];

  // Validate email address
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Invalid email address.';
    header('Location: login.php');
    exit();
  }

  // Check if email exists in database
  $stmt ="SELECT * FROM user WHERE email = '$email'";
  $res=$con->query($stmt);
  $user=$res->fetch_assoc();
  $name=$user['uname'];
  if (!$user) {
    $_SESSION['error'] = 'Email not found.';
    header('Location: forgot.php');
    exit();
  }

  // Generate unique token
  $token = bin2hex(random_bytes(32));

  // Store token in database with expiration time
  //have to check the expiry
  $stmt = $con->prepare('UPDATE user SET reset_token = ?, reset_expiration = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ? and uname= ?');
  $stmt->execute([$token, $email, $name]);

  // Send email with reset password link
  
  $mail->setFrom('eatables.bitdrag@gmail.com', 'Name');          
  $mail->addAddress($email);
  $reset_link = 'https://localhost/eatables_backend/reset_password.php?token=' . $token;
  $mail->isHTML(false);                                 
  $mail->Subject = 'PASSWORD RESET LINK';
  $mail->Body    = "Click the link below to reset your password:\n\n" . $reset_link;
  $mail->AltBody = 'nill';

  echo "Mail has been sent successfully!";

  
 

  if ($mail->send()) {
    //give alert
    $_SESSION['success'] = 'Password reset link sent to your email.';
    echo"<script>alert('PASSWORD RESET LINK SEND TO THE MAIL')</script>";
    echo"<script>window.location.href='login.php'</script>";
    exit();
  } else {
    $_SESSION['error'] = 'Error sending email. Please try again.';
    header('Location: forgot.php');
    exit();
  }
}
?>

</body>
</html>