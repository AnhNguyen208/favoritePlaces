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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>

<body>
    <?php
    session_start();
    include("place.php");
    include("navbar.php");
    $host = "127.0.0.1";
    $port = 8888;
    $_SESSION['host_server'] = $host;
    $_SESSION['port'] = $port;

    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");

    // connect to server
    $result = socket_connect($socket, $_SESSION['host_server'], $_SESSION['port']) or die("socket_connect() failed.\n");

    $msg = "03|" . "0" . "|";

    $ret = socket_write($socket, $msg, strlen($msg));
    if (!$ret) die("client write fail:" . socket_strerror(socket_last_error()) . "\n");

    // receive response from server
    $response = socket_read($socket, 1024);
    if (!$response) die("client read fail:" . socket_strerror(socket_last_error()) . "\n");

    $response = explode("|", $response);

    if ($response[0] == "1") {
        $_SESSION['num_places'] = $response[1];
        $_SESSION["position"] = 1;
        $_SESSION['place_list'] = array();

        //echo $_SESSION['num_places'];
    } else {
        echo "<script>alert('Loading fail');</script>";
    }
    while ($_SESSION['position'] <= $_SESSION['num_places']) {
        $msg = "03|" . $_SESSION["position"] . "|";

        $ret = socket_write($socket, $msg, strlen($msg));
        if (!$ret) die("client write fail:" . socket_strerror(socket_last_error()) . "\n");

        // receive response from server
        $response = socket_read($socket, 1024);
        if (!$response) die("client read fail:" . socket_strerror(socket_last_error()) . "\n");
        //echo $response;

        // split response from server
        $response = explode("|", $response);

        if ($response[0] == "2") {
            $p = new Place();
            $p->set_id($response[1]);
            $p->set_name($response[2]);
            $p->set_type($response[3]);
            $p->set_image($response[4]);
            $p->set_description($response[5]);
        } else {
            echo "<script>alert('Places loading fail');</script>";
            echo "<script>window.location.href = 'test.php';</script>";
        }
        $_SESSION["place_list"][$_SESSION["position"]] = $p;
        $_SESSION["position"] += 1;
    }
    socket_close($socket);
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
                for ($i = 1; $i <= $total; $i++) {
                    echo (" <div class=\"col mb-5\">
                                <div class=\"card h-100\">
                                    <img class=\"card-img-top\" src=\"" . $_SESSION['place_list'][$i]->get_image() . "\" alt=\"" .  $_SESSION['place_list'][$i]->get_image() . "\" />
                                    <div class=\"card-body p-4\">
                                        <div class=\"text-center\">
                                            <h5 class=\"fw-bolder\">" . $_SESSION['place_list'][$i]->get_name() . "</h5>
                                                    " . $_SESSION['place_list'][$i]->get_type() . "
                                        </div>
                                    </div>
                                    <div class=\"card-footer p-4 pt-0 border-top-0 bg-transparent\">
                                        <div class=\"text-center\">
                                            <a class=\"btn btn-outline-dark mt-auto\" href=\"index.php?AddFavorite=". $_SESSION['place_list'][$i]->get_id() ."\">Favorite</a>
                                            <button type=\"button\" class=\"btn btn-primary\" data-toggle=\"modal\" data-target=\"#exampleModal".$i ."\">
                                                Share
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class=\"modal fade\" id=\"exampleModal". $i ."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalLabel\"
                                    aria-hidden=\"true\">
                                    <div class=\"modal-dialog\" role=\"document\">
                                        <form action=\"" . $_SERVER['PHP_SELF']. "\" method=\"post\">
                                            <div class=\"modal-content\">
                                                <div class=\"modal-header\">
                                                    <h5 class=\"modal-title\" id=\"exampleModalLabel\">Modal title</h5>
                                                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                                                        <span aria-hidden=\"true\">&times;</span>
                                                    </button>
                                                </div>
                                                <div class=\"modal-body\">
                                                    This is a modal. Edit it however you want.
                                                </div>
                                                <div class=\"modal-footer\">
                                                    <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Close</button>
                                                    <a class=\"btn btn-primary\" href=\"index.php?share=". $i ."\">Share</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    ");
                }

                if(isset($_GET['AddFavorite'])) {
                    if(isset($_SESSION['login']) && ($_SESSION['login'] == 1 )) {
                        //echo "<script>alert('Add favorite: ". $_GET['AddFavorite'] ."');</script>";
                        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");

                        // connect to server
                        $result = socket_connect($socket, $_SESSION['host_server'], $_SESSION['port']) or die("socket_connect() failed.\n");

                        $msg = "05|" . $_SESSION['id_user'] . "|" . $_GET['AddFavorite'] . "|";

                        $ret = socket_write($socket, $msg, strlen($msg));
                        if (!$ret) die("client write fail:" . socket_strerror(socket_last_error()) . "\n");

                        // receive response from server
                        $response = socket_read($socket, 1024);
                        if (!$response) die("client read fail:" . socket_strerror(socket_last_error()) . "\n");

                        $response = explode("|", $response);

                        if ($response[0] == "13") {
                            echo "<script>alert('Add success');</script>";
                        } else {
                            echo "<script>alert('Add fail');</script>";
                        }
                        socket_close($socket);
                    }
                    else {
                        echo "<script>alert('You have to log in first');</script>";
                        echo "<script>window.location.href = 'login.php';</script>";
                    }
                }

                if(isset($_GET['share'])) {
                    echo "<script>alert('Place share = ". $_GET['share'] ."');</script>";
                }
                ?>
            </div>
        </div>

    </section>
    <!-- Footer-->
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; Your Website 2022</p>
        </div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"
        integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous">
    </script>
</body>

</html>
