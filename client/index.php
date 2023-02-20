<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>PlaSha - Sharing Places Sharing Fun</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>

<body>
    <?php
        session_start();
        include("navbar.php");
        include("request.php");
        $host = "127.0.0.1";
        $port = 1111;
        $_SESSION['host_server'] = $host;
        $_SESSION['port'] = $port;

        $request = new Request();
        $request->getPlaceList();
        if(isset($_SESSION['login']) && ($_SESSION['login'] == 1 )){
            $request->getFriendList();
        }
        
        if(isset($_POST['logout'])) {
            $request->logout();
            echo "<script>window.location.href = 'index.php';</script>";
        }
        
    ?>
    <!-- Header-->
    <header class="bg-dark py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="text-center text-white">
                <h1 class="display-4 fw-bolder">Sharing Favorite Places Application</h1>
                <p class="lead fw-normal text-white-50 mb-0">Network Programming Final Project</p>
            </div>
        </div>
    </header>
    <!-- Section-->
    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                <?php
                if (isset($_SESSION['num_places'])) {
                    $total = $_SESSION['num_places'];
                } else {
                    $total = 0;
                }
                if(isset($_SESSION['friend_list_html'])) {
                    $friend = $_SESSION['friend_list_html'];
                }
                else {
                    $friend = "";
                }

                for ($i = 1; $i <= $total; $i++) {
                    echo (" <div class=\"col mb-5\">
                                <div class=\"card h-100\">
                                    <img class=\"card-img-top\" src=\"" . $_SESSION['place_list'][$i]->get_image() . "\" alt=\"" .  $_SESSION['place_list'][$i]->get_image() . "\" />
                                    <div class=\"card-body p-4\">
                                        <div class=\"text-center\">
                                            <h5 class=\"fw-bolder\" style=\"height:50px\">" . $_SESSION['place_list'][$i]->get_name() . "</h5>
                                                    " . $_SESSION['place_list'][$i]->get_type() . "
                                                    ". "<br>" . $_SESSION['place_list'][$i]->get_description() . "

                                        </div>
                                    </div>
                                    <div class=\"card-footer p-4 pt-0 border-top-0 bg-transparent\">
                                        <div class=\"text-center\">
                                            <a class=\"btn btn-outline-dark mt-auto\" href=\"index.php?AddFavorite=" . $_SESSION['place_list'][$i]->get_id() . "\">Favorite</a>
                                            <button type=\"button\" class=\"btn btn-outline-dark mt-auto\" data-toggle=\"modal\" data-target=\"#exampleModal" . $i . "\">
                                                Share
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class=\"modal fade\" id=\"exampleModal" . $i . "\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalLabel\"
                                    aria-hidden=\"true\">
                                    <div class=\"modal-dialog\" role=\"document\">
                                        <form action=\"" . $_SERVER['PHP_SELF']. "\" method=\"post\">
                                            <input type=\"hidden\" name=\"id_place\" value=\"" . $i . "\">
                                            <div class=\"modal-content\">
                                                <div class=\"modal-header\">
                                                    <h5 class=\"modal-title\" id=\"exampleModalLabel\">Share This Place To</h5>
                                                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                                                        <span aria-hidden=\"true\">&times;</span>
                                                    </button>
                                                </div>
                                                <div class=\"modal-body\">
                                                    <label for=\"friend\">Choose a friend:</label>
                                                    <select name=\"friend\" id=\"friend\">
                                                    " . $friend . "
                                                    </select>
                                                </div>
                                                <div class=\"modal-footer\">
                                                    <button type=\"button\" class=\"btn btn-outline-dark mt-auto\" data-dismiss=\"modal\">Close</button>
                                                    <input class=\"btn btn-outline-dark mt-auto\" type=\"submit\" value=\"Share\">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    ");
                }
                if (isset($_GET['AddFavorite'])) {
                    if(isset($_SESSION['login']) && ($_SESSION['login'] == 1 )){
                        $msg = "05|" . $_SESSION['id_user'] . "|" . $_GET['AddFavorite'] . "|";
                        $request->favoritePlace($msg);
                    }       
                    else {
                        echo "<script>alert('You have to log in first');</script>";
                        echo "<script>window.location.href = 'login.php';</script>";
                    }
                }
                
                if(isset($_POST['friend']) && isset($_POST['id_place'])) {
                    $msg = "07|" . $_POST['friend'] . "|" . $_SESSION['id_user'] . "|" . $_POST['id_place'] . "|";
                    $request->favoritePlace($msg);
                }       
               
                ?>
            </div>
        </div>

    </section>
    <!-- Footer-->
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; PlaSha 2023</p>
        </div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous">
    </script>
</body>

</html>
