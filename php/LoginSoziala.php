<!DOCTYPE html>
<html>
<head>

    <?php include '../html/Head.html'?>
    <?php include 'DbConfig.php'?>
</head>
<body>
    <?php include '../php/Menus.php' ?>

    <?php
        if (isset($_SESSION["kautotua"]) && $_SESSION["kautotua"] == "BAI") {
            echo "<script> window.location.href = 'Layout.php';</script>";
            exit();
        }
    ?>

    <?php
      require_once '../loginSozialaAPI/vendor/autoload.php';

      // init configuration
      $clientID = '778249677064-r470r7q9nbv64adootkjivd935m1fmon.apps.googleusercontent.com';
      $clientSecret = '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';
      $redirectUri = 'https://sw.ikasten.io/~emendibil002/WSMigratua/php/LoginSoziala.php';

      // create Client Request to access Google API
      $client = new Google_Client();
      $client->setClientId($clientID);
      $client->setClientSecret($clientSecret);
      $client->setRedirectUri($redirectUri);
      $client->addScope("email");
      $client->addScope("profile");

      // authenticate code from Google OAuth Flow
      if (isset($_GET['code'])) {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token['access_token']);

        // get profile info
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        $email =  $google_account_info->email;
        $name =  $google_account_info->name;
        include 'IncreaseGlobalCounter.php';
        $_SESSION["kautotua"]= "BAI";
        $_SESSION["eposta"] = $email;
        $_SESSION["mota"] = 1;

        header("location: HandlingQuizesAjax.php");

        // now you can use this profile info to create account in your website and make user logged in.
      } else {
        echo "<script> window.location.href = '".$client->createAuthUrl()."';</script>";
      }
      ?>
    <section class="main" id="s1" style="display: flex">

    </section>
    <?php include '../html/Footer.html' ?>
</body>
</html>
