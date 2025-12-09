<?php
session_start();

include "../Database/connect_to_db.php";

$success = "";
$error = "";
$username_error = "";
$useremail_error = "";
$userdob_error = "";
$usercity_error = "";
$userpassword_error = "";
$usercpassword_error = "";

if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['login'])){
    $useremail = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL,FILTER_SANITIZE_EMAIL);
    $userpassword = filter_input(INPUT_POST, 'userpassword', FILTER_UNSAFE_RAW);
    if(!empty($useremail) && !empty($userpassword)){
        $login_query = "SELECT user_id, name, email, password, role_id 
                        FROM users 
                        WHERE email = '$useremail' LIMIT 1";
        $result = mysqli_query($conn, $login_query);
        if(mysqli_num_rows($result) === 1){
            $user = mysqli_fetch_assoc($result);
            $isUserVerified = password_verify($userpassword, $user['password']);
            if($isUserVerified){
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role_id'] = $user['role_id'];
                header("Location: Dashboard/ad_dashboard.php");
              }else {
                // echo "$user";
                $error = "Invalid credentials";
              }
        }else {
            $error = "User not found";
        }
    }else{
         $error = "All fields are required";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    <title>Login Chat App</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
      body{
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      font-family: 'Jost', sans-serif;
      background: #CFF752;
      }
      .main{
        width: 600px;
        height: 500px;
        background: red;
        overflow: hidden;
        /* background: url("https://doc-08-2c-docs.googleusercontent.com/docs/securesc/68c90smiglihng9534mvqmq1946dmis5/fo0picsp1nhiucmc0l25s29respgpr4j/1631524275000/03522360960922298374/03522360960922298374/1Sx0jhdpEpnNIydS4rnN4kHSJtU1EyWka?e=view&authuser=0&nonce=gcrocepgbb17m&user=03522360960922298374&hash=tfhgbs86ka6divo3llbvp93mg4csvb38") no-repeat center/ cover; */
        background: #fff;
        border-radius: 10px;
        box-shadow: 5px 20px 50px #000;
      }
      #chk{
        display: none;
      }
      .signup{
        position: relative;
        width:100%;
        height: 100%;
        h1{
          color: #fff;
        font-size: 2.3em;
        justify-content: center;
        display: flex;
        margin: 30px;
        font-weight: bold;
        cursor: pointer;
        transition: .5s ease-in-out;
        }
      }
      .sign-up-form{
        display: grid;
        grid-template-columns: 1fr 1fr;
        padding: 0 20px; 
      }
      .sign-up-form button{
        grid-column: span 2;
      }
      .sign-up-form input , .sign-up-form select{
        width: 80%;
        height: 33px;
        color: #757474;
        background: #e0dede;
        justify-content: center;
        display: flex;
        margin: 15px auto;
        padding: 0 12px;
        border: none;
        outline: none;
        border-radius: 5px;
      }
      label{
        color: #fff;
        font-size: 2.2em;
        justify-content: center;
        display: flex;
        margin: 50px;
        font-weight: bold;
        cursor: pointer;
        transition: .5s ease-in-out;
      }
      input{
        width: 60%;
        height: 33px;
        background: #f8f8f8;
        justify-content: center;
        color: #000;
        display: flex;
        margin: 20px auto;
        padding: 0 12px;
        border: none;
        outline: none;
        border-radius: 5px;
      }
      input::placeholder {
        color: #bebdbd;
      }
      button{
        width: 60%;
        height: 40px;
        margin: 10px auto;
        justify-content: center;
        display: block;
        color: #fff;
        background: #000;
        font-size: 1em;
        font-weight: bold;
        margin-top: 30px;
        outline: none;
        border: none;
        border-radius: 5px;
        transition: 0.2s ease-in;
        cursor: pointer;
      }
      button:hover{
        background: #a3d313;
      }
      .signup{
        height: 460px;
        background: #CFF752;
        border-radius: 60% / 10%;
        transform: translateY(30px);
        transition: .8s ease-in-out;
      }
      .signup label{
        color: #000;
        transform: scale(.6);
      }

      .sign-up-form input,
      .sign-up-form select {
        background: #f8f8f8;
      }


      #chk:checked ~ .signup{
        transform: translateY(-250px);
      }
      #chk:checked ~ .signup label{
        transform: scale(1);	
      }
      #chk:checked ~ .login label{
        transform: scale(.6);
      }
    </style>
  </head>
  <body>
    <div class="main" >  
     <?php if ($success):
        ?>
            <div id="message" class=" fixed top-5 5 z-50 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-lg" role="alert">
                <strong class="font-bold">Success</strong>
                <span class="block sm:inline"><?= $success ?>.</span>
            </div>
        <?php endif; ?>

        <?php if ($error):
        ?>
            <div id="message" class="fixed top-5 5 z-50 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg shadow-lg" role="alert">
                <strong class="font-bold">Error</strong>
                <span class="block sm:inline"><?= $error ?>.</span>
            </div>
        <?php endif; ?>	
      <input type="checkbox" id="chk" aria-hidden="true">
        <div class="login">
          <form method="post">
            <label for="chk" aria-hidden="true"><a href="AD_login.php" class="text-black">Login</a></label>
            <input type="email" name="email" placeholder="Email">
            <input type="password" name="userpassword" placeholder="Password">
            <button type="submit" name="login">Login</button>
          </form>
        </div>

    </div> 
  </body>
</html>