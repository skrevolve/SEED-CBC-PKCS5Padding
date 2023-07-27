<%@page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@page import="java.net.URLEncoder" %>
<%@page import="wpaystd.WpayStdSample" %>
<%@ include file="wpaystdConfig.jsp" %>

<%
	//-------------------------------------------------------
	// 1. 파라미터 설정
	//-------------------------------------------------------

	// 입력 파라미터
	request.setCharacterEncoding("UTF-8");  							// UTF-8 설정
	String param_mid			= request.getParameter("mid");			// [필수] 가맹점 ID
	String param_wpayUserKey	= request.getParameter("wpayUserKey");	// [필수] 이니시스에서 발행한 wpayUserKey - (SEED 암호화 대상필드)
	String param_ci				= request.getParameter("ci");			// [옵션] 가맹점 고객의 ci
	String param_returnUrl		= request.getParameter("returnUrl");	// [필수] 결제처리 결과전달 URL - (URL Encoding 대상필드)
	
	
	// signature 파라미터
	String param_signature	= "";

	// 카드등록요청 URL
	String requestURL = requestDomain + "/stdwpay/std/u/v1/payreg/card";	// 테스트계
	
	WpayStdSample wpaySample = new WpayStdSample();	
	try {
		//-------------------------------------------------------
		// 2. 암호화 대상 필드 Seed 암호화  
		//-------------------------------------------------------
		
		// Seed  암호화
		param_wpayUserKey 	= wpaySample.seedEncrypt(param_wpayUserKey, g_SEEDKEY, g_SEEDIV);
		
		// URL Encoding
		param_returnUrl = URLEncoder.encode(param_returnUrl, "UTF-8");

	
	} catch(Exception e) {
		System.out.println(e);
	}
	//-------------------------------------------------------
	// 3. 위변조 방지체크를 위한 signature 생성
	//   (순서주의:연동규약서 참고)
	//-------------------------------------------------------

	String srcStr = "";
	srcStr = "mid="+param_mid;
	srcStr = srcStr + "&wpayUserKey="+param_wpayUserKey;
	srcStr = srcStr + "&ci="+param_ci;
	srcStr = srcStr + "&returnUrl="+param_returnUrl;
	srcStr = srcStr + "&hashKey="+g_HASHKEY;
	
	try {
		param_signature = wpaySample.encrypteSHA256(srcStr);
	} catch(Exception e) {
		System.out.println(e);
	}
%>

<!DOCTYPE html>
<html>
<head>
	<title>WPAY 표준 결제정보(신용카드) 등록</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<style type="text/css">
		body { background-color: #efefef;}
		body, tr, td {font-size:9pt; font-family:굴림,verdana; color:#433F37; line-height:19px;}
		table, img {border:none}
	</style>
</head>


<body bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0 >
<form id="SendPayForm_id" name="SendPayForm" method="POST" >
	
	<!-- <br/><b>mid</b> -->
	<br/><input type="hidden" name="mid" value="<%=param_mid%>" >
		
	<!-- <br/><b>wpayUserKey</b> -->
	<br/><input type="hidden" name="wpayUserKey" value="<%=param_wpayUserKey%>" >
	
	<!-- <br/><b>ci</b> -->
	<br/><input type="hidden"  name="ci" value="<%=param_ci%>" >
	
	<!-- <br/><b>returnUrl</b> -->
	<br/><input type="hidden"   name="returnUrl" value="<%=param_returnUrl%>" >

	<!-- <br/><b>signature</b> -->
	<br/><input type="hidden"  name="signature" value="<%=param_signature%>" >
	
	
	<div id="lodingImg" style="position:absolute; left:45%; top:40%; dispaly:none;">
		<div class='loader'  style=""></div>
	</div>

</form>
</body>
</html>

<script language="javascript">
<!--
	goWpay();
	function goWpay() {
		var sendfrm = document.getElementById("SendPayForm_id");
		sendfrm.action = "<%=requestURL%>";
		sendfrm.submit();
	}
-->
</script>
