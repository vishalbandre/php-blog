<li class="item">
    <img class="thumb" src="/uploads/images/<?php echo $row['imgpath']; ?>" alt="<?php echo $row['caption']; ?>">
    <!-- <input type="checkbox" name="thumb[]" <?php if(in_array($row['id'], $_POST['thumb'])): echo 'checked'; endif; ?> value="<?php echo $row['id']; ?>"> -->
    <input type="checkbox" name="thumb[]" <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') { if(in_array($row['id'], $_POST['thumb'])): echo 'checked'; endif; } else { if(in_array($row['id'], $old_images)): echo 'checked'; endif; } ?> value="<?php echo $row['id']; ?>">
</li>