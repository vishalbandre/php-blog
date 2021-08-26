<?php
if (!isset($_SESSION)) {
    session_start();
}
?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>

<main class="container">
    <section class="content">
        <?php
        if ($_SESSION['logged_in']) {
            header('Location: /index.php');
        }
        ?>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty(trim($_POST['username']))) {
                $username = htmlspecialchars($_POST['username']);
            } else {
                $username = null;
            }

            if (!empty(trim($_POST['password']))) {
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

            if (count($errors) > 0) {
                foreach ($errors as $key => $value) {
                    echo '<div class="form-error">' . $value . '</div>';
                }
            } else {
                $check = "SELECT username, role FROM users WHERE username='" . $username . "' and password='" . $password_hash . "' LIMIT 1";
                $result = $conn->query($check);
                if ($result->num_rows > 0) {
                    foreach ($result as $key => $value) {
                        $_SESSION['message'] = '<div class="success">Welcome back ' . $username . "</div>";
                        $_SESSION['user'] = $value['username'];
                        $_SESSION['logged_in'] = true;
                        if ($value['role'] == 'admin')
                            $_SESSION['is_admin'] = true;
                    }
                    header('Location: /accounts/view.php?user=' . $value['username']);
                }
            }
        }
        ?>
        <form action="/accounts/login.php" method="POST" class="accounts-forms">
            <h3 class="form-caption">Login</h3>
            <div class="form-inner">
                <fieldset>
                    <label for="username">Username: </label><br>
                    <input type="text" name="username" class="<?php if (isset($errors['username']) || isset($errors['check_credentials'])) : ?>input-error<?php endif; ?>" value="<?php echo $username; ?>" />
                </fieldset>
                <fieldset>
                    <label for="password">Password: </label><br>
                    <input type="password" name="password" class="<?php if (isset($errors['password']) || isset($errors['check_credentials'])) : ?>input-error<?php endif; ?>" value="<?php echo $password; ?>" />
                </fieldset>
                <fieldset>
                    <button type="submit" name="submit" value="login" class="button button-ok">Login</button>
                </fieldset>
                <fieldset>
                    <a class="button-link" href="/accounts/forgot-password.php">Forgot Password?</a>
                </fieldset>
                <fieldset>
                    <span class="rule">Don't Have An Account? <a href="/accounts/register.php">Register here</a></span>
                </fieldset>
            </div>
        </form>
    </section>

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
</main>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>