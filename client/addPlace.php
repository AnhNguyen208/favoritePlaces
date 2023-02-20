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
    include("request.php");
    ?>
    <!-- Top content -->
    <div class="top-content">
        <div class="inner-bg">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 text">
                        <h1><strong>Plasha</strong> Add Place Form</h1>
                        <div class="description">
                            <p>
                                This is PlaSha add place form
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3 form-box">
                        <div class="form-top">
                            <div class="form-top-left">
                                <h3>Let's share your place to our site</h3>
                                <p>Enter information of the place:</p>
                            </div>
                            <div class="form-top-right">
                                <i class="fa fa-lock"></i>
                            </div>
                        </div>
                        <div class="form-bottom">
                            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method="post" class="login-form">
                                <div class="form-group">
                                    <label class="sr-only" for="placename">Placename</label>
                                    <input type="text" name="placename" placeholder="Place's name..." class="form-placename form-control" id="placename">
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="category">Category</label>
                                    <input type="text" name="category" placeholder="Category..." class="form-placename form-control" id="category">
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="description">Description</label>
                                    <input type="text" name="description" placeholder="Description..." class="form-placename form-control" id="description">
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="image">Image</label>
                                    <input type="file" name="image" class="form-placename form-control" id="image">
                                </div>
                                <button type="submit" class="btn ">Create!</button>

                            </form>
                            <a href="index.php"><button class="btn" style="margin-top: 10px;width: 100%;">Back to Home</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $request = new Request();
        $request->login();
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
