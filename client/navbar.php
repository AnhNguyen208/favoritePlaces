<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="#!">PlaSha</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="myFavor.php">My Favorite Places</a></li>
            </ul>
            <form class="nav-bar-nav ms-auto" method="post">
                <?php
                    if(isset($_SESSION['login']) && ($_SESSION['login'] == 1 )) {
                        echo ("<a class=\"btn btn-outline-dark\">
                                    Xin chào, ". $_SESSION['username'] ."
                                </a>
                                <input class=\"btn btn-outline-dark\" type=\"submit\" name=\"logout\" value=\"Log Out\"/>
                        ");
                    }
                    else {
                        echo ("<a class=\"btn btn-outline-dark\" href=\"login.php\">
                                Sign In
                            </a>
                            <button class=\"btn btn-outline-dark\" type=\"submit\">
                                Register
                            </button>
                        ");
                    }
                ?>
            </form>
            <?php
                if(isset($_POST['logout'])) {
                    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");

                    // connect to server
                    $result = socket_connect($socket, $_SESSION['host_server'], $_SESSION['port']) or die("socket_connect() failed.\n");

                    $msg = "02|" . $_SESSION['username'] . "|";

                    $ret = socket_write($socket, $msg, strlen($msg));
                    if (!$ret) die("client write fail:" . socket_strerror(socket_last_error()) . "\n");

                    // receive response from server
                    $response = socket_read($socket, 1024);
                    if (!$response) die("client read fail:" . socket_strerror(socket_last_error()) . "\n");

                    $response = explode("|", $response);

                    if ($response[0] == "9") {
                        $_SESSION['username'] = '';
                        $_SESSION['id_user'] = 0;
                        $_SESSION['login'] = 0;
                        echo "<script>alert('Log out success');</script>";
                        echo "<script>window.location.href = 'index.php';</script>";
                    } else {
                        echo "<script>alert('Logout fail');</script>";
                    }
                    socket_close($socket);
                }
            ?>
        </div>
    </div>
</nav>