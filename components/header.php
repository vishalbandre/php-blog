<?php

use Admin\Translation;

require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

if (!isset($_SESSION)) {
    session_start();
}

// Set language if it is provided else set it to default (en)
if (isset($_GET['lang']) && !empty($_GET['lang'])) {
    $lang = $_GET['lang'];
} else {
    $lang = 'en';
}

// check if 'lang' cookie is set
if (isset($_COOKIE['lang'])) {
    $site_lang = $_COOKIE['lang'];
} else {
    $site_lang = $lang;
}
?>

<header class="navigation-wrap bg-light start-header start-style">
    <div class="row">
        <div class="col-12">
            <nav class="navbar navbar-expand-lg navbar-light bg-light bg px-3">
                <div class="container-fluid p-0">
                    <a class="navbar-brand" href="/"><?php Translation::translate('Colors', $site_lang); ?></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarScroll">
                        <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 250px;">
                            <li class="nav-item {% if request.path == '/' %}active{% endif %}">
                                <a class="nav-link active" aria-current="page" href="/"><?php Translation::translate('Home', $site_lang); ?></a>
                            </li>
                            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) : ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="/en/posts/create"><?php Translation::translate('New Article', $site_lang); ?></a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php Translation::translate('Carousels', $site_lang); ?>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item" href="/carousels/"><?php Translation::translate('All Carousels', $site_lang); ?></a></li>
                                        <li><a class="dropdown-item" href="/carousels/create.php"><?php Translation::translate('Add New Carousel', $site_lang); ?></a></li>
                                        <li><a class="dropdown-item" href="/carousels/categories/"><?php Translation::translate('All Carousels Categories', $site_lang); ?></a></li>
                                        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) : ?>
                                            <li><a class="dropdown-item" href="/carousels/categories/create.php"><?php Translation::translate('Add New Carousel Category', $site_lang); ?></a></li>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php Translation::translate('My Account', $site_lang); ?>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li>
                                            <a class="dropdown-item" href="/accounts/profile/<?php echo $_SESSION['user']; ?>"><?php Translation::translate('My Profile', $site_lang); ?></a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/accounts/edit.php?user=<?php echo $_SESSION['user']; ?>"><?php Translation::translate('Edit Profile', $site_lang); ?></a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/accounts/logout.php"><?php Translation::translate('Logout', $site_lang); ?></a>
                                        </li>
                                    </ul>
                                </li>
                                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) : ?>
                                    <li class="nav-ite">
                                        <a class="btn btn-primary" href="/admin/"><?php Translation::translate('Admin Dashboard', $site_lang); ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php else : ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="/accounts/register.php"><?php Translation::translate('Register', $site_lang); ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/accounts/login.php"><?php Translation::translate('Login', $site_lang); ?></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <form class="d-flex col-6 top-search-form" action="/<?php echo $site_lang; ?>/posts/search.php">
                            <input class="form-control me-2" type="search" placeholder="<?php Translation::translate('Search', $site_lang); ?>" name="q" value="<?php if (isset($_GET['q'])) echo $_GET['q']; ?>" aria-label="Search">
                            <button class="btn btn-dark" type="submit"><?php Translation::translate('Search', $site_lang); ?></button>
                        </form>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>