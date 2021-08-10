<?php
if (!empty($_COOKIE['blog_user'])) {
    header('Location: /index.php');
}
?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<div id="content">
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') :
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

        if (!empty($_POST['password2'])) {
            $password2 = htmlspecialchars($_POST['password2']);
        } else {
            $password2 = null;
        }

        $errors = array();

        if ($username == null) {
            $errors['username'] = 'Username is required.';
        } else {
            // also check for existing entry
            $check = "SELECT username FROM users WHERE username='" . $username . "' LIMIT 1";
            $result = $conn->query($check);
            if ($result->num_rows > 0) {
                $errors['username'] = 'This username is already taken.';
            } else {
                $error = "Something went wrong. " . $conn->error;
                echo $conn->error;
            }
        }

        if ($password == null) {
            $errors['password'] = 'Password is required.';
        }

        if ($password2 == null) {
            $errors['password2'] = 'Confirmed Password is required.';
        }

        if ($password != null && $password2 != null) {
            if (strcmp($password, $password2)) {
                $errors['password_mismatch'] = 'Confirmed password should be same as the password.';
            }
        }

        // echo "<br />We encounted (" . count($errors) . ") errors.<br />";

        if (count($errors) > 0) {
            foreach ($errors as $key => $value) {
                echo '<div class="form-error">' . $value . '</div>';
            }
    ?>
            <h3 class="form-caption">Register</h3>
            <form action="/accounts/register.php" method="POST" class="accounts-forms">
                <p>
                    <label for="username">Username: </label><br>
                    <input type="text" name="username" id="username" value="<?php echo $username; ?>" />
                </p>
                <p>
                    <label for="password">Password: </label><br>
                    <input type="password" name="password" id="password" value="<?php echo $password; ?>" />
                </p>
                <p>
                    <label for="password2">Confirm Password: </label><br>
                    <input type="password" name="password2" id="password2" value="" />
                </p>
                <p>
                    <button type="submit" name="submit" value="register" class="button button-ok">Register</button>
                </p>
            </form>
        <?php
            return null;
        }

        $password_hash = md5($password);

        $sql = "INSERT INTO users (username, password) VALUES('" . $username . "', '" . $password_hash . "')";

        if ($conn->query($sql) === TRUE) {
            header("Location: /accounts/login.php?welcome=$username");
            // echo "Done.";
        } else {
            // $error = "Something went wrong. " . $conn->error;
            echo $conn->error;
            echo "Error";
        }

        // var_dump($conn->error);

        // if (isset($error) && $error !== "") {
        //     echo $error;
        // }
        $conn->close();
    else : ?>
        <h3 class="form-caption">Register</h3>

        <form action="/accounts/register.php" method="POST" class="accounts-forms">
            <p>
                <label for="username">Username: </label><br>
                <input type="text" name="username" id="username" />
            </p>
            <p>
                <label for="password">Password: </label><br>
                <input type="password" name="password" id="password" />
            </p>
            <p>
                <label for="password2">Confirm Password: </label><br>
                <input type="password" name="password2" id="password2" />
            </p>
            <p>
                <button type="submit" name="submit" value="register" class="button button-ok">Register</button>
            </p>
        </form>
    <?php endif; ?>
</div>


<?php include($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
</body>

</html>