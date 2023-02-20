<?php 
    include("place.php");
    include("user.php");
    
    class Request {
        function login() {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");

            // connect to server
            $result = socket_connect($socket, $_SESSION['host_server'], $_SESSION['port']) or die("socket_connect() failed.\n");

            $msg = "00|" . $_POST['username'] . "|" . $_POST['password'] . "|";

            $ret = socket_write($socket, $msg, strlen($msg));
            if (!$ret) die("client write fail:" . socket_strerror(socket_last_error()) . "\n");

            // receive response from server
            $response = socket_read($socket, 1024);
            if (!$response) die("client read fail:" . socket_strerror(socket_last_error()) . "\n");

            $response = explode("|", $response);

            if ($response[0] == "8") {
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['id_user'] = $response[1];
                $_SESSION['login'] = 1;
                echo "<script>alert('Login success');</script>";
                // echo "<script>alert('id_user: ". $_SESSION['id_user'] ."');</script>";
                echo "<script>window.location.href = 'index.php';</script>";
            } else {
                echo "<script>alert('Login fail');</script>";
            }
            socket_close($socket);
        }

        function register() {
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

        function logout() {
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
                $_SESSION['friend_list_html'] = '';
                echo "<script>alert('Log out success');</script>";
                echo "<script>window.location.href = 'index.php';</script>";
            } else {
                echo "<script>alert('Logout fail');</script>";
            }
            socket_close($socket);
        }

        function getPlaceList() {
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
                }
                $_SESSION["place_list"][$_SESSION["position"]] = $p;
                $_SESSION["position"] += 1;
            }
            socket_close($socket);
        }

        function getFavouriteList() {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");

            // connect to server
            $result = socket_connect($socket, $_SESSION['host_server'], $_SESSION['port']) or die("socket_connect() failed.\n");

            $msg = "04|" . $_SESSION['id_user'] . "|";

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
                }
                array_push($_SESSION['favorite_place_list'], $p);
            }
            socket_close($socket);
        }

        function favoritePlace($msg) {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");

            // connect to server
            $result = socket_connect($socket, $_SESSION['host_server'], $_SESSION['port']) or die("socket_connect() failed.\n");

            $ret = socket_write($socket, $msg, strlen($msg));
            if (!$ret) die("client write fail:" . socket_strerror(socket_last_error()) . "\n");

            // receive response from server
            $response = socket_read($socket, 1024);
            if (!$response) die("client read fail:" . socket_strerror(socket_last_error()) . "\n");

            $response = explode("|", $response);

            if ($response[0] == "13") {
                echo "<script>alert('Add success');</script>";
            }

            else if ($response[0] == "19") {
                echo "<script>alert('Share success');</script>";
            }
            else if ($response[0] == "26") {
                echo "<script>alert('Delete success');</script>";
            }
            else {
                echo "<script>alert('Fail');</script>";
            }
            socket_close($socket);
        }

        function getAllUser() {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");

            // connect to server
            $result = socket_connect($socket, $_SESSION['host_server'], $_SESSION['port']) or die("socket_connect() failed.\n");

            $msg = "06|" . "0" . "|";

            $ret = socket_write($socket, $msg, strlen($msg));
            if (!$ret) die("client write fail:" . socket_strerror(socket_last_error()) . "\n");

            // receive response from server
            $response = socket_read($socket, 1024);
            if (!$response) die("client read fail:" . socket_strerror(socket_last_error()) . "\n");

            $response = explode("|", $response);

            if ($response[0] == "17") {
                $_SESSION['num_user'] = $response[1];
                $_SESSION["position"] = 1;
                $_SESSION['user_list'] = array();

            } else {
                echo "<script>alert('Loading fail');</script>";
            }
            while ($_SESSION['position'] <= $_SESSION['num_user']) {
                $msg = "06|" . $_SESSION["position"] . "|";
                $ret = socket_write($socket, $msg, strlen($msg));
                if (!$ret) die("client write fail:" . socket_strerror(socket_last_error()) . "\n");

                // receive response from server
                $response = socket_read($socket, 1024);
                if (!$response) die("client read fail:" . socket_strerror(socket_last_error()) . "\n");
                //echo $response;

                // split response from server
                $response = explode("|", $response);

                if ($response[0] == "18") {
                    if($_SESSION['id_user'] == $response[1]) {
                        $_SESSION["position"] += 1;
                        continue;
                    }
                    $u = new User();
                    $u->set_id_user($response[1]);
                    $u->set_username($response[2]);
                    if(in_array($u, $_SESSION['friend_list']) || in_array($u, $_SESSION['friend_request'])) {
                        $_SESSION["position"] += 1;
                        continue;
                    }
                    else {
                        array_push($_SESSION['user_list'], $u);                    
                    }
                } else {
                    echo "<script>alert('Friend loading fail');</script>";
                }
                $_SESSION["position"] += 1;
            }
            socket_close($socket);
        }

        function getListSharedPlaces() {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");

            // connect to server
            $result = socket_connect($socket, $_SESSION['host_server'], $_SESSION['port']) or die("socket_connect() failed.\n");

            $msg = "08|" . $_SESSION['id_user'] . "|";

            $ret = socket_write($socket, $msg, strlen($msg));
            if (!$ret) die("client write fail:" . socket_strerror(socket_last_error()) . "\n");

            // receive response from server
            $response = socket_read($socket, 1024);
            if (!$response) die("client read fail:" . socket_strerror(socket_last_error()) . "\n");
            
            //echo json_encode($response);

            $response = explode("|", $response);

            if ($response[0] == "21") {
                $_SESSION['num_shared_places'] = $response[1];
                $_SESSION['position_friend'] = array();
                $_SESSION['position_place_shared'] = array();
                for ($i = 0; $i < $_SESSION['num_shared_places']; $i++) { 
                    $response1 = explode(",", $response[$i+2]);
                    array_push($_SESSION['position_friend'], $response1[0]);
                    array_push($_SESSION['position_place_shared'], $response1[1]);
                }
                $_SESSION['place_list_shared'] = array();

                
                // echo json_encode($_SESSION['position_friend']);
            } else {
                echo "<script>alert('Loading fail');</script>";
            }

            for ($i=0; $i < $_SESSION['num_shared_places'] ; $i++) { 
                $msg = "03|" . $_SESSION['position_place_shared'][$i] . "|";

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
                    echo "<script>alert('Place shared loading fail');</script>";
                }

                $msg = "06|" . $_SESSION['position_friend'][$i] . "|";
                $ret = socket_write($socket, $msg, strlen($msg));
                if (!$ret) die("client write fail:" . socket_strerror(socket_last_error()) . "\n");

                // receive response from server
                $response = socket_read($socket, 1024);
                if (!$response) die("client read fail:" . socket_strerror(socket_last_error()) . "\n");
                //echo $response;

                // split response from server
                $response = explode("|", $response);

                if ($response[0] == "18") {
                    $p->set_share_by_id($response[1]);
                    $p->set_share_by($response[2]);
                } else {
                    echo "<script>alert('Friend loading fail');</script>";
                }

                array_push($_SESSION['place_list_shared'], $p);
            }

            socket_close($socket);
        }

        function getFriendList() {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");

            // connect to server
            $result = socket_connect($socket, $_SESSION['host_server'], $_SESSION['port']) or die("socket_connect() failed.\n");

            $msg = "09|" . $_SESSION['id_user'] . "|";

            $ret = socket_write($socket, $msg, strlen($msg));
            if (!$ret) die("client write fail:" . socket_strerror(socket_last_error()) . "\n");

            // receive response from server
            $response = socket_read($socket, 1024);
            if (!$response) die("client read fail:" . socket_strerror(socket_last_error()) . "\n");

            $response = explode("|", $response);

            if ($response[0] == "23") {
                $_SESSION['num_friend'] = $response[1];
                $_SESSION['position_friend'] = array();
                for ($i = 0; $i < $_SESSION['num_friend']; $i++) { 
                    array_push($_SESSION['position_friend'], $response[$i+2]);
                }

                $_SESSION['friend_list_html'] = '';
                $_SESSION['friend_list'] = array();

            } else {
                echo "<script>alert('Loading fail');</script>";
            }
            foreach($_SESSION['position_friend'] as $friend) {
                $msg = "06|" . $friend . "|";
                $ret = socket_write($socket, $msg, strlen($msg));
                if (!$ret) die("client write fail:" . socket_strerror(socket_last_error()) . "\n");

                // receive response from server
                $response = socket_read($socket, 1024);
                if (!$response) die("client read fail:" . socket_strerror(socket_last_error()) . "\n");
                //echo $response;

                // split response from server
                $response = explode("|", $response);

                if ($response[0] == "18") {
                    $u = new User();
                    $u->set_id_user($response[1]);
                    $u->set_username($response[2]);
                    $_SESSION['friend_list_html'] .= "<option value=\"" . $response[1] ."\">" . $response[2] . "</option>";
                    
                } else {
                    echo "<script>alert('Friend loading fail');</script>";
                }

                array_push($_SESSION['friend_list'], $u);

            }
            socket_close($socket);
        }

        function addPlace($msg) {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");

            // connect to server
            $result = socket_connect($socket, $_SESSION['host_server'], $_SESSION['port']) or die("socket_connect() failed.\n");

            $ret = socket_write($socket, $msg, strlen($msg));
            if (!$ret) die("client write fail:" . socket_strerror(socket_last_error()) . "\n");

            // receive response from server
            $response = socket_read($socket, 1024);
            if (!$response) die("client read fail:" . socket_strerror(socket_last_error()) . "\n");

            $response = explode("|", $response);

            if ($response[0] == "24") {
                echo "<script>alert('Add success');</script>";
                echo "<script>window.location.href = 'index.php';</script>";
            } else {
                echo "<script>alert('Failed');</script>";
            }
            socket_close($socket);
        }

        function getFriendRequest() {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");

            // connect to server
            $result = socket_connect($socket, $_SESSION['host_server'], $_SESSION['port']) or die("socket_connect() failed.\n");

            $msg = "12|" . $_SESSION['id_user'] . "|";

            $ret = socket_write($socket, $msg, strlen($msg));
            if (!$ret) die("client write fail:" . socket_strerror(socket_last_error()) . "\n");

            // receive response from server
            $response = socket_read($socket, 1024);
            if (!$response) die("client read fail:" . socket_strerror(socket_last_error()) . "\n");

            $response = explode("|", $response);

            if ($response[0] == "28") {
                $_SESSION['num_friend_request'] = $response[1];
                $_SESSION['position_friend'] = array();
                for ($i = 0; $i < $_SESSION['num_friend_request']; $i++) { 
                    array_push($_SESSION['position_friend'], $response[$i+2]);
                }

                $_SESSION['friend_request'] = array();

            } else {
                echo "<script>alert('Loading fail');</script>";
            }
            foreach($_SESSION['position_friend'] as $friend) {
                $msg = "06|" . $friend . "|";
                $ret = socket_write($socket, $msg, strlen($msg));
                if (!$ret) die("client write fail:" . socket_strerror(socket_last_error()) . "\n");

                // receive response from server
                $response = socket_read($socket, 1024);
                if (!$response) die("client read fail:" . socket_strerror(socket_last_error()) . "\n");
                //echo $response;

                // split response from server
                $response = explode("|", $response);

                if ($response[0] == "18") {
                    $u = new User();
                    $u->set_id_user($response[1]);
                    $u->set_username($response[2]);
                    
                } else {
                    echo "<script>alert('Friend request loading fail');</script>";
                }

                array_push($_SESSION['friend_request'], $u);

            }
            socket_close($socket);
        }

        function crudFriend($msg) {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");

            // connect to server
            $result = socket_connect($socket, $_SESSION['host_server'], $_SESSION['port']) or die("socket_connect() failed.\n");

            $ret = socket_write($socket, $msg, strlen($msg));
            if (!$ret) die("client write fail:" . socket_strerror(socket_last_error()) . "\n");

            // receive response from server
            $response = socket_read($socket, 1024);
            if (!$response) die("client read fail:" . socket_strerror(socket_last_error()) . "\n");

            $response = explode("|", $response);

            if ($response[0] == "29") {
                echo "<script>alert('Success');</script>";
            } else {
                echo "<script>alert('Failed');</script>";
            }
            socket_close($socket);
        }
    }
?>