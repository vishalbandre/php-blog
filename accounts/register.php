<?php session_start(); ?>

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

        if (count($errors) > 0) {
            foreach ($errors as $key => $value) {
                echo '<div class="form-error">' . $value . '</div>';
            }
    ?>
            <?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/forms/user-registration.php") ?>
        <?php
            return null;
        }

        $password_hash = md5($password);

        $sql = "INSERT INTO users (username, password) VALUES('" . $username . "', '" . $password_hash . "')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['welcome'] = '<div class="success">Welcome ' . $username . " You've successfully registered on PHP Blog.</div>";
            $_SESSION['user'] = $username;
            $_SESSION['logged_in'] = true;
            header("Location: /accounts/view.php?user=$username");
        } else {
            $error = "Something went wrong. " . $conn->error;
        }

        $conn->close();
    else : ?>
        <?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/forms/user-registration.php") ?>
    <?php endif; ?>
</main>


<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
</body>

</html>