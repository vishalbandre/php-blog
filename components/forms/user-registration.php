<h3 class="form-caption">Register</h3>
<form action="/accounts/register.php" method="POST" class="accounts-forms">
    <p>
        <label for="username">Username: </label><br>
        <input type="text" name="username" class="<?php if (isset($errors['username'])) : ?>input-error<?php endif; ?>" value="<?php echo $username; ?>" />
    </p>
    <p>
        <label for="password">Password: </label><br>
        <input type="password" name="password" class="<?php if (isset($errors['password'])) : ?>input-error<?php endif; ?>" value="<?php echo $password; ?>" />
    </p>
    <p>
        <label for="password2">Confirm Password: </label><br>
        <input type="password" class="<?php if (isset($errors['password2']) || isset($errors['password_mismatch'])) : ?>input-error<?php endif; ?>" value="<?php echo $password2; ?>" name="password2" />
    </p>
    <p>
        <button type="submit" name="submit" value="register" class="button button-ok">Register</button>
    </p>
</form>