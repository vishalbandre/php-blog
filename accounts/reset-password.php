<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/config.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>

<main class="content">
    <?php
    if (isset($_POST['password']) && $_POST['reset_link_token'] && $_POST['email']) {
        $emailId = $_POST['email'];
        $token = $_POST['reset_link_token'];

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
            <?php
            $query = mysqli_query(
                $conn,
                "SELECT * FROM users WHERE reset_link_token='" . $token . "' and email='" . $email . "';"
            );
            $curDate = date("Y-m-d H:i:s");
            if (mysqli_num_rows($query) > 0) {
                $row = mysqli_fetch_array($query);
                if ($row['exp_date'] >= $curDate) { ?>
                    <form action="" method="post" class="accounts-forms">
                        <input type="hidden" name="email" value="<?php echo $email; ?>">
                        <input type="hidden" name="reset_link_token" value="<?php echo $token; ?>">
                        <p>
                            <label>Password</label>
                            <input type="password" value="<?php echo $password; ?>" name='password'>
                        </p>
                        <p>
                            <label>Confirm Password</label>
                            <input type="password" class="<?php if (isset($errors['password2'])) : ?>input-error<?php endif; ?>" value="<?php echo $password2; ?>" name='password2'>
                        </p>
                        <button type="submit" name="new-password" class="button button-ok">Save New Password</button>
                    </form>
                <?php }
            } else { ?>
            <?php
            }
        } else {
            ?>

            <?php

            $emailId = $_POST['email'];
            $token = $_POST['reset_link_token'];

            $query = mysqli_query($conn, "SELECT * FROM users WHERE reset_link_token='" . $token . "' and email='" . $emailId . "'");

            $row = mysqli_num_rows($query);

            if ($row) {
                $password = md5($password);
                mysqli_query($conn, "UPDATE users set  password='" . $password . "', reset_link_token=NULL, exp_date=NULL WHERE email='" . $emailId . "'");
                header("Refresh:5; url=/accounts/login.php");
            ?>
                <p>Your password has been updated successfully.</p>
                <p>You will be redirected to login within 5 seconds. If it doesn't redirect within 5 seconds, <a href="/accounts/login.php">click here</a>.</p>
            <?php
            } else {
                echo "<p>Something goes wrong. Please try again</p>";
            }
        }
    }

    if ($_GET['key'] && $_GET['token']) {
        $email = $_GET['key'];
        $token = $_GET['token'];
        $query = mysqli_query(
            $conn,
            "SELECT * FROM users WHERE reset_link_token='" . $token . "' and email='" . $email . "';"
        );
        $curDate = date("Y-m-d H:i:s");
        if (mysqli_num_rows($query) > 0) {
            $row = mysqli_fetch_array($query);
            if ($row['exp_date'] >= $curDate) { ?>
                <form action="" method="post" class="accounts-forms">
                    <input type="hidden" name="email" value="<?php echo $email; ?>">
                    <input type="hidden" name="reset_link_token" value="<?php echo $token; ?>">
                    <p>
                        <label>Password</label>
                        <input type="password" class="<?php if (isset($errors['password'])) : ?>input-error<?php endif; ?>" value="<?php echo $password; ?>" name='password'>
                    </p>
                    <p>
                        <label>Confirm Password</label>
                        <input type="password" class="<?php if (isset($errors['password2'])) : ?>input-error<?php endif; ?>" value="<?php echo $password2; ?>" name='password2'>
                    </p>
                    <button type="submit" name="new-password" class="button button-ok">Save New Password</button>
                </form>
            <?php }
        } else { ?>

    <?php
        }
    }
    ?>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/sidebar.php") ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/components/footer.php") ?>
</body>

</html>