<?php

//操作步骤：必须先拆分源图片，才能合并。

// $shell = '拆分';//或合并
// $ip1 = new ImagePartition('./res/3.jpg', './res/out.file_', './res/out.jpg');
// ! strcmp($shell, '拆分') ? $ip1->split() : $ip1->merge(); 

foreach (glob('res/*.jpg') as $img){
	echo$pre_name = substr($img, 0, strlen($img)-4);
	$ip = new ImagePartition(
			$img, 
			$pre_name.'out.file_', 
			$pre_name.'out.jpg'
	);
	$ip->split();
	$ip->merge();
}

class ImagePartition {
	
	private $imgSrc;	//图片源
	private $prefix;	//输出分卷的前缀
	private $mergeSrc;	//将分卷合并之后得到的新图片
	
	function __construct($imgSrc, $prefix, $mergeSrc){
		$this->imgSrc = $imgSrc;
		$this->prefix = $prefix;
		$this->mergeSrc = $mergeSrc;
	}
	
	function merge(){
		$outname = $this->mergeSrc;
		$handle = fopen($outname, 'ab+');
		foreach (glob($this->prefix.'*') as $name){
			$content = fread(fopen($name, 'rb'), 8192);
			fwrite($handle, $content);
		}
		fclose($handle);
	}
	
	function split(){
		$filename = $this->imgSrc;
		$handle = fopen($filename, 'rb+');
		$i = 0;
		while( ! feof($handle) ){
			$content = fread($handle, 8192);
			fwrite(fopen($this->prefix.$i, 'wb+'), $content);
			$i ++;
		}
		fclose($handle);
	}
}