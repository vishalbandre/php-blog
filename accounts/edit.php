<?php
if (!isset($_SESSION)) {
    session_start();
}

if (empty($_GET['user']) || !$_SESSION['logged_in'] || $_GET['user'] !== $_SESSION['user'] && !$_SESSION['is_admin']) {
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

        $errors = array();

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

        if (count($errors) > 0) {
            foreach ($errors as $key => $value) {
                echo '<div class="form-error">' . $value . '</div>';
            }
        } else {

            $sql = "UPDATE users SET email='$email' WHERE username='$username'";

            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] = '<div class="success">Profile updated successfully.</div>';
                header("Location: /accounts/view.php?user=$username");
            }
        }
    endif; ?>

    <h3 class="form-caption">Edit Profile</h3>
    <?php
    $check = "SELECT * FROM users WHERE username='" . $_GET['user'] . "'";
    $result = $conn->query($check);
    if ($result->num_rows > 0) {
    ?>
        <?php while ($row = $result->fetch_array()) : ?>
            <form action="" method="POST" class="accounts-forms">
                <p>
                    <label for="username">Username: </label><br>
                    <input type="text" name="username" class="<?php if (isset($errors['username'])) : ?>input-error<?php endif; ?>" value="<?php echo $row['username']; ?>" readonly />
                    <small>(Usernames can't be changed.)</small>
                </p>
                <p>
                    <label for="email">Email: </label><br>
                    <input type="text" name="email" class="<?php if (isset($errors['email'])) : ?>input-error<?php endif; ?>" value="<?php echo $row['email']; ?>" />
                </p>
                <p>
                    <button type="submit" name="submit" value="save" class="button button-ok">Save Profile</button>
                </p>
            </form>
    <?php endwhile;
    }
    ?>
</main>


<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>

<?php $conn->close(); ?>

</body>

</html>