<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<header>
    <a href="/" class="branding">PHP Blog</a>
    <form action="/posts/search.php" class="top-search-form" method="get">
        <input type="text" placeholder="Search" name="q" value="<?php if(isset($_GET['q'])) echo $_GET['q']; ?>" />
        <input type="submit" value="Submit" class="search-btn" />
    </form>

    <nav>
        <a href="/">Home</a>
        <?php if ($_SESSION['logged_in']) : ?>
            <a href="/posts/create.php">New Article</a>
            <a href="/accounts/view.php?user=<?php echo $_SESSION['user']; ?>">My Articles</a>
            <a href="/accounts/edit.php?user=<?php echo $_SESSION['user']; ?>">Edit Profile</a>
            <a href="/accounts/logout.php">Logout</a>
        <?php else : ?>
            <a href="/accounts/register.php">Register</a>
            <a href="/accounts/login.php">Login</a>
        <?php endif; ?>
    </nav>
</header>