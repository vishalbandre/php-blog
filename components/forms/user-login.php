<h3 class="form-caption">Login</h3>
<form action="/accounts/login.php" method="POST" class="accounts-forms">
    <p>
        <label for="username">Username: </label><br>
        <input type="text" name="username" class="<?php if (isset($errors['check_credentials'])) : ?>input-error<?php endif; ?>" value="<?php echo $username; ?>" />
    </p>
    <p>
        <label for="password">Password: </label><br>
        <input type="password" name="password" class="<?php if (isset($errors['check_credentials'])) : ?>input-error<?php endif; ?>" value="<?php echo $password; ?>" />
    </p>
    <p>
        <a class="button-link" href="/accounts/forgot-password.php">Forgot Password?</a> <button type="submit" name="submit" value="login" class="button button-ok">Login</button>
    </p>
</form>