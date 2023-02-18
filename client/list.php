<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PlaSha - Sharing Places Sharing Fun</title>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://getbootstrap.com/examples/jumbotron-narrow/jumbotron-narrow.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js">
        < link rel = "icon"
        type = "image/x-icon"
        href = "assets/favicon.ico" / >
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />

    <style type="text/css">
        .list-content {
            min-height: 300px;
        }

        .list-content .list-group .title {
            background: #5bc0de;
            border: 2px solid #DDDDDD;
            font-weight: bold;
            color: #FFFFFF;
        }

        .list-group-item img {
            height: 80px;
            width: 80px;

        }

        .jumbotron .btn {
            padding: 10px 10px !important;
            font-size: 12px !important;
        }

        .prj-name {
            color: #5bc0de;
        }

        .break {
            width: 100%;
            margin: 20px;
        }

        .name {
            color: var(--bs-body-color);
        }
    </style>
    <script type="text/javascript">
    </script>
</head>

<body>
    <?php
    session_start();
    include("place.php");
    include("navbar.php"); ?>
    <header class="bg-dark py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="text-center text-white">
                <h1 class="display-4 fw-bolder">Sharing Favorite Places Application</h1>
                <p class="lead fw-normal text-white-50 mb-0">Network Programming Final Project</p>
            </div>
        </div>
    </header>
    <div class="container bootstrap snippets bootdey">
        <!-- <div class="header">
            <h3 class="text-muted prj-name">
                <span class="fa fa-users fa-2x principal-title"></span>
                Friend zone
            </h3>
        </div> -->
        <div class="container px-4 px-lg-5 mt-5">
        <h1 class="text-center">Friend Requests</h1>
        </div>
        <div class="jumbotron list-content">
            <ul class="list-group">
                <!-- <li href="#" class="list-group-item title">
                    Your friend zone
                </li> -->
                <li href="#" class="list-group-item text-left">
                    <img class="img-thumbnail" src="https://icons.veryicon.com/png/o/miscellaneous/standard/user-274.png">
                    <label class="name">
                        Juan guillermo cuadrado<br>
                    </label>
                    <label class="pull-right">
                        <a class="btn btn-success btn-xs glyphicon glyphicon-ok" href="#">Accept</a>
                        <a class="btn btn-danger  btn-xs glyphicon glyphicon-trash" href="#">Deny</a>
                    </label>
                    <div class="break"></div>
                </li>
                <li href="#" class="list-group-item text-left">
                    <img class="img-thumbnail" src="https://icons.veryicon.com/png/o/miscellaneous/standard/user-274.png">
                    <label class="name">
                        James Rodriguez (10)
                    </label>
                    <label class="pull-right">
                        <a class="btn btn-success btn-xs glyphicon glyphicon-ok" href="#">Accept</a>
                        <a class="btn btn-danger  btn-xs glyphicon glyphicon-trash" href="#">Deny</a>
                    </label>
                    <div class="break"></div>
                </li>
                <li href="#" class="list-group-item text-left">
                    <img class="img-thumbnail" src="https://icons.veryicon.com/png/o/miscellaneous/standard/user-274.png">
                    <label class="name">
                        Mariana pajon
                    </label>
                    <label class="pull-right">
                        <a class="btn btn-success btn-xs glyphicon glyphicon-ok" href="#">Accept</a>
                        <a class="btn btn-danger  btn-xs glyphicon glyphicon-trash" href="#">Deny</a>
                    </label>
                    <div class="break"></div>
                </li>
            </ul>
        </div>
    </div>
    <div class="container px-4 px-lg-5 mt-5">
        <h1 class="text-center">Friends List</h1>
        </div>
    <div class="container bootstrap snippets bootdey">

        <div class="jumbotron list-content">
            <ul class="list-group">
                <!-- <li href="#" class="list-group-item title">
                    Your friend zone
                </li> -->
                <li href="#" class="list-group-item text-left">
                    <img class="img-thumbnail" src="https://icons.veryicon.com/png/o/miscellaneous/standard/user-274.png">
                    <label class="name">
                        Juan guillermo cuadrado<br>
                    </label>
                    <label class="pull-right">
                        <a class="btn btn-danger  btn-xs glyphicon glyphicon-trash" href="#">Unfriend</a>
                    </label>
                    <div class="break"></div>
                </li>
                <li href="#" class="list-group-item text-left">
                    <img class="img-thumbnail" src="https://icons.veryicon.com/png/o/miscellaneous/standard/user-274.png">
                    <label class="name">
                        James Rodriguez (10)
                    </label>
                    <label class="pull-right">
                        <a class="btn btn-danger  btn-xs glyphicon glyphicon-trash" href="#">Unfriend</a>
                    </label>
                    <div class="break"></div>
                </li>
                <li href="#" class="list-group-item text-left">
                    <img class="img-thumbnail" src="https://icons.veryicon.com/png/o/miscellaneous/standard/user-274.png">
                    <label class="name">
                        Mariana pajon
                    </label>
                    <label class="pull-right">
                        <a class="btn btn-danger  btn-xs glyphicon glyphicon-trash" href="#">Unfriend</a>
                    </label>
                    <div class="break"></div>
                </li>
            </ul>
        </div>
    </div>
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; PlaSha 2023</p>
        </div>
    </footer>
</body>

</html>
