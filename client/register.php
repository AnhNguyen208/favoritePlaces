<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plasha Login Form</title>

    <!-- CSS -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/form-elements.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    <!-- Favicon and touch icons -->
    <link rel="shortcut icon" href="assets/ico/favicon.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
</head>

<body>
    <?php
        session_start();
    ?>
    <!-- Top content -->
    <div class="top-content">
        <div class="inner-bg">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 text">
                        <h1><strong>Plasha</strong> Register Form</h1>
                        <div class="description">
                            <p>
                                This is PlaSha Register form
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3 form-box">
                        <div class="form-top">
                            <div class="form-top-left">
                                <h3>Create an account</h3>
                                <p>Enter your username and password to sign up:</p>
                            </div>
                            <div class="form-top-right">
                                <i class="fa fa-lock"></i>
                            </div>
                        </div>
                        <div class="form-bottom">
                            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"
                                class="login-form">
                                <div class="form-group">
                                    <label class="sr-only" for="form-username">Username</label>
                                    <input type="text" name="username" placeholder="Username..."
                                        class="form-username form-control" id="form-username">
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="form-password">Password</label>
                                    <input type="password" name="password" placeholder="Password..."
                                        class="form-password form-control" id="form-password">
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="form-password">Password</label>
                                    <input type="password" name="password1" placeholder="Password again..."
                                        class="form-password form-control" id="form-password">
                                </div>
                                <button type="submit" class="btn">Sign up!</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
            if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password1'])) {
                if ($_POST['password'] != $_POST['password1']) {
                    echo "<script>alert('Input password again');</script>";
                } 
                else {
                    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");

                    // connect to server
                    $result = socket_connect($socket, $_SESSION['host_server'], $_SESSION['port']) or die("socket_connect() failed.\n");

                    $msg = "01|" . $_POST['username'] . "|" . $_POST['password'] . "|";

                    $ret = socket_write($socket, $msg, strlen($msg));
                    if (!$ret) die("client write fail:" . socket_strerror(socket_last_error()) . "\n");

                    // receive response from server
                    $response = socket_read($socket, 1024);
                    if (!$response) die("client read fail:" . socket_strerror(socket_last_error()) . "\n");

                    $response = explode("|", $response);

                    if ($response[0] == "11") {
                        echo "<script>alert('Register success');</script>";
                        // echo "<script>alert('id_user: ". $_SESSION['id_user'] ."');</script>";
                        echo "<script>window.location.href = 'login.php';</script>";
                    } else {
                        echo "<script>alert('Register fail');</script>";
                    }
                    socket_close($socket);
                }


                
            }
        ?>
    <!-- Javascript -->
    <script src="login_form/assets/js/jquery-1.11.1.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="login_form/assets/js/jquery.backstretch.min.js"></script>
    <script src="login_form/assets/js/scripts.js"></script>

    <!--[if lt IE 10]>
            <script src="assets/js/placeholder.js"></script>
        <![endif]-->

</body>

</html>