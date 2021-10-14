<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/components/config.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/components/head.php") ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/components/header.php") ?>

<main class="container-fluid">
    <div class="row">
        <div class="col-md-8">

            <div class="content-area">
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
                                <form action="" method="post" class="form form-small">
                                    <input type="hidden" name="email" value="<?php echo $email; ?>">
                                    <input type="hidden" name="reset_link_token" value="<?php echo $token; ?>">
                                    <h3 class="form-caption">Reset Password</h3>
                                    <div class="form-inner">
                                        <fieldset>
                                            <label>Password</label>
                                            <input type="password" placeholder="Enter Password" class="<?php if (isset($errors['password'])) : ?>input-error<?php endif; ?>" value="<?php echo $password; ?>" name='password'>
                                            <span class="rule">(Username must be in between 6 to 15 characters.)</span>
                                        </fieldset>
                                        <fieldset>
                                            <label>Confirm Password</label>
                                            <input type="password" placeholder="Enter Confirm Password" class="<?php if (isset($errors['password2'])) : ?>input-error<?php endif; ?>" value="<?php echo $password2; ?>" name='password2'>
                                        </fieldset>
                                        <fieldset>
                                            <button type="submit" name="new-password" class="button button-ok">Save New Password</button>
                                        </fieldset>
                                    </div>
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
                            <form action="" method="post" class="form form-small">
                                <input type="hidden" name="email" value="<?php echo $email; ?>">
                                <input type="hidden" name="reset_link_token" value="<?php echo $token; ?>">
                                <h3 class="form-caption">Reset Password</h3>
                                <div class="form-inner">
                                    <fieldset>
                                        <label class="form-label">Password</label>
                                        <input type="password" placeholder="Enter Password" class="form-control m-0 <?php if (isset($errors['password'])) : ?>input-error<?php endif; ?>" value="<?php echo $password; ?>" name='password'>
                                        <span class="rule">(Username must be in between 6 to 15 characters.)</span>
                                    </fieldset>
                                    <fieldset>
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" placeholder="Enter Confirm Password" class="form-control m-0 <?php if (isset($errors['password2'])) : ?>input-error<?php endif; ?>" value="<?php echo $password2; ?>" name='password2'>
                                    </fieldset>
                                    <fieldset>
                                        <button type="submit" name="new-password" class="btn btn-dark">Save New Password</button>
                                    </fieldset>
                                </div>
                            </form>
                        <?php }
                    } else { ?>

                <?php
                    }
                }
                ?>
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