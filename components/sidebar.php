<?php
if (!isset($_SESSION)) {
    session_start();
}
?>

<aside class="sidebar">
    <div class="container-fluid mt-5">
        <form class="row subscription-form" action="/newsletters/subscribe.php" method="post">
            <h6 class="caption mb-3">Subscribe to Email Newsletter: </h6>
            <div class="col">
                <div class="input-group">
                    <input type="text" name="email" class="form-control" placeholder="Email">
                    <button type="submit" name="submit" value="create" class="btn btn-primary">Subscribe</button>
                </div>
            </div>
        </form>
    </div>

    <?php // Queued for change 
    ?>
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