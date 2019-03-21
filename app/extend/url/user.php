<?php
class url{
	function sendGet($str){
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $str);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$info = curl_exec($ch);
    	curl_close($ch);
    	return $info;
	}

	function sendPost($url,$arr){
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SAFE_UPLOAD,true);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	@curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
    	$info = curl_exec($ch);
    	curl_close($ch);
    	return $info;
	}

	










	//getaccessToken
	// $strUrl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx7eacfa4c46bd3461&secret=7cb95ba9ecfc4833ce5f05c973a4d004";
	// echo $strUrl;
	// $data = sendGet($strUrl);
	// $arr= json_decode($data,true);
	// $accessToken = $arr['access_token'];
	// echo $accessToken;
	// exit;
	// 上传临时素材
	// $accessToken="19_CiwW085fy6LKnjcJ8KT-ebbNmc6bfIn-gfU-5S3d36r1CYwx3yF0ZVSTL2cAHvD2gabPjDntFyu4lAFT5CK_0IrQszwnRfCeTSC6lvQWxAuxbN68vbVBn1OD2FOIz1Pr3epPm_ygPDGUHd8UJHBgAAATRL";
	// $strImgUrl = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$accessToken}&type=image";
	//  $size = filesize("./yangming.jpg");
	//  $obj  = new CURLFile('./yangming.jpg');
	//  $arrImg=array(
	// 	'media'=>$obj,
	// 	'form-data' => [
 //        	'filename' =>'yangming.jpg',
 //        	'filelength'=>$size,
 //        	'content-type'=>'image/jpeg'
 //    	]
	//  );
	// $bol = sendPost($strImgUrl,$arrImg);
	// var_dump($bol);


	/*	
	//获取用户列表
	$url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$accessToken";
	$info = sendGet($url);
	$arrUserInfo = json_decode($info,true);
	print_r($arrUserInfo);
	*/
	/*
	$url = "https://api.weixin.qq.com/cgi-bin/tags/create?access_token=$accessToken";
	$postData = array('tag'=>array("name"=>"测试"));
	$str = json_encode($postData);
	$info = sendPost($url,$str);	
	print_r($info);
	*/
	//获取临时素材
	//$media_id="v5afdVMUDtW2hHvlgDh0zMc6tZKWQEVOUcNDWd4aE4tDDBVUoVX6uNA7Ij1qdy12";
	//$url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token={$accessToken}&media_id={$media_id}";
	//$info = file_get_contents($url);
	//file_put_contents("./aaaaa.jpg", $info);
	
// 	$str=array(
// 			'button'=>array(
// 					array(
// 							'name'=>"xxxx",
// 							'sub_button'=>array(
// 									array(
// 											'type'=>"click",
// 											'name'=>"ooo",
// 											'key'=>"aaaa",
// 										),
// 									array(
// 											'type'=>'click',
// 											'name'=>"pppp",
// 											'key'=>"bbbb",
// 										),
// 								),
// 						),
// 					array(
// 							'name'=>"uuuu",
// 							'sub_button'=>array(
// 									array(
// 											'type'=>'click',
// 											'name'=>"bbbb",
// 											'key'=>"ccccc",
// 										),

// 										array(
// 											'type'=>"scancode_push",
// 											"name"=>"baidu",
// 											"key"=>"hit",
// 											),
// 								),
// 						),
// 				),
// 		);
// $strjson = json_encode($str);
// $access_token="19_QoKzbrwKRoXiaH9ksHgSTDc5iAPPtCRwabAEH550s1Okqc0EU5tYjFvQHj9B1seqmpk3V_tEZp1CfF-eAtrgwcAnthaKMpxSF6GjR7tP0Terz26F2nEnLtBlRHuuHjAsuP3vXLXNbq_LKtLJMGQiAIATNX";	
// $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
// function https_request($url,$data = null){
// 	    $curl = curl_init();
// 	    curl_setopt($curl, CURLOPT_URL, $url);
// 	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
// 	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
// 	    if (!empty($data)){
// 	        curl_setopt($curl, CURLOPT_POST, 1);
// 	        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
// 	    }
// 	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
// 	    $output = curl_exec($curl);
// 	    curl_close($curl);
//     	return $output;
// }

// 	$info = https_request($url,$strjson);
// 	var_dump($info);
}
?>
