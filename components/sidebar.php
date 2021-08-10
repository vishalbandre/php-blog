<div id="sidebar">
    <h3 class="caption">Editors:</h3>
    <ul id="editor-list">
    <?php
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $dataArray = array();
        while ($row = $result->fetch_array()) {
    ?>
        <li><a href="/accounts/view.php?user=<?php echo $row['username']; ?>"><?php echo $row['username']; ?></a></li>
    <?php
        }
    }
    ?>
    </ul>
</div>