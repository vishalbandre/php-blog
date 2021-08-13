<?php session_start(); ?>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>

<main class="content">
    <?php
    if ($_SESSION['logged_in']) {
        header('Location: /index.php');
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

        if (count($errors) > 0) {
            foreach ($errors as $key => $value) {
                echo '<div class="form-error">' . $value . '</div>';
            }
    ?>
            <?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/forms/user-login.php") ?>
        <?php
        }

        $check = "SELECT username, role FROM users WHERE username='" . $username . "' and password='" . $password_hash . "' LIMIT 1";
        $result = $conn->query($check);
        if ($result->num_rows > 0) {
            foreach ($result as $key => $value) {
                $_SESSION['welcome-back'] = '<div class="success">Welcome back ' . $username . "</div>";
                $_SESSION['user'] = $value['username'];
                $_SESSION['logged_in'] = true;
                if($value['role'] == 'admin')
                    $_SESSION['is_admin'] = true;
            }
            header('Location: /accounts/view.php?user=' . $value['username']);
        }
    } else { ?>
            <?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/forms/user-login.php") ?>
    <?php } ?>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
</body>

</html>