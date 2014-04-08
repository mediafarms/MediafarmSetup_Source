<?php

//---------------------------------------------------------------------------------------------
// Include
// - parser_php4.php : XML Parser Class
// - config.php : Configuration File  
//---------------------------------------------------------------------------------------------
include "parser_php4.php";
include 'config.php';

//---------------------------------------------------------------------------------------------
// Define Variable
// - id : Tiny Farmer ID
// - publicIp : Public IP 
// - staticIp : Static IP
// - subnetMask : Subnet Mask
// - gateWay : gateway
// - dns1 : DNS1
// - dns2 : DNS2
// - camera1Port : Web Cam Port 1
// - camera2Port : Web Cam Port 2
// - sshPort : Remote Terminal Port
// - macAddr : Mac Address
// - configYn : Save
//---------------------------------------------------------------------------------------------
$id;
$publicIp;
$staticIp;
$subnetMask = "255.255.255.0";
$gateway = "192.168.0.1";
$dns1;
$dns2;
$camera1Port;
$camera2Port;
$sshPort;
$macAddr;
$configYn = false;

//---------------------------------------------------------------------------------------------
// Read Mac Address
//---------------------------------------------------------------------------------------------
$mac_addr_filepath = CONFIG_FILE_FATH.MACADDR_FILE_NAME;

if(file_exists($mac_addr_filepath)) {
	//echo $mac_addr_filepath."있음.";

	$fp = fopen($mac_addr_filepath, "r");

	// 첫라인만 읽는다.
	if( !feof($fp) ) 
		$macAddr = fgets($fp);
	fclose($fp);
	
	//echo "<br>Mac Address :".$macAddr;

}
else {
	//echo $mac_addr_filepath."없음.";
}

//---------------------------------------------------------------------------------------------
// TinyFarmer.xml 파일 유무 검사 후 Parsing
//---------------------------------------------------------------------------------------------
$config_filepath = CONFIG_FILE_FATH.CONFIG_FILE_NAME;
	//echo "config_filepath :".$config_filepath."<br>";

if(file_exists($config_filepath)) {
	//echo $config_filepath."있음.<br>";
	$configYn = "Y";

	$xml_string = file_get_contents($config_filepath);
	$parser = new XMLParser($xml_string);
	$parser->Parse(); 

	$id = $parser->document->tagAttrs["id"];
	$publicIp = $parser->document->network[0]->tagAttrs["public"];
	$staticIp = $parser->document->network[0]->tagAttrs["static"];
	$subnetMask = $parser->document->network[0]->tagAttrs["subnet"];
	$gateway = $parser->document->network[0]->tagAttrs["gateway"];
	$dns1 = $parser->document->network[0]->tagAttrs["dns1"];
	$dns2 = $parser->document->network[0]->tagAttrs["dns2"];

	$camera1Port = $parser->document->camera[0]->tagAttrs["port1"];
	$camera2Port = $parser->document->camera[0]->tagAttrs["port2"];

	$sshPort = $parser->document->ssh[0]->tagAttrs["port"];
}
else {
	//echo $config_filepath."없음.";
}
//echo "root : ".$_SERVER["DOCUMENT_ROOT"];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Mediafarm Setup Main</title>
<link rel="stylesheet" type="text/css" href="./css/common.css">
<script type="text/javascript" src="./js/common.js"></script>
</head>
<body>

<form name="main" method="post" target="result" action="save.php">
		<input type="hidden" name="configYn" value="<?php echo $configYn; ?>">
		<div id='edit_tiny_farmer' style="padding: 10px">
			<h2>Tiny Farmer 설정</h2>
			<table style="padding: 10px">
				<tr>
					<td class="cTxt">공인IP(*)</td>
					<td><input type="text" name="publicIp" class="cInput" onChange="checkIp(this)" value="<?php echo $publicIp; ?>"></td>
				</tr>
				<tr>
					<td class="cTxt">내부IP(*)</td>
					<td><input type="text" name="staticIp" class="cInput" value="<?php echo $staticIp; ?>"></td>
				</tr>
				<tr>
					<td class="cTxt">Subnet(*)</td>
					<td><input type="text" name="subnetMask" class="cInput" value="<?php echo $subnetMask; ?>"></td>
				</tr>
				<tr>
					<td class="cTxt">Gateway(*)</td>
					<td><input type="text" name="gateway" class="cInput" value="<?php echo $gateway; ?>"></td>
				</tr>
				<tr>
					<td class="cTxt">Mac Address</td>
					<td><input type="text" name="macAddr" class="cInput" value="<?php echo $macAddr; ?>"></td>
				</tr>
				<tr>
					<td class="cTxt">DNS</td>
					<td><input type="text" name="dns1" class="cInput" value="<?php echo $dns1; ?>">
						<input type="text" name="dns2" class="cInput" value="<?php echo $dns2; ?>"></td>
				</tr>
			</table>
		</div>

		<div id='edit_camera' style="padding: 10px">
			<h2>Camera 포트포워딩</h2>
			<table style="padding: 10px">
				<tr>
					<td class="cTxt">Camera1 포트</td>
					<td><input type="text" name="camera1Port" class="cInput" value="<?php echo $camera1Port; ?>"></td>
				</tr>
				<tr>
					<td class="cTxt">Camera2 포트</td>
					<td><input type="text" name="camera2Port" class="cInput" value="<?php echo $camera2Port; ?>"></td>
				</tr>
			</table>
		</div>

		<div id='edit_ssh' style="padding: 10px">
			<h2>원격접속 포트포워딩</h2>
			<table style="padding: 10px">
				<tr>
					<td class="cTxt">포트(*)</td>
					<td><input type="text" name="sshPort" class="cInput" onChange="checkNumber(this)" value="<?php echo $sshPort; ?>"></td>
				</tr>
			</table>
		</div>

		<div id='edit_button' style="padding: 12px">
			<table style="width:450px">
				<tr>
					<td class="cTxt" style="text-align:right">
						<input type="button" value="취소" class="cButton" onclick="reset()"><input type="button" value="저장" class="cButton" onclick="save(this.form)">
					</td>
				</tr>
				<tr>
					<td>
						<iframe name="result" width="450" height="200"></iframe>
					<td>
				</td>
			</table>
		</div>
		
	</form>

</body>
</html>