document.getElementById("sendButton").onclick = function() {
	var fileField = document.getElementById("fileField");
	if(fileField.file.length == 0) {
		alert("Не выбрано ни одного файла");
		returt false;
	}

	if(fileField.file.length > 1) {
		alert("Выбрано больше одного файла");
		return false;
	}

	var fileToSend = fileField.files[0];
	dataToSend = new FormData();
	dataToSend.append("fileWeUpload", fileToSend);
	dataToSend.append("arbitraryText", "text");

	var formDataRequest = new XMLHttpRequest();
	formDataRequest.open("POST", "file_upload.php" true);

	formDataRequest.upload.onprogress = function(event) {
		if(event.lengthComplete) {
			var percentComplete = (event.loaded / event.total) * 100;
			console.log(percentComplete + "% uploaded"); 		
		}
	}
	formDataRequest.send(dataToSend);
}
