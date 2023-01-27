<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Shop Homepage - Start Bootstrap Template</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <?php
            include("place.php");
            session_start();
            $host = "127.0.0.1";
            $port = 8888;
            $_SESSION['host_server'] = $host;
            $_SESSION['port']= $port; 
            
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
                    echo "<script>alert('Game loading fail');</script>";
                    echo "<script>window.location.href = 'test.php';</script>";
                }
                $_SESSION["place_list"][$_SESSION["position"]] = $p;
                $_SESSION["position"] += 1;
            }
            socket_close($socket);
        ?>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="#!">Start Bootstrap</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="#!">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="#!">About</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#!">All Products</a></li>
                                <li><hr class="dropdown-divider" /></li>
                                <li><a class="dropdown-item" href="#!">Popular Items</a></li>
                                <li><a class="dropdown-item" href="#!">New Arrivals</a></li>
                            </ul>
                        </li>
                    </ul>
                    <form class="d-flex">
                        <button class="btn btn-outline-dark" type="submit">
                            <i class="bi-cart-fill me-1"></i>
                            Cart
                            <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
                        </button>
                    </form>
                </div>
            </div>
        </nav>
        <!-- Header-->
        <header class="bg-dark py-5">
            <div class="container px-4 px-lg-5 my-5">
                <div class="text-center text-white">
                    <h1 class="display-4 fw-bolder">Shop in style</h1>
                    <p class="lead fw-normal text-white-50 mb-0">With this shop hompeage template</p>
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
                            echo ("<div class=\"col mb-5\">
                                    <div class=\"card h-100\">
                                        <img class=\"card-img-top\" src=\"". $_SESSION['place_list'][$i]->get_image() ."\" alt=\"".  $_SESSION['place_list'][$i]->get_image() ."\" />
                                            <div class=\"card-body p-4\">
                                                <div class=\"text-center\">
                                                    <h5 class=\"fw-bolder\">". $_SESSION['place_list'][$i]->get_name() ."</h5>
                                                        ". $_SESSION['place_list'][$i]->get_type() ."
                                                </div>
                                            </div>
                                            <div class=\"card-footer p-4 pt-0 border-top-0 bg-transparent\">
                                                <div class=\"text-center\"><a class=\"btn btn-outline-dark mt-auto\" href=\"#\">View options</a></div>
                                            </div>
                                        </div>
                                    </div>
                            ");
                        }
                    ?>
                </div>
            </div>
        </section>
        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2022</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>