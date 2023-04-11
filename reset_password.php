<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <link rel="stylesheet" href="styles/input.css">
  <link rel="stylesheet" href="styles/style.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://kit.fontawesome.com/08ae7c27bc.js" crossorigin="anonymous"></script>
  <link rel="shortcut icon" href="public/eatables.png" type="image/x-icon">
</head>

<body>

  <?php
  session_start();
  $con = new mysqli("localhost", "root", "", "eatables");
  if (!isset($_POST['submit'])) {

    $_SESSION['token'] = $_GET['token'];
    $token = $_SESSION['token'];

    $stmt = "SELECT * FROM user WHERE reset_token = '$token'";
    $res = $con->query($stmt);
    $user = $res->fetch_assoc();
    if ($user) {
  ?>
      <div class="bg-brand bg-img min-h-screen grid">
        <div class="flex flex-col items-center justify-center">
          <div class="flex flex-col items-center justify-center py-16 md:py-36 md:space-y-4 space-y-2">
            <div class="flex flex-col items-center pb-2 md:pb-4">
              <a href="login.php" class="text-5xl md:text-6xl font-colvet">
                eatables.
              </a>
              <p class="font-poppy text-sm md:text-md">Find your next favorite.</p>
            </div>
            <p class="font-poppy text-md md:text-lg">Promise me that you will never forgot this one!</p>
            <form action="reset_password.php" method="post" class="grid place-items-center md:grid-rows-2 grid-cols-1 gap-3 mx-4">
              <input type="text" name="pass" id="pass" class="hover:border-brand outline-none opacity-90 border-0 text-xl md:text-2xl px-10 py-3 md:px-16 md:py-4 placeholder:opacity-70 text-center placeholder:font-poppy bg-off-brand placeholder-color font-poppy hover:placeholder:-translate-y-20 placeholder:duration-[0.5s]" placeholder="password" type="text" name="username" autoComplete="off">
              <input type="text" name="cnfpass" id="cnfpass" class="hover:border-brand outline-none opacity-90 border-0 text-xl md:text-2xl px-10 py-3 md:px-16 md:py-4 placeholder:opacity-70 text-center placeholder:font-poppy bg-off-brand placeholder-color font-poppy hover:placeholder:-translate-y-20 placeholder:duration-[0.5s]" placeholder="confirm" type="text" name="username" autoComplete="off">
              <input type="submit" name="submit" class="py-[0.50rem] md:py-[0.70rem] tracking-wider px-9 md:px-12 text-xl font-poppy rounded-md duration-500" type="submit" name="submit" value="reset">
            </form>
          </div>

      <?php
    } else {
      echo "some error occured";
    }
  } else {

    $token = $_SESSION['token'];
    $pass = $_POST['pass'];
    $cnfpass = $_POST['cnfpass'];
    if ($pass != $cnfpass) {
      echo "<script>alert('PASSWORD MISSMATCH')</script>";
    } else {
      $password = password_hash($pass, PASSWORD_DEFAULT);
      $stmt = $con->prepare('UPDATE user SET password = ? WHERE reset_token = ?');
      $stmt->execute([$password, $token]);
      echo "<script>alert('PASSWORD CHANGED SUCCESFULLY')</script>";
      header('Location: http://localhost/eatables/login.php');
    }

    exit();
  }


      ?>

</body>

</html>