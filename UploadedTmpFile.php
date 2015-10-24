<?php

namespace kevin;

class UploadedTmpFile extends File {

	protected $orgName;
	protected $reName;
	protected $rePath;
	
	public function __construct($path, $orgName) {
		parent::__construct($path);
		$this->orgName = $orgName;
	}
	
	public function getOrgName() {
		return $this->orgName;
	}
	
	public function setReName($name) {
		$this->reName = $name;
	}
	
	public function getReName() {
		return $this->reName;
	}
	
	public function setRePath($path) {
		$this->rePath = $path;
	}
	
	public function getRePath() {
		return $this->rePath;
	}
}