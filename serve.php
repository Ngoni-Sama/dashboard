<?php
if (isset($_FILES['Pictures'])) {
    $pictures = $_FILES['Pictures'];
    $fileCount = count($pictures["name"]);
    $target_file="public/images/";
    for ($i = 0; $i < $fileCount; $i++) {
        ?>
        <p>File #<?= $i+1 ?>:</p>
        <p>
            Name: <?= $pictures["name"][$i] ?><br>
            Temporary file: <?= $pictures["tmp_name"][$i] ?><br>
            Type: <?= $pictures["type"][$i] ?><br>
            Size: <?= $pictures["size"][$i] ?><br>
            Error: <?= $pictures["error"][$i] ?><br>
        </p>

        <?php
        move_uploaded_file($pictures["tmp_name"][$i], $target_file.$pictures["name"][$i]);
    }
}

if (isset($_FILES['Sounds'])) {
    $sounds = $_FILES['Sounds'];
    $fileCount = count($sounds["name"]);
    $target_file="public/sounds/";
    for ($i = 0; $i < $fileCount; $i++) {
        ?>
        <p>File #<?= $i+1 ?>:</p>
        <p>
            Name: <?= $sounds["name"][$i] ?><br>
            Temporary file: <?= $sounds["tmp_name"][$i] ?><br>
            Type: <?= $sounds["type"][$i] ?><br>
            Size: <?= $sounds["size"][$i] ?><br>
            Error: <?= $sounds["error"][$i] ?><br>
        </p>

        <?php
        move_uploaded_file($sounds["tmp_name"][$i], $target_file.$sounds["name"][$i]);
    }
}
?>