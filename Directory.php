<?php

namespace kevin;

class Directory {
	
	public static function isDirExist($dir) {
		return is_dir($dir);
	}
	
	public static function createDir($dir, $perm=0755, $rec=false) {
		if (!mkdir($dir, $perm, $rec)) {
			umask(0);
			throw new DirectoryException("Fail to create dir");
		}
		return true;
	}
	
}