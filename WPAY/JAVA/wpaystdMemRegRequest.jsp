<%@page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@page import="java.net.URLEncoder" %>
<%@page import="wpaystd.WpayStdSample" %>
<%@ include file="wpaystdConfig.jsp" %>

<%
	//-------------------------------------------------------
	// 1. 파라미터 설정
	//-------------------------------------------------------
	
	// 입력 파라미터
	request.setCharacterEncoding("UTF-8");  						// UTF-8 설정
	String param_mid 		= request.getParameter("mid");			// [필수] 가맹점 ID
	String param_userId 	= request.getParameter("userId");		// [필수] 가맹점 고객 ID - (SEED 암호화 대상필드)
	String param_ci 		= request.getParameter("ci");			// [옵션] 고객의 CI - (SEED 암호화 대상필드)
	String param_userNm 	= request.getParameter("userNm");		// [옵션] 고객실명 - (URL Encoding 대상필드)
	String param_hNum 		= request.getParameter("hNum");			// [옵션] 고객 휴대폰번호 - (SEED 암호화 대상필드)
	String param_hCorp 		= request.getParameter("hCorp");		// [옵션] 휴대폰 통신사 
	String param_birthDay 	= request.getParameter("birthDay");		// [옵션] 고객 생년월일(yyyymmdd) - (SEED 암호화 대상필드)
	String param_socialNo2 	= request.getParameter("socialNo2");	// [옵션] 주민번호 뒤 첫자리
	String param_frnrYn 	= request.getParameter("frnrYn");		// [옵션] 외국인 여부
	String param_returnUrl 	= request.getParameter("returnUrl");	// [필수] 회원가입 결과전달 URL - (URL Encoding 대상필드)
	
	// signature 파라미터
	String param_signature	= "";

	// 회원가입요청 URL
	String requestURL = requestDomain + "/stdwpay/std/u/v1/memreg"; // 테스트계
	
	//-------------------------------------------------------
	// 2. 암호화 대상 필드 Seed 암호화  
	//-------------------------------------------------------
	WpayStdSample wpaySample = new WpayStdSample();
	
	try {
		
		// Seed  암호화
		param_userId 	= wpaySample.seedEncrypt(param_userId, g_SEEDKEY, g_SEEDIV);
		param_hNum 		= wpaySample.seedEncrypt(param_hNum, g_SEEDKEY, g_SEEDIV);
		param_birthDay 	= wpaySample.seedEncrypt(param_birthDay, g_SEEDKEY, g_SEEDIV);
		
		// URL Encoding
		param_userNm 	= URLEncoder.encode(param_userNm, "UTF-8");
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
	srcStr = srcStr + "&userId="+param_userId;
	srcStr = srcStr + "&ci="+param_ci;
	srcStr = srcStr + "&userNm="+param_userNm;
	srcStr = srcStr + "&hNum="+param_hNum;
	srcStr = srcStr + "&hCorp="+param_hCorp;
	srcStr = srcStr + "&birthDay="+param_birthDay;
	srcStr = srcStr + "&socialNo2="+param_socialNo2;
	srcStr = srcStr + "&frnrYn="+param_frnrYn;
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
	<title>WPAY 표준 회원가입 요청</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<style type="text/css">
		body { background-color: #efefef;}
		body, tr, td {font-size:9pt; font-family:굴림,verdana; color:#433F37; line-height:19px;}
		table, img {border:none}
	</style>
</head>


<body bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0 >
<form id="SendMemregForm_id" name="SendMemregForm" method="POST" >

	<!-- <br/><b>mid</b> : 가맹점 ID -->
	<input  type="hidden" name="mid" id="mid" value="<%=param_mid%>"  />	

	<!-- <br/><b>userId</b> : 가맹점 고객 ID -->
	<input  type="hidden" name="userId" id="userId" value="<%=param_userId%>" />

	<!-- <br/><b>returnUrl</b> : 회원가입 결과전달 URL -->
	<input  type="hidden" name="returnUrl" id="returnUrl" value="<%=param_returnUrl%>"  />


	<!-- <br/><b>ci</b> : 고객의 CI -->
	<input  type="hidden" name="ci" id="ci"  value="<%=param_ci%>" >

	<!-- <br/><b>userNm</b> : 고객실명 -->
	<input  type="hidden" name="userNm" id="userNm" value="<%=param_userNm%>" >

	<!-- <br/><b>hNum</b> : 고객 휴대폰번호 -->
	<input  type="hidden" name="hNum" id="hNum" value="<%=param_hNum%>" >

	<!-- <br/><b>hCorp</b> : 휴대폰 통신사('SKT', 'KTF', 'LGT', 'SKR':SKT알뜰폰, 'LGR':LGT알뜰폰, 'KTR':KT알뜰폰) -->
	<input  type="hidden" name="hCorp" id="hCorp" value="<%=param_hCorp%>" >

	<!-- <br/><b>birthDay</b> : 고객 생년월일(yyyymmdd) -->
	<input  type="hidden" name="birthDay" id="birthDay" value="<%=param_birthDay%>" >

	<!-- <br/><b>socialNo2</b> : 주민번호 뒤 첫자리 -->
	<input  type="hidden" name="socialNo2" id="socialNo2" value="<%=param_socialNo2%>" >

	<!-- <br/><b>frnrYn</b> : 외국인여부(Y:외국인,N:내국인) -->
	<input  type="hidden" name="frnrYn" id="frnrYn" value="<%=param_frnrYn%>" >

	<!-- <br/><b>signature</b> : HashValue -->
	<input  type="hidden" name="signature" id="signature" value="<%=param_signature%>" >
	
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
		var sendfrm = document.getElementById("SendMemregForm_id");
		sendfrm.action = "<%=requestURL%>"; 
		sendfrm.submit();
	}
-->	
</script>
