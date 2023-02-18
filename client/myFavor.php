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
        include("navbar.php");
        include("request.php");
        $request = new Request();

        if(isset($_SESSION['login']) && ($_SESSION['login'] == 1 )) {
            if(isset($_GET['shared_by_id']) && isset($_GET['id_place'])) {
                $msg = "11|" . "3" . "|" . $_SESSION['id_user'] . "|" . $_GET['shared_by_id'] . "|" . $_GET['id_place'] ."|";
                $request->deletePlace($msg);
            } else if (isset($_GET['id_place'])) {
                $msg = "11|" . "2" . "|" . $_SESSION['id_user'] . "|" . $_GET['id_place'] ."|";
                $request->deletePlace($msg);
            }
            $request->getFavouriteList();
            $request->getListSharedPlaces();
        }
        else {
            echo "<script>alert('You have to log in first');</script>";
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
            <h1 class="text-center"> Your Favorite Places List</h1>
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
                                                    <a class=\"btn btn-outline-dark mt-auto\" href=\"myFavor.php?id_place=".  $_SESSION['favorite_place_list'][$i]->get_id() ."\">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            ");
                    }
                } else {
                    $total = 0;
                }

                if(isset($_POST['logout'])) {
                    $request->logout();
                }
            ?>
            </div>
        </div>
        <div class="container px-4 px-lg-5 mt-5">
            <h1 class="text-center"> Your Shared Places List</h1>
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            <?php
                if (isset($_SESSION['num_shared_places'])) {
                    $total = $_SESSION['num_shared_places'];
                    for ($i = 0; $i < $total; $i++) {
                    echo ("<div class=\"col mb-5\">
                                    <div class=\"card h-100\">
                                        <img class=\"card-img-top\" src=\"" . $_SESSION['place_list_shared'][$i]->get_image() . "\" alt=\"" .  $_SESSION['place_list_shared'][$i]->get_image() . "\" />
                                            <div class=\"card-body p-4\">
                                                <div class=\"text-center\">
                                                    <h5 class=\"fw-bolder\">" . $_SESSION['place_list_shared'][$i]->get_name() . "</h5>
                                                        " . $_SESSION['place_list_shared'][$i]->get_type() . "
                                                    <h5 class=\"fw-bolder\"> Shared By: " . "</h5>
                                                        ". $_SESSION['place_list_shared'][$i]->get_share_by()."
                                                </div>
                                            </div>
                                            <div class=\"card-footer p-4 pt-0 border-top-0 bg-transparent\">
                                                <div class=\"text-center\">
                                                    <a class=\"btn btn-outline-dark mt-auto\" href=\"myFavor.php?shared_by_id=". $_SESSION['place_list_shared'][$i]->get_share_by_id() . "&id_place=".  $_SESSION['place_list_shared'][$i]->get_id() ."\">Delete</a>
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
            <p class="m-0 text-center text-white">Copyright &copy; PlaSha 2023</p>
        </div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>

</html>
