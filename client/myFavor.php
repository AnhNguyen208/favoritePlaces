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
</head>

<body>
    <?php
        session_start();
        include("place.php");
        include("navbar.php");
        if(isset($_SESSION['login']) && ($_SESSION['login'] == 1 )) {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");

            // connect to server
            $result = socket_connect($socket, $_SESSION['host_server'], $_SESSION['port']) or die("socket_connect() failed.\n");

            $msg = "04|" . $_SESSION['id_user'] . "|" . "0|";

            $ret = socket_write($socket, $msg, strlen($msg));
            if (!$ret) die("client write fail:" . socket_strerror(socket_last_error()) . "\n");

            // receive response from server
            $response = socket_read($socket, 1024);
            if (!$response) die("client read fail:" . socket_strerror(socket_last_error()) . "\n");
            
            //echo json_encode($response);

            $response = explode("|", $response);

            if ($response[0] == "15") {
                $_SESSION['num_favorite_places'] = $response[1];
                $_SESSION['position_favorite_place'] = array();
                for ($i = 0; $i < $_SESSION['num_favorite_places']; $i++) { 
                    array_push($_SESSION['position_favorite_place'], $response[$i+2]);
                }
                $_SESSION['favorite_place_list'] = array();

                // echo json_encode($response);
            } else {
                echo "<script>alert('Loading fail');</script>";
            }

            foreach ($_SESSION['position_favorite_place'] as $position) {
                $msg = "03|" . $position . "|";

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
                array_push($_SESSION['favorite_place_list'], $p);
            }
            socket_close($socket);
        }
        else {
            echo "<script>alert('Not logged in');</script>";
            echo "<script>window.location.href = 'login.php';</script>";
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
                if (isset($_SESSION['num_favorite_places'])) {
                    $total = $_SESSION['num_favorite_places'];
                    for ($i = 0; $i < $total; $i++) {
                    echo ("<div class=\"col mb-5\">
                                    <div class=\"card h-100\">
                                        <img class=\"card-img-top\" src=\"" . $_SESSION['favorite_place_list'][$i]->get_image() . "\" alt=\"" .  $_SESSION['favorite_place_list'][$i]->get_image() . "\" />
                                            <div class=\"card-body p-4\">
                                                <div class=\"text-center\">
                                                    <h5 class=\"fw-bolder\">" . $_SESSION['favorite_place_list'][$i]->get_name() . "</h5>
                                                        " . $_SESSION['favorite_place_list'][$i]->get_type() . "
                                                </div>
                                            </div>
                                            <div class=\"card-footer p-4 pt-0 border-top-0 bg-transparent\">
                                                <div class=\"text-center\">
                                                    <a class=\"btn btn-outline-dark mt-auto\" href=\"#\">Share</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            ");
                    }
                } else {
                    $total = 0;
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
</body>

</html>