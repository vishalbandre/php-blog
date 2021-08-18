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
<main class="content">
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
    <h3 class="form-caption">Register</h3>
    <form action="/accounts/register.php" method="POST" class="accounts-forms">
        <p>
            <label for="username">Username: </label><br>
            <input type="text" name="username" class="<?php if (isset($errors['username'])) : ?>input-error<?php endif; ?>" value="<?php echo $username; ?>" />
        </p>
        <p>
            <label for="email">Email: </label><br>
            <input type="text" name="email" class="<?php if (isset($errors['email'])) : ?>input-error<?php endif; ?>" value="<?php echo $email; ?>" />
        </p>
        <p>
            <label for="password">Password: </label><br>
            <input type="password" name="password" class="<?php if (isset($errors['password'])) : ?>input-error<?php endif; ?>" value="<?php echo $password; ?>" />
            <small>(Password must contain at least 8 characters.)</small>
        </p>
        <p>
            <label for="password2">Confirm Password: </label><br>
            <input type="password" class="<?php if (isset($errors['password2']) || isset($errors['password_mismatch'])) : ?>input-error<?php endif; ?>" value="<?php echo $password2; ?>" name="password2" />
        </p>
        <p>
            <button type="submit" name="submit" value="register" class="button button-ok">Register</button>
        </p>
        <small>Already Have An Account?<a href="/accounts/login.php">Login here</a></small>
    </form>
</main>


<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>

<?php $conn->close(); ?>

</body>

</html>