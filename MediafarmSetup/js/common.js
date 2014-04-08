/**
 * Check Number
 * @param obj Target Object
 */
function checkNumber(obj) {
	var num_regx = /^[0-9]*$/;
	if( !num_regx.test(obj.value) ) {
		alert('숫자만 입력하세요!');
		obj.value = "";
		obj.focus();
	}
}

/**
 * Check IP Address 
 * @param obj Target Object
 */
function checkIp(obj) {
	var ip_regx = '((\\d{1,3}\\.){3}\\d{1,3}))';
	if( !ip_regx.test(obj.value) ) {
		alert('숫자와 dot(.)만 입력하세요!');
		obj.value = "";
		obj.focus();
	}
}

/**
 * Check Null
 * @param str String
 * @returns {Boolean}
 */
function isNull(str) {
	var isResult = false;
	str_temp = str + "";
	str_temp = str_temp.replace(" ", "");
	if (str_temp != "undefined" && str_temp != "" && str_temp != "null") {
		isResult = true;
	}

	return isResult;
}

/**
 * Save Infomation 
 * @param form
 * @returns {Boolean}
 */
function save(form) {
	if(!isNull(form.publicIp.value)) {
		alert("공인IP를 입력하세요.");
		form.publicIp.focus();
		return false;
	}
	else if(!isNull(form.staticIp.value)) {
		alert("내부IP를 입력하세요.");
		form.staticIp.focus();
		return false;
	}
	else if(!isNull(form.subnetMask.value)) {
		alert("Subnet Mask를 입력하세요.");
		form.subnetMask.focus();
		return false;
	}
	else if(!isNull(form.gateway.value)) {
		alert("Gateway를 입력하세요.");
		form.gateway.focus();
		return false;
	}
	else if(!isNull(form.macAddr.value)) {
		alert("Mac Address를 입력하세요.");
		form.macAddr.focus();
		return false;
	}
	else if(!isNull(form.sshPort.value)) {
		alert("원격접속 포트포워딩 정보를 입력하세요.");
		sshPort.gateway.focus();
		return false;
	}
	
	form.submit();
}