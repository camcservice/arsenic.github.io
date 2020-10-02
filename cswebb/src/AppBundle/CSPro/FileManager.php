<?php
namespace AppBundle\CSPro;
class FileInfo 
{
	var $type;
	var	$name;
	var $directory;
	var $md5;
	var $size;
}
class FileManager
{
	public $rootFolder = null;
	public function getDirectoryListing($folderPath){
		if(!isset($this->rootFolder))
			return null;
		$dirList = array();

		$absfolderPath = $this->rootFolder . DIRECTORY_SEPARATOR . $folderPath;
		if(!@is_dir($absfolderPath)){
			return null;
		}
		$dirContents = array_diff(@scandir($absfolderPath), array('..', '.'));
		$n=0;
		foreach($dirContents as $file){
			$fileInfo = new FileInfo();
			$fileInfo->name = $file;
			$fileInfo->directory =  "/" . $folderPath;
			if (substr($fileInfo->directory, -1) != '/')
				$fileInfo->directory .= '/';
			if(@is_dir($absfolderPath . DIRECTORY_SEPARATOR . $file)){
				$fileInfo->type = 'directory';
				unset($fileInfo->md5);
				unset($fileInfo->size);
			}
			else{
				$fileInfo->type = 'file';
				$fileInfo->md5 = @md5_file($absfolderPath . DIRECTORY_SEPARATOR . $file);
				$fileInfo->size = @filesize($absfolderPath . DIRECTORY_SEPARATOR . $file);
			}
			$dirList[$n] = $fileInfo;
			$n++;
		}
		return $dirList;
	}
	
	public function putFile($filePath,$content){
		$folderPath = "";
		if(!isset($this->rootFolder) || empty($filePath))
			return null;
		
		$absfolderPath = $this->rootFolder;
		$pos = strrpos($filePath, '/');
		if($pos===false){
			$fileName = $filePath;
		}
		else {
			$folderPath = substr($filePath,0,$pos);
			$fileName = substr($filePath,$pos+1);
			$absfolderPath = $this->rootFolder . DIRECTORY_SEPARATOR . $folderPath;
			if(!is_dir($absfolderPath)){
				$bRet = @mkdir($absfolderPath, 0777 , true);
				if(!$bRet)
				 return null;
			}
		}
		// Write the contents back to the file
		$file = $absfolderPath . DIRECTORY_SEPARATOR . $fileName;
		//echo 'file : ' .$file;
		if( !(@file_put_contents($file, $content) === FALSE) ){
			$fileInfo = new FileInfo();
			$fileInfo->type = 'file';
			$fileInfo->name = $fileName;
			$fileInfo->md5 = @md5_file($file);
			$fileInfo->size = @filesize($file);
			$fileInfo->directory = $folderPath;
			return $fileInfo;
		}
		return null;
	}
	public function getFileInfo($filePath){
		$folderPath = "";
		if(!isset($this->rootFolder))
			return null;
		
		$absfolderPath = $this->rootFolder;
		$pos = strrpos($filePath, '/');
		if($pos===FALSE){
			$fileName = $filePath;
		}
		else {
			$folderPath = substr($filePath,0,$pos);
			$fileName = substr($filePath,$pos+1);
			$absfolderPath = $this->rootFolder . DIRECTORY_SEPARATOR . $folderPath;
		}
		// Write the contents back to the file
		$file = $absfolderPath . DIRECTORY_SEPARATOR . $fileName;
		if( @is_file($file) === TRUE){
			$fileInfo = new FileInfo();
			$fileInfo->type = 'file';
			$fileInfo->name = $fileName;
			$fileInfo->md5 = @md5_file($file);
			$fileInfo->size = @filesize($file);
			$fileInfo->directory = $folderPath;
			return $fileInfo;
		}
		else if(is_dir($file)){
			$fileInfo = new FileInfo();
			$fileInfo->type = 'directory';
			$fileInfo->name = $fileName;
			$fileInfo->directory = $folderPath;
			unset($fileInfo->md5);
			unset($fileInfo->size);
			return $fileInfo;
		}
		return null;
	}
}
?>