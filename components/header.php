<div id="nav">
    <a href="/" id="branding">PHP Blog</a>
    <ul id="topnav">
        <li>
            <a href="/">Home</a>
        </li>
        <?php if (isset($_COOKIE['blog_user'])) : ?>
            <li>
                <a href="/posts/create.php">Add New Article</a>
            </li>
            <li>
                <a href="/accounts/logout.php">Logout</a>
            </li>
        <?php else : ?>
            <li>
                <a href="/accounts/register.php">Register</a>
            </li>
            <li>
                <a href="/accounts/login.php">Login</a>
            </li>
        <?php endif; ?>
    </ul>
</div>