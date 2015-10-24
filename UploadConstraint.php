<?php

namespace kevin;

class UploadConstraint {
	
	const IMAGE_CONSTRAINT = "imageConstraint";
	const FILESIZE_CONSTRAINT = "fileSizeConstraint";
	public $constraints = array();
	// in mb
	public $allowedFileSize = null;
	
	public function imageConstraint($file) {
		if (!$file->isImg()) {
			throw new UploadConstraintException("Only Image is allowed to upload");
		}
		return true;
	}
	
	public function fileSizeConstraint($file) {
		if ($file->getFileSizeInMB() > $this->allowedFileSize) {
			throw new UploadConstraintException("File is bigger than the allowed size ({$this->allowedFileSize})");
		}
		return true;
	}
	
	public function hasPassConstraints($file) {
		
		$isPass = true;
		foreach ($this->constraints as $constraint) {
			$isPass = $isPass & (bool) $this->$constraint($file);
		}
		return $isPass;
	}
	
}