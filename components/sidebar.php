<?php
if (!isset($_SESSION)) {
    session_start();
}
?>

<aside class="sidebar">
    <?php if ($_SESSION['logged_in']) : ?>
        <h3 class="sidebar-caption">Carousels:</h3>
        <ul class="sidebar-list">
            <li>
                <a href="/carousels/">All Carousels</a>
            </li>
            <li>
                <a href="/carousels/create.php">Add New Carousel</a>
            </li>
            <?php if (isset($_SESSION['is_admin'])) : ?>
                <li>
                    <a href="/carousels/categories/index.php">All Categories</a>
                </li>
                <li>
                    <a href="/carousels/categories/create.php">Add New Category</a>
                </li>
            <?php endif; ?>
        </ul>

        <hr>
    <?php endif; ?>

    <form action="/newsletters/subscribe.php" method="post" class="subscribe">
        <fieldset>
            <input type="email" name="email" class="input-subscriber">
            <button type="submit" name="submit" value="create" class="button button-ok">Subscribe to Newsletter</button>
        </fieldset>
    </form>

    <?php // Queued for change ?>
    <h3 class="sidebar-caption">Editors:</h3>
    <ul class="sidebar-list">
        <?php
        $sql = "SELECT * FROM users LIMIT 10";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $dataArray = array();
            while ($row = $result->fetch_array()) {
        ?>
                <li>
                    <a href="/accounts/view.php?user=<?php echo $row['username']; ?>">
                        <?php echo $row['username']; ?></a>
                </li>
        <?php
            }
        }
        ?>
    </ul>
</aside>