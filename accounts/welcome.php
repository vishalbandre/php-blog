<?php include("../config.php") ?>
<?php 
if(isset($_COOKIE['blog_user'])) {
    echo "Already Logged in as: " . $_COOKIE['blog_user'];
    die();
}
?>
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

    $errors = array();

    if ($username == null) {
        $errors['username'] = 'Username is required.';
    }

    if ($password == null) {
        $errors['password'] = 'Password is required.';
    }

    // echo "<br />We encounted (" . count($errors) . ") errors.<br />";

    if (count($errors) > 0) {
        foreach ($errors as $key => $value) {
            echo '<div class="form-error">' . $value . '</div>';
        }
?>
        <form action="/accounts/welcome.php" method="POST">
            <p>
                <label for="username">Username: </label><br>
                <input type="text" name="username" id="username" value="<?php echo $username; ?>" />
            </p>
            <p>
                <label for="password">Password: </label><br>
                <input type="password" name="password" id="password" value="" />
            </p>
            <p>
                <button type="submit" name="submit" value="login" class="button button-ok">Login</button>
            </p>
        </form>
    <?php
        return null;
    }

    $password_hash = md5($password);

    $check = "SELECT username FROM users WHERE username='" . $username . "' and password='" . $password_hash . "' LIMIT 1";
    $result = $conn->query($check);
    if ($result->num_rows > 0) {
        foreach($result as $key => $value)
            setcookie("blog_user", $value['username'], time() + (86400 * 30), '/');
        echo $_COOKIE["blog_user"];
        // header('Location: /index.php');
    } else {
        $error = "Something went wrong. " . $conn->error;
        echo $conn->error;
    }

    $conn->close();
else : ?>
    <?php echo $_SESSION['user']; ?>
    <h3>Thanks for registering as an author.</h3>
    <h4>Now, you can login and start writing the articles.</h4>
    <form action="/accounts/welcome.php" method="POST">
        <p>
            <label for="username">Username: </label><br>
            <input type="text" name="username" id="username" />
        </p>
        <p>
            <label for="password">Password: </label><br>
            <input type="password" name="password" id="password" />
        </p>
        <p>
            <button type="submit" name="submit" value="login" class="button button-ok">Login</button>
        </p>
    </form>
<?php endif; ?>
<?php // include("footer.php"); 
?>
<?php include("../footer.php") ?>