<?php
$whales_name = "Forbidden Access";
$title = "<title>Whales Market | $whales_name </title>";


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo $title; ?>
    <style>
        html {
            background-color: rgb(239, 242, 245);
        }

        .container {
            height: fit-content;
            display: block;
            justify-content: center;
            align-items: center;
            box-sizing: border-box;
            padding-bottom: 50px;
            padding-top: 0;
        }

        .login-div {
            position: relative;
            height: fit-content;
            width: calc(100vw - 70%);
            background-color: white;
            justify-content: center;
            align-items: center;
            margin: auto;
            border-radius: 0.5rem;
            box-shadow: 0 1px 1px 0 rgba(48, 48, 48, 0.3),
                0 1px 3px 1px rgba(48, 48, 48, 0.15);
            box-sizing: border-box;
            padding: 10px;
        }

        form {
            margin: 5%;
            justify-content: center;
            align-content: center;
            justify-items: center;
            align-items: center;
            text-align: center;
        }

        .img-name {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .logo {
            display: flex;
            text-align: center;
            justify-content: center;
            align-items: center;
            margin-bottom: 0;
        }

        .logo>img {
            width: 150px;
            height: 150px;
            margin-bottom: 0;
        }

        h1 {
            text-align: center;
            word-spacing: 7px;
            font-size: 2.8rem;
            margin-top: 0;
        }

        .w {
            text-align: center;
            color: #0080ff;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-weight: bolder;
            letter-spacing: 5;
        }

        .m {
            text-align: center;
            color: #0b3996;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-weight: bolder;
            letter-spacing: 3;
        }

        h3 {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 1.1rem;
            color: darkred;
            margin: 0;
            text-align: center;
            padding: 5px;
        }
        label {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 1rem;
        }
        .cprght {
  margin-top: 20px;
  font-size: 0.8rem;
  color: #666;
}
    </style>

</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="<?= '/public/site-img/whale.png'; ?>" alt="whale image" srcset=""><br>
        </div>
        <div class="img-name">
            <h1><span class="w">WHALES</span> <span class="m">MARKET</span></h1>
        </div>
        <div class="login-div">
        <h3>ooPs 404</h3><hr>
            <form action="">
                <p class="quotes">
                    No matter how hard you try, there is always someone ahead of you looking for the same thing. But don't worry, Whales Market is committed to ensuring the highest level of security for our users. We value your contributions to our platform's security and are ready to reward you fairly for any legitimate bugs you report.
                    <br><br>
                    Our bug bounty program acknowledges the time and complexity involved in identifying and reporting bugs. We understand that your efforts are crucial in helping us maintain a secure environment for our community. So, don't hesitate to let us know about any vulnerabilities you discover.
                    <br><br>
                    To report a bug, please click the button to fill form below. Provide a detailed bug title and notes, which will help us understand and address the issue effectively.
                </p>
                <p><?= (!empty($_SESSION['user_id']) ? '<a href="/report/bugs">Report bug</a>' : 'You must login to report bugs!<br><a href="/auth/login">login here</a>'); ?></p>
                    WHERE FREEDOM TRULY LIES <br>
                </p>
            </form>
        </div>
    </div>
</body>

</html>