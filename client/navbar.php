<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="#!">PlaSha</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Home</a></li>
                <!-- <li class="nav-item"><a class="nav-link" href="myFavor.php">My Favorite Places</a></li> -->
            </ul>
            
            <form class="nav-bar-nav ms-auto">
                <?php
                    if(isset($_SESSION['login']) && ($_SESSION['login'] == 1 )) {
                        echo ("<a class=\"btn btn-outline-dark\">
                                    Xin chào, ". $_SESSION['username'] ."
                                </a>
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
        </div>
    </div>
</nav>