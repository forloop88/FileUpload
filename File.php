<?php

namespace kevin;

class File {

	protected $path;
	static protected $finfo;
	
	
	public function __construct($path) {
		$this->finfo = new \finfo();
		$this->path = $path;
	}
	
	
	public function getBaseName() {
		return pathinfo($this->path, PATHINFO_BASENAME);
	}
	
	public function getMIMEType() {
		self::$finfo->file($this->path, FILEINFO_MIME_TYPE);
	}
	
	public function getFileSizeInByte() {
		return filesize($this->path);
	}
	
	public function getFileSizeInMB() {
		return $this->bytesToMB($this->getFileSizeInByte());
	}
	
	public function getPath() {
		return $this->path;
	}
	
	public function isImg() {
		return (bool)getimagesize($this->path);
	}
	
	
	/**
	 * Convert bytes to MB
	 *
	 * @return float
	 */
	public static function bytesToMB($bytes) {
		return round(($bytes / 1048576), 2);
	}
	
	public static function MBToBytes($mb) {
		return $mb * 1048576;
	}
	
	
	
	
}