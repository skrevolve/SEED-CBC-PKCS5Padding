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
	String param_wpayToken		= request.getParameter("wpayToken");	// [필수] 이니시스에서 발행한 wpaytoken - (SEED 암호화 대상필드)
	String param_ci				= request.getParameter("ci");			// [옵션] 가맹점 고객의 ci
	String param_payMethod		= request.getParameter("payMethod");	// [옵션] 결제수단 코드
	String param_bankCardCode	= request.getParameter("bankCardCode");	// [옵션] 은행 코드
	String param_oid			= request.getParameter("oid");			// [필수] 가맹점 주문번호
	String param_goodsName		= request.getParameter("goodsName");	// [필수] 상품명 - (URL Encoding 대상필드)
	String param_goodsPrice		= request.getParameter("goodsPrice");	// [필수] 결제금액 
	String param_buyerName		= request.getParameter("buyerName");	// [필수] 구매자명 - (URL Encoding 대상필드)
	String param_buyerTel		= request.getParameter("buyerTel");		// [필수] 구매자연락처
	String param_buyerEmail		= request.getParameter("buyerEmail");	// [필수] 구매자이메일
	String param_cshRecpSave	= request.getParameter("cshRecpSave");	// [옵션] 현금영수증정보 저장 여부 (Y : 저장, N(or null) : 저장안함)
	String param_cshRecpCode	= request.getParameter("cshRecpCode");	// [옵션] 현금영수증 발행 요청 코드 (1 : 소득공제용, 2 : 지출증빙용)
	String param_cshRecpInfo	= request.getParameter("cshRecpInfo");	// [옵션] 현금영수증 발행 정보 
	String param_returnUrl		= request.getParameter("returnUrl");	// [필수] 결제처리 결과전달 URL - (URL Encoding 대상필드)
	
	
	// signature 파라미터
	String param_signature	= "";

	// 결제인증요청 URL
	String requestURL = requestDomain + "/stdwpay/std/u/v1/payauth/acct";	// 테스트계
	
	
	WpayStdSample wpaySample = new WpayStdSample();	
	try {
		//-------------------------------------------------------
		// 2. 암호화 대상 필드 Seed 암호화  
		//-------------------------------------------------------
		
		// Seed  암호화
		param_wpayUserKey 	= wpaySample.seedEncrypt(param_wpayUserKey, g_SEEDKEY, g_SEEDIV);
		param_wpayToken 	= wpaySample.seedEncrypt(param_wpayToken, g_SEEDKEY, g_SEEDIV);
		param_cshRecpInfo 	= wpaySample.seedEncrypt(param_cshRecpInfo, g_SEEDKEY, g_SEEDIV);
		
		// URL Encoding
		param_goodsName = URLEncoder.encode(param_goodsName, "UTF-8");
		param_buyerName = URLEncoder.encode(param_buyerName, "UTF-8");
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
	srcStr = srcStr + "&wpayToken="+param_wpayToken;
	srcStr = srcStr + "&ci="+param_ci;
	srcStr = srcStr + "&payMethod="+param_payMethod;
	srcStr = srcStr + "&bankCardCode="+param_bankCardCode;
	srcStr = srcStr + "&oid="+param_oid;
	srcStr = srcStr + "&goodsName="+param_goodsName;
	srcStr = srcStr + "&goodsPrice="+param_goodsPrice;
	srcStr = srcStr + "&buyerName="+param_buyerName;
	srcStr = srcStr + "&buyerTel="+param_buyerTel;
	srcStr = srcStr + "&buyerEmail="+param_buyerEmail;
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
	<title>WPAY 표준 결제인증(오픈뱅킹 계좌) 요청</title>
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
	
	<!-- <br/><b>wpayToken</b> -->
	<br/><input type="hidden" name="wpayToken" value="<%=param_wpayToken%>" >

	<!-- <br/><b>ci</b> -->
	<br/><input type="hidden"  name="ci" value="<%=param_ci%>" >
	
	<!-- <br/><b>payMethod</b> -->
	<br/><input type="hidden"  name="payMethod" value="<%=param_payMethod%>" >
	
	<!-- <br/><b>bankCardCode</b> -->
	<br/><input type="hidden"  name="bankCardCode" value="<%=param_bankCardCode%>" >
	
	<!-- <br/><b>oid</b> -->
	<br/><input type="hidden"  name="oid" value="<%=param_oid%>" >

	<!-- <br/><b>goodsName</b> -->
	<br/><input type="hidden"  name="goodsName" value="<%=param_goodsName%>" >

	<!-- <br/><b>goodsPrice</b> -->
	<br/><input type="hidden"  name="goodsPrice" value="<%=param_goodsPrice%>" >

	<!-- <br/><b>buyerName</b> -->
	<br/><input type="hidden"  name="buyerName" value="<%=param_buyerName%>" >

	<!-- <br/><b>buyerTel</b> -->
	<br/><input type="hidden"   name="buyerTel" value="<%=param_buyerTel%>" >

	<!-- <br/><b>buyerEmail</b> -->
	<br/><input type="hidden"   name="buyerEmail" value="<%=param_buyerEmail%>" >

	<!-- <br/><b>cshRecpSave</b> -->
	<br/><input type="hidden"   name="cshRecpSave" value="<%=param_cshRecpSave%>" >
	
	<!-- <br/><b>cshRecpCode</b> -->
	<br/><input type="hidden"   name="cshRecpCode" value="<%=param_cshRecpCode%>" >

	<!-- <br/><b>cshRecpInfo</b> -->
	<br/><input type="hidden"   name="cshRecpInfo" value="<%=param_cshRecpInfo%>" >

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
