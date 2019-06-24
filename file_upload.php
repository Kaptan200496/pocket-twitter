<?php 
header("contentType", "application/json");
copy($_FILES["fileWeUpload"]["tmp_name"], getcwd()."/".$_FILES["fileWeUpload"]["name"]);
print $_POST["arbitraryText"];
?>
