<form action="/posts/create.php" method="POST" class="posts-forms">
    <h3 class="form-caption">New Post</h3>
    <div class="form-inner">
        <input name="user" type="hidden" value="<?php echo $_SESSION['user']; ?>" />
        <fieldset>
            <label>Title: </label><br>
            <input type="text" name="title" class="<?php if (isset($errors['title'])) : ?>input-error<?php endif; ?>" value="<?php echo $title; ?>" />
        </fieldset>
        <fieldset>
            <label>Description: </label><br>
            <textarea name="description" class="<?php if (isset($errors['description'])) : ?>input-error<?php endif; ?>" cols="30" rows="10"><?php echo $description; ?></textarea>
        </fieldset>
        <fieldset>
            <label>Body: </label><br>
            <textarea name="body" class="<?php if (isset($errors['body'])) : ?>input-error<?php endif; ?>" cols="30" rows="20"><?php echo $body; ?></textarea>
        </fieldset>
        <fieldset>
            <button type="submit" name="submit" value="create" class="button button-ok">Save Post</button>
        </fieldset>
    </div>
</form>