<header>
    <a href="/" class="branding">PHP Blog</a>
    <nav>
        <a href="/">Home</a>
        <?php if (isset($_COOKIE['blog_user'])) : ?>
            <a href="/posts/create.php">Add New Article</a>
            <a href="/accounts/logout.php">Logout</a>
        <?php else : ?>
            <a href="/accounts/register.php">Register</a>
            <a href="/accounts/login.php">Login</a>
        <?php endif; ?>
    </nav>
</header>