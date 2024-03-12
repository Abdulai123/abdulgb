<?php

$wait_time = mt_rand(3, 5); // time < 8 seconds

if (!isset($_SESSION['wait_time'])) {
    $_SESSION['wait_time'] = time();
}

if (time() - $_SESSION['wait_time'] > $wait_time) {
    if (isset($_SESSION['welcome'])) {
        header("Location:  welcome/");
        exit;
    }elseif(isset($_SESSION['role'])  &&  $_SESSION['role'] == 'user'){
        header("Location:  /");
    }elseif(!empty($_SESSION['store_id']) &&  ($_SESSION['role'] == 'store')){
        header("Location:  /Store");
    }elseif(!empty($_SESSION['smod_id']) &&  ($_SESSION['role'] == 'smod')){
        header("Location:  /smod");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="<?php echo $wait_time; ?>">
    <title>Waiting Room</title>
    <style>
        span{
            animation: aniblink 2s infinite 1s ease-in-out;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-weight: 900;
            font-size: 1.1rem;
        }

        @keyframes aniblink{
            0%{
                opacity: 0;
            }
            25%{
                opacity: 0.7;
            }
            50%{
                opacity: 0.3;
            }
            75%{
                opacity: 0.5;
            }
            100%{
                opacity: 1;
            }
        }
    </style>
</head>
<body>
A new visitor is entering the site. You'll be redirected shortly. Estimated wait time is less than 5 seconds. Thank you for your patience<span>.......</span> 
</body>
</html>
