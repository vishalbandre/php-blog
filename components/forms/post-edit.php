<h3 class="form-caption">Edit Article</h3>
<form action="" method="POST">
    <input name="id" type="hidden" value="<?php echo $id; ?>" />
    <input name="user" type="hidden" value="<?php echo $_SESSION['user']; ?>" />
    <p>
        <label for="title">Title: </label><br>
        <input type="text" name="title" class="<?php if (isset($errors['title'])) : ?>input-error<?php endif; ?>" value="<?php echo $title; ?>" />
    </p>
    <p>
        <label for="description">Description: </label><br>
        <textarea name="description" class="<?php if (isset($errors['description'])) : ?>input-error<?php endif; ?>" cols="30" rows="10"><?php echo $description; ?></textarea>
    </p>
    <p>
        <label for="body">Body: </label><br>
        <textarea name="body" class="<?php if (isset($errors['body'])) : ?>input-error<?php endif; ?>" cols="30" rows="10"><?php echo $body; ?></textarea>
    </p>
    <p>
        <button type="submit" name="submit" value="create" class="button button-ok">Save Post</button>
    </p>
</form>