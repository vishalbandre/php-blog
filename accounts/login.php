<?php include("../config.php") ?>
<?php include("../header.php") ?>
<div id="content">
    <?php
    if (isset($_COOKIE['blog_user'])) {
        header('Location: /index.php');
    }
    if (!empty($_GET['welcome'])) {
    ?>
        <div id="welcome">Welcome <?php echo $_GET['welcome']; ?>! Thanks for the registration. Now you can login and start contributing to this blog.</div>
    <?php
    }
    ?>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['username'])) {
            $username = htmlspecialchars($_POST['username']);
        } else {
            $username = null;
        }

        if (!empty($_POST['password'])) {
            $password = htmlspecialchars($_POST['password']);
        } else {
            $password = null;
        }

        $errors = array();

        if ($username == null) {
            $errors['username'] = 'Username is required.';
        }

        if ($password == null) {
            $errors['password'] = 'Password is required.';
        }

        $password_hash = md5($password);

        $check = "SELECT username FROM users WHERE username='" . $username . "' and password='" . $password_hash . "' LIMIT 1";
        $result = $conn->query($check);
        if ($result->num_rows == 0) {
            $errors['check_credentials'] = 'Please check your credentials and try again.';
        }

        // echo "<br />We encounted (" . count($errors) . ") errors.<br />";

        if (count($errors) > 0) {
            foreach ($errors as $key => $value) {
                echo '<div class="form-error">' . $value . '</div>';
            }
    ?>
            <h3 class="form-caption">Login</h3>
            <form action="/accounts/login.php" method="POST" class="accounts-forms">
                <p>
                    <label for="username">Username: </label><br>
                    <input type="text" name="username" id="username" value="<?php echo $username; ?>" />
                </p>
                <p>
                    <label for="password">Password: </label><br>
                    <input type="password" name="password" id="password" value="" />
                </p>
                <p>
                    <button type="submit" name="submit" value="login" class="button button-ok">Login</button>
                </p>
            </form>
        <?php
            // return null;
        }

        $check = "SELECT username FROM users WHERE username='" . $username . "' and password='" . $password_hash . "' LIMIT 1";
        $result = $conn->query($check);
        if ($result->num_rows > 0) {
            foreach ($result as $key => $value)
                setcookie("blog_user", $value['username'], time() + (86400 * 30), '/');
            // echo $_COOKIE["blog_user"];
            header('Location: /accounts/view.php?user=' . $value['username']);
        }

    } else { ?>
        <h3 class="form-caption">Login</h3>
        <form action="/accounts/login.php" method="POST" class="accounts-forms">
            <p>
                <label for="username">Username: </label><br>
                <input type="text" name="username" id="username" />
            </p>
            <p>
                <label for="password">Password: </label><br>
                <input type="password" name="password" id="password" />
            </p>
            <p>
                <button type="submit" name="submit" value="login" class="button button-ok">Login</button>
            </p>
        </form>
    <?php } ?>
</div>
<?php include("../sidebar.php") ?>
<?php include("../footer.php") ?>