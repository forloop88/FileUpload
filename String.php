<?php

namespace kevin;

class String {
	
	/**
	 * Generate a unique string
	 *
	 * @return string
	 */
	public static function generateUniqueStr($str="") {
		return sha1(mt_rand(1, 9999) . $str . uniqid()) . time();
	}
	
}
