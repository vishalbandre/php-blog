<?php
if (!isset($_SESSION)) {
    session_start();
}

use User\Auth\Auth;

?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/accounts/models/Auth.php") ?>

<main class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="content-area">
                <section class="content">
                    <?php
                    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
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
                                echo '<div class="alert alert-danger">' . $value . '</div>';
                            }
                        } else {

                            // Login with username & password
                            $login = Auth::login($username, $password_hash);

                            if ($login) {
                                while ($row = $login->fetch_array()) {
                                    $_SESSION['message'] = '<div class="alert alert-success">Welcome back ' . $username . "</div>";
                                    $_SESSION['user'] = $row['username'];
                                    $_SESSION['logged_in'] = true;
                                    if ($row['role'] == 'admin')
                                        $_SESSION['is_admin'] = true;
                                }
                                header('Location: /accounts/view.php?user=' . $username);
                                die();
                            }
                        }
                    }
                    ?>

                    <form action="/accounts/login.php" method="POST" class="form form-small">
                        <h3 class="form-caption">Login</h3>
                        <div class="form-inner">
                            <fieldset>
                                <label for="username" class="form-label">Username: </label><br>
                                <input type="text" name="username" class="form-control m-0 <?php if (isset($errors['username']) || isset($errors['check_credentials'])) : ?>input-error<?php endif; ?>" value="<?php if(isset($username)) { echo $username; } ?>" />
                            </fieldset>
                            <fieldset>
                                <label for="password" class="form-label">Password: </label><br>
                                <input type="password" name="password" class="form-control m-0 <?php if (isset($errors['password']) || isset($errors['check_credentials'])) : ?>input-error<?php endif; ?>" value="" />
                            </fieldset>
                            <fieldset>
                                <button type="submit" name="submit" value="login" class="btn btn-dark">Login</button>
                            </fieldset>
                            <fieldset>
                                <a class="btn btn-outline-secondary" href="/accounts/forgot-password.php">Forgot Password?</a>
                            </fieldset>
                            <fieldset>
                                <span class="rule">Don't Have An Account? <a href="/accounts/register.php">Register here</a></span>
                            </fieldset>
                        </div>
                    </form>
                </section>
            </div>
        </div>
        <div class="col-md-4">
            <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
        </div>
    </div>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>
</body>

</html>