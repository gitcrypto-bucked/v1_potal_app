<?php 

$target =__DIR__.'/storage/thumb/thumb_';
$link = __DIR__.'/public/storage';
symlink($target, $link);
echo "Done";
