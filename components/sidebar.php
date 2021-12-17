<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

// Use Language namespace to handle the languages
use Admin\Translation;

// check if 'lang' cookie is set
if (isset($_COOKIE['lang'])) {
    $site_lang = $_COOKIE['lang'];
} else {
    $site_lang = $lang;
}
?>

<aside class="sidebar">
    <div class="container-fluid mt-5">
        <form class="row subscription-form" action="/newsletters/subscribe.php" method="post">
            <h6 class="caption mb-3"><?php Translation::translate('Subscribe to Email Newsletter', $site_lang); ?>: </h6>
            <div class="col">
                <div class="input-group">
                    <input type="text" name="email" class="form-control" placeholder="<?php Translation::translate('Email', $site_lang); ?>">
                    <button type="submit" name="submit" value="create" class="btn btn-primary"><?php Translation::translate('Subscribe', $site_lang); ?></button>
                </div>
            </div>
        </form>
    </div>

    <h3 class="sidebar-caption"><?php Translation::translate('Editors', $site_lang); ?>:</h3>
    <ul class="sidebar-list">
        <?php
        $sql = "SELECT * FROM users LIMIT 10";
        $result = $conn->query($sql);
        if ($result) {
            if ($result->num_rows > 0) {
                $dataArray = array();
                while ($row = $result->fetch_array()) {
        ?>
                    <li>
                        <a href="/accounts/profile/<?php echo $row['username']; ?>">
                            <?php echo $row['username']; ?></a>
                    </li>
        <?php
                }
            }
        }
        ?>
    </ul>
</aside>