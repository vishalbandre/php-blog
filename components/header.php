<?php
if (!isset($_SESSION)) {
    session_start();
}
?>

<header class="navigation-wrap bg-light start-header start-style">
    <div class="row">
        <div class="col-12">
            <nav class="navbar navbar-expand-lg navbar-light bg-light bg px-3">
                <div class="container-fluid p-0">
                    <a class="navbar-brand" href="/">Colors Blog</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarScroll">
                        <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 250px;">
                            <li class="nav-item {% if request.path == '/' %}active{% endif %}">
                                <a class="nav-link active" aria-current="page" href="/">Home</a>
                            </li>
                            <?php if ($_SESSION['logged_in']) : ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="/posts/create">New Article</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Carousels
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item" href="/carousels/">All Carousels</a></li>
                                        <li><a class="dropdown-item" href="/carousels/create.php">Add New Carousel</a></li>
                                        <li><a class="dropdown-item" href="/carousels/categories/">All Carousels Categories</a></li>
                                        <li><a class="dropdown-item" href="/carousels/categories/create.php">Add New Carousel Category</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        My Account
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li>
                                            <a class="dropdown-item" href="/accounts/profile/<?php echo $_SESSION['user']; ?>">My Profile</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/accounts/edit.php?user=<?php echo $_SESSION['user']; ?>">Edit Profile</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/accounts/logout.php">Logout</a>
                                        </li>
                                    </ul>
                                </li>
                            <?php else : ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="/accounts/register.php">Register</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/accounts/login.php">Login</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <form class="d-flex top-search-form" action="/posts/search.php">
                            <input class="form-control me-2" type="search" placeholder="Search" name="q" value="<?php if (isset($_GET['q'])) echo $_GET['q']; ?>" aria-label="Search">
                            <button class="btn btn-dark" type="submit">Search</button>
                        </form>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>