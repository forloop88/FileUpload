<?php
include_once 'File.php';
include_once 'Upload.php';
include_once 'UploadedTmpFile.php';
include_once 'UploadConstraint.php';
include_once 'Directory.php';
include_once 'String.php';
include_once 'Exception/FileUploadException.php';
include_once 'Exception/UploadConstraintException.php';
include_once 'Exception/DirectoryException.php';


try {
$upload = new kevin\Upload("./upload/1/2/3/", "file");
$upload->createDirIfNotExist = true;
$uploadConstraint = new kevin\UploadConstraint();

$uploadConstraint->constraints = array (
		kevin\UploadConstraint::IMAGE_CONSTRAINT,
		kevin\UploadConstraint::FILESIZE_CONSTRAINT,
);

$uploadConstraint->allowedFileSize = 3;
$upload->uploadConstraint = $uploadConstraint;

$tmpFiles = $upload->getUploadedTmpFilesIfPassed();
print_r($tmpFiles); 
// $upload->upload();
// print_r($upload->getSavedFiles());

} catch (kevin\FileUploadException $e) {
	echo $e->getMessage();
}