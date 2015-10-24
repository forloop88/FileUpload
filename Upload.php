<?php

/**
 * @author Kevin
 *
 */
namespace kevin;

class Upload {
	
	protected $destDir = null;
	protected $savedFiles = array();
	protected $uploadKey = null;
	public $isUseOrgName = false;
	public $permission = 0755;
	public $createDirIfNotExist = false;
	public $uploadConstraint = null;
	
	public function __construct($destDir, $uploadKey) {
		$this->setDestDir($destDir);
		$this->setUploadKey($uploadKey);
		$this->uploadConstraint = new UploadConstraint();
	}
	
	/**
	 * 
	 * @return array
	 */
	public function getSavedFiles() {
		return $this->savedFiles;
	}
	
	public function setDestDir($destDir) {
		$this->destDir = $destDir;
	}
	
	public function getDestDir() {
		return $this->destDir;
	}
	
	public function setUploadKey($uploadKey) {
		$this->uploadKey = $uploadKey;
	}
	
	public function getUploadKey() {
		return $this->uploadKey;
	}
	
	
	/*  
	 * 
	 * 
	 * */
	
	protected function convertUploadedTmpFInfoFldToAry($tmpFInfo, $fld) {
		$result = array();
		
		if (is_array($tmpFInfo[$fld])) {
			$result = $tmpFInfo[$fld];
		} else {
			$result[] = $tmpFInfo[$fld];
		}
		
		return $result;
		
	}
	
	
	public function getUploadedTmpFInfo($key) {
		
		if (empty($_FILES[$key]) || empty($_FILES[$key][error])) {
			// no file is uploaded
			throw new FileUploadException();
		}
		return $_FILES[$key];
		
	}
	
	
	public function getUploadedTmpFiles() {
		$tmpFInfo = $this->getUploadedTmpFInfo($this->uploadKey);
		if ($this->isUploadedSuccessfully($tmpFInfo)) {
			return $this->fillUploadedTmpFiles($tmpFInfo);
		}
	}
	
	
	protected function fillUploadedTmpFiles($tmpFInfo) {
		
		$uploadedTmpFiles = array();
		
		$tmp_names = $this->convertUploadedTmpFInfoFldToAry($tmpFInfo, "tmp_name");
		$names = $this->convertUploadedTmpFInfoFldToAry($tmpFInfo, "name");
		
		foreach ($tmp_names as $key => $tmp_name) {
			$name = $names[$key];
			$uploadedTmpFiles[] = new UploadedTmpFile($tmp_name, $name);
		}
		return $uploadedTmpFiles;
	}
	
	
	
	public function isUploadedSuccessfully($tmpFInfo) {
		
		$errors = self::convertUploadedTmpFInfoFldToAry($tmpFInfo, "error");
		
		foreach ($errors as $key => $err) {
			if ($err > 0) {
				throw new FileUploadException();
			}
		}
		
		return true;
	}
	
	
	
	public function upload() {

// 		$tmpFiles = $this->getUploadedTmpFiles();
		$tmpFiles = $this->getUploadedTmpFilesIfPassed();
		
		
		foreach ($tmpFiles as $tmpFile) {
// 			if ($this->uploadConstraint->hasPassConstraints($tmpFile)) {
				
// 				if (!$this->isUseOrgName) $tmpFile->setReName(String::generateUniqueStr());
// 				else $tmpFile->setReName($tmpFile->getOrgName());

				
// 				if ($this->moveUploadedFile($tmpFile)) {
// 					$savedFile = new File($tmpFile->getRePath());
// 					$this->savedFiles[] = $savedFile;
// 				}
// 			}
			if (!$this->isUseOrgName) $tmpFile->setReName(String::generateUniqueStr());
			else $tmpFile->setReName($tmpFile->getOrgName());
			
			
			if ($this->moveUploadedFile($tmpFile)) {
				$savedFile = new File($tmpFile->getRePath());
				$this->savedFiles[] = $savedFile;
			}
		}

	}
	
	public function getUploadedTmpFilesIfPassed() {
		try {
			$isPass = true;
			$tmpFiles = $this->getUploadedTmpFiles();
			foreach ($tmpFiles as $tmpFile) {
				$isPass = $isPass & $this->uploadConstraint->hasPassConstraints($tmpFile);
			}
			if ($isPass) return $tmpFiles;
		} catch (UploadConstraintException $e) {
			throw new FileUploadException($e->getMessage());
		}
		
	}
	

	protected function moveUploadedFile($tmpFile) {
	
		if (!Directory::isDirExist($this->destDir)) {
			if ($this->createDirIfNotExist) {
				Directory::createDir($this->destDir, $this->permission, true);
			} else {
				throw new FileUploadException("Fail to move uploaded temp file, because the destination dir is not exist");
			}
		}
	
		if (move_uploaded_file($tmpFile->getPath(), $this->destDir . $tmpFile->getReName())) {
			$tmpFile->setRePath($this->destDir . $tmpFile->getReName());
			return true;
		} else {
			throw new FileUploadException("Fail to move uploaded file");
		}
	
	}
}