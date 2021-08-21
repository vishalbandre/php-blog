<aside class="sidebar">
    <h3 class="sidebar-caption">Editors:</h3>
    <ul class="editor-list">
        <?php
        $sql = "SELECT * FROM users";
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