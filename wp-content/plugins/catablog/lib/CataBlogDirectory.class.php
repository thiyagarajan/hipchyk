<?php

class CataBlogDirectory {
	private $filearray = array();
	private $directory = "";
	
	
	public function __construct($directory) {
		$this->directory = $directory;
		$d = "";
		if (is_dir($directory)) {
			$d = opendir($directory) or exit(__("Could not open directory.", 'catablog'));
			while (false !== ($f=readdir($d))) {
				if (is_file("$directory/$f")) {
					if (substr($f, 0, 1) != '.') {
						$this->filearray[] = $f;
					}
				}
			}
			closedir($d);
		}
		else {
			return false;
		}
	}
	
	
	public function isDirectory() {
		return is_dir($this->directory);
	}
	
	public function getFileArray() {
		return $this->filearray;
	}
	
	public function indexOrder() {
		sort($this->filearray);
	}
	
	public function naturalCaseInsensitiveOrder() {
		natcasesort($this->filearray);
	}
	
	public function getCount() {
		return count($this->filearray);
	}
	
	public function getDirectorySize() {
		$filesize = 0;
		foreach ($this->filearray as $file) {
			$filesize = $filesize + filesize($this->directory . "/$file");
		}
		
		return $filesize;
	}
	
	
	
	public function checkAllImages() {
		$bln = true;
		$extension = "";
		$types = array("jpg", "jpeg", "gif", "png");
		foreach ($this->filearray as $key => $value) {
			$extension = substr($value, (strrpos($value, ".") + 1));
			$extension = strtolower($extension);
			if (!in_array($extension, $types)) {
				$bln = false;
				break;
			}
		}
		return $bln;
	}
	
	
	
	public function checkAllSpecificType($extension) {
		$extension = strtolower($extension);
		$bln = true;
		$ext = "";
		foreach ($this->filearray as $key => $value) {
			$ext = substr($value, (strrpos($value, ".") + 1));
			$ext = strtolower($ext);
			if ($extension != $ext) {
				$bln = false;
				break;
			}
		}
		return $bln;
	}
	
	
	
	public function filter($extension) {
		$extension = strtolower($extension);
		foreach ($this->filearray as $key => $value) {
			$ext = substr($value, (strrpos($value, ".") + 1));
			$ext = strtolower($ext);
			if ($ext != $extension) {
				unset($this->filearray[$key]);
			}
		}
	}
	
	
	
	public function removeFilter() {
		unset($this->filearray);
		$d = "";
		$d = opendir($this->directory) or exit(__("Could not open directory.", 'catablog'));
		while (false !== ($f = readdir($d))) {
			if (is_file("$this->directory/$f")) {
				$this->filearray[] = $f;
			}
		}
	}
	
	
	
}