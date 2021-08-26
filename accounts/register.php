<?php
if (!isset($_SESSION)) {
    session_start();
}
?>

<?php
if ($_SESSION['logged_in']) {
    header('Location: /index.php');
}
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>
<main class="container">
    <section class="content">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') :
            if (!empty(trim($_POST['username']))) {
                $username = htmlspecialchars($_POST['username']);
            } else {
                $username = null;
            }

            if (!empty(trim($_POST['email']))) {
                $email = htmlspecialchars($_POST['email']);
            } else {
                $email = null;
            }

            if (!empty(trim($_POST['password']))) {
                $password = htmlspecialchars($_POST['password']);
            } else {
                $password = null;
            }

            if (!empty(trim($_POST['password2']))) {
                $password2 = htmlspecialchars($_POST['password2']);
            } else {
                $password2 = null;
            }

            $errors = array();

            if ($username == null) {
                $errors['username'] = 'Username is required.';
            } else if (strlen($username) < 6 || strlen($username) > 15) {
                $errors['username'] = "Username must be at least 6 and at most 15 characters long.";
            } else {
                // also check for existing username entry
                $check = "SELECT username FROM users WHERE username='" . $username . "' LIMIT 1";
                $result = $conn->query($check);
                if ($result->num_rows > 0) {
                    $errors['username'] = 'This username is already taken.';
                }
            }

            if ($email == null) {
                $errors['email'] = 'Email is required.';
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Enter the valid email address.';
            } else {
                // also check for existing email entry
                $check = "SELECT email FROM users WHERE email='" . $email . "' LIMIT 1";
                $result = $conn->query($check);
                if ($result->num_rows > 0) {
                    $errors['email'] = 'This email is already registered.';
                }
            }

            if ($password == null) {
                $errors['password'] = 'Password is required.';
            } else if (strlen($password) < 8) {
                $errors['password'] = "Password must contain at least 8 characters!";
            }

            if ($password2 == null) {
                $errors['password2'] = 'Confirmed Password is required.';
            }

            if ($password != null && $password2 != null) {
                if (strcmp($password, $password2)) {
                    $errors['password_mismatch'] = 'Confirmed password should be same as the password.';
                }
            }

            if (count($errors) > 0) {
                foreach ($errors as $key => $value) {
                    echo '<div class="form-error">' . $value . '</div>';
                }
            } else {

                $password_hash = md5($password);

                $sql = "INSERT INTO users (username, email, password) VALUES('" . $username . "', '" . $email . "', '" . $password_hash . "')";

                if ($conn->query($sql) === TRUE) {
                    $_SESSION['message'] = '<div class="success">Welcome ' . $username . " You've successfully registered on PHP Blog.</div>";
                    $_SESSION['user'] = $username;
                    $_SESSION['logged_in'] = true;
                    header("Location: /accounts/view.php?user=$username");
                } else {
                    $error = "Something went wrong. " . $conn->error;
                }
            }
        endif; ?>
        <form action="/accounts/register.php" method="POST" class="accounts-forms">
            <h3 class="form-caption">Register</h3>
            <div class="form-inner">
                <fieldset>
                    <label>Username: </label>
                    <input type="text" placeholder="Enter username" name="username" class="<?php if (isset($errors['username'])) : ?>input-error<?php endif; ?>" value="<?php echo $username; ?>" />
                    <span class="rule">(Username must be in between 6 to 15 characters.)</span>
                </fieldset>
                <fieldset>
                    <label>E-mail: </label>
                    <input type="text" placeholder="Enter e-mail" name="email" class="<?php if (isset($errors['email'])) : ?>input-error<?php endif; ?>" value="<?php echo $email; ?>" />
                </fieldset>
                <fieldset>
                    <label>Password: </label>
                    <input type="password" placeholder="Enter password" name="password" class="<?php if (isset($errors['password'])) : ?>input-error<?php endif; ?>" value="<?php echo $password; ?>" />
                    <span class="rule">(Password must contain at least 8 characters.)</span>
                </fieldset>
                <fieldset>
                    <label for="password2">Confirm Password: </label>
                    <input type="password" placeholder="Enter password again" class="<?php if (isset($errors['password2']) || isset($errors['password_mismatch'])) : ?>input-error<?php endif; ?>" value="<?php echo $password2; ?>" name="password2" />
                </fieldset>
                <fieldset>
                    <button type="submit" name="submit" value="register" class="button button-ok">Register</button>
                </fieldset>
                <fieldset>
                    <span class="rule">Already Have An Account? <a href="/accounts/login.php">Login here</a></span>
                </fieldset>
            </div>
        </form>
    </section>

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
</main>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>

<?php $conn->close(); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer-scripts.php") ?>

</body>

</html>