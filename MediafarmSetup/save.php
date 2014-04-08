<?php
	/**
	 *	1. socket 사용 - php.ini 파일 수정
	 * 		extension=php_sockets.dll
	 */

	include './config.php';
	
	//-----------------------------------------------------------------------------------------
	// xml Data
	//-----------------------------------------------------------------------------------------
	$publicIp = $_POST['publicIp']; 
	//echo "publicIp : " .$publicIp. "\n<br>";
	$staticIp = $_POST['staticIp']; 
	//echo "staticIp : " .$staticIp. "\n<br>";
	$subnetMask = $_POST['subnetMask']; 
	//echo "subnetMask : " .$subnetMask. "\n<br>";
	$macAddr = $_POST['macAddr']; 
	echo "macAddr : " .$macAddr. "\n<br>";
	$gateway = $_POST['gateway']; 
	//echo "gateway : " .$gateway. "\n<br>";
	$dns1 = $_POST['dns1']; 
	//echo "dns1 : " .$dns1. "\n<br>";
	$dns2 = $_POST['dns2']; 
	//echo "dns2 : " .$dns2. "\n<br>";
	
	$camera1Port = $_POST['camera1Port']; 
	//echo "camera1Port : " .$camera1Port. "\n<br>";
	$camera2Port = $_POST['camera2Port']; 
	//echo "camera2Port : " .$camera2Port. "\n<br>";
	
	$sshPort = $_POST['sshPort']; 
	//echo "sshPort : " .$sshPort. "\n<br>";
	
	$configYn = $_POST['configYn'];
	echo "configYn : " .$configYn. "\n<br>";
	
	$dirName = CONFIG_FILE_FATH.CONFIG_FILE_NAME;
	
	// 설정파일이 있을 경우 삭제한다.
	if($configYn === "Y") {
		$result = unlink($dirName);
		echo "file delete result : ".$result;
	}
	
	//-----------------------------------------------------------------------------------------
	// xml 생성
	//-----------------------------------------------------------------------------------------
	//header("Content-Type: text/plain");
	
	$DOMObject = new DomDocument('1.0', 'UTF-8');
	
	$rootElement = $DOMObject->createElement("config");
	$DOMObject->appendChild($rootElement);
	createAttribute($DOMObject, "id", "ID", $rootElement); // TinyFarmer ID (서버에서 준 Response Protocol로 기록) 
	
	$networkElement = $DOMObject->createElement("network");
	$rootElement->appendChild($networkElement);

	createAttribute($DOMObject, "public", $publicIp, $networkElement);
	createAttribute($DOMObject, "static", $staticIp, $networkElement);
	createAttribute($DOMObject, "subnet", $subnetMask, $networkElement);
	createAttribute($DOMObject, "gateway", $gateway, $networkElement);
	createAttribute($DOMObject, "mac", $macAddr, $networkElement);
	createAttribute($DOMObject, "dns1", $dns1, $networkElement);
	createAttribute($DOMObject, "dns2", $dns2, $networkElement);
	
	$cameraElement = $DOMObject->createElement("camera");
	$rootElement->appendChild($cameraElement);
	
	createAttribute($DOMObject, "port1", $camera1Port, $cameraElement);
	createAttribute($DOMObject, "port2", $camera2Port, $cameraElement);
	
	$sshElement = $DOMObject->createElement("ssh");
	$rootElement->appendChild($sshElement);
	
	createAttribute($DOMObject, "port", $sshPort, $sshElement);
	
	$DOMObject->preserveWhiteSpace = false;
	$DOMObject->formatOutput = true;

	$DOMObject->save($dirName);
	
	$xml = $DOMObject->saveXml();
	
	echo "<pre>" . htmlspecialchars($xml) . "</pre>";
	echo "<br>";
	
	/**
	 * Attribute 생성 함수
	 * @param unknown $dom Dom
	 * @param unknown $attrName 속성명
	 * @param unknown $attrValue 속성값
	 * @param unknown $parentElement 부모element
	 */
	function createAttribute($dom, $attrName, $attrValue, $parentElement) {
		$attribute = $dom->createAttribute($attrName);
		$parentElement->appendChild($attribute);
		$attributeValue = $dom->createTextNode($attrValue);
		$attribute->appendChild($attributeValue);
	}

	//-----------------------------------------------------------------------------------------
	// 서버전송
	//-----------------------------------------------------------------------------------------
	
	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	
	if ($socket === false) {
		echo "socket 생성 실패! : " . socket_strerror(socket_last_error()) . "\n";
		echo "<br>";
	} else {
		echo "socket 생성 성공.\n";
		echo "<br>";
	}
	
	$connection = socket_connect($socket, SERVER_IP, SERVER_PORT);
	if ($result === false) {
		echo "연결 실패.\n: ($connection) " . socket_strerror(socket_last_error($socket)) . "\n";
		echo "<br>";
	} 
	else {
		echo "연결 성공 : ".SERVER_IP."/".SERVER_PORT."\n";
		echo "<br>";
	}
	
	socket_write($socket, $xml, strlen($xml));
	echo "<br>";
	$input = socket_read($socket, 1024) or die("응답메시지 읽기 실패.\n"); 
	echo "<br>";
	
	echo "응답메시지 : ".$input."\n";
	echo "<br>";
	socket_close($socket);

	//-----------------------------------------------------------------------------------------
	// TinyFarmer.xml에 ID 기록
	//-----------------------------------------------------------------------------------------
	$xmlDom = new DOMDocument();
	$xmlDom->load($dirName);
	$xpath = new DOMXPath($xmlDom);
	$elements = $xpath->query('//config[@id="ID"]');
	//echo "elements length : ".$elements->length;
	if ($elements->length >= 1) {
		$element = $elements->item(0);
		$element->setAttribute('id', $input);
	}
	
	$xmlDom->save($dirName);
	
	$finishXml = $xmlDom->saveXml();
	
	echo "<pre>" . htmlspecialchars($finishXml) . "</pre>";

	echo "완료";
		
?>