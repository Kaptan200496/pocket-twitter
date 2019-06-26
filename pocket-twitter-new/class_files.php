<?php 
class files {
	public $id;
	public $file_name;
	public $author_id;
	public $created;
	public $modified;
	public $active;

	function __construct($jsonstring) {
		$decodeObject = json_decode($jsonstring);
		$this->id = $decodeObject->id;
		$this->file_name = $decodeObject->file_name;
		$this->author_id = $decodeObject->author_id;
		$this->created = $decodeObject->created;
		$this->modified = $decodeObject->modified;
		$this->active = $decodeObject->active;

	}

	public static function loadFileIntoDB ($fileData) {
	header("contentType", "application/json");
	copy($_FILES["fileWeUpload"]["tmp_name"], getcwd()."/".$_FILES["fileWeUpload"]["name"]);

	$targetFileName = getcwd()."/".$_FILES["fileWeUpload"]["name"];


	$insertObject = "
		INSERT INTO images (
			file_name
		)
		VALUES (
			'{$targetFileName}'
		)
	";
	DB::$connection->query($insertObject);

	}
}
?>
