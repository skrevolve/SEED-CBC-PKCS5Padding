<%@page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@page import="java.io.*"%>
<%@page import="java.text.SimpleDateFormat"%>
<%@page import="java.util.*"%>
<%@page import="java.net.URL"%>
<%@page import="java.net.URLConnection"%>
<%@page import="org.json.simple.JSONArray"%>
<%@page import="org.json.simple.JSONObject"%>
<%@page import="org.json.simple.JSONValue"%>

<%@page import="java.net.URLEncoder" %>
<%@page import="java.net.URLDecoder" %>
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
	String param_wpayToken		= request.getParameter("wpayToken");	// [필수] 결제토큰 - (SEED 암호화 대상필드)
	String param_ci				= request.getParameter("ci");			// [옵션] 고객의 ci
	
	// signature 파라미터
	String param_signature	= "";

	// 결제정보 삭제 URL
	String requestURL = requestDomain + "/stdwpay/std/rest/v1/delpayinfo";	// 테스트계
	
	
	WpayStdSample wpaySample = new WpayStdSample();
	try {
		//-------------------------------------------------------
		// 2. 암호화 대상 필드 Seed 암호화  
		//-------------------------------------------------------
		
		// Seed  암호화
		param_wpayUserKey 	= wpaySample.seedEncrypt(param_wpayUserKey, g_SEEDKEY, g_SEEDIV);
		param_wpayToken 	= wpaySample.seedEncrypt(param_wpayToken, g_SEEDKEY, g_SEEDIV);
		
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
	srcStr = srcStr + "&hashKey="+g_HASHKEY;
	
	try {
		param_signature = wpaySample.encrypteSHA256(srcStr);
	} catch(Exception e) {
		System.out.println(e);
	}
	
	
	
	//-------------------------------------------------------
	// 4. 결제정보 삭제 요청
	//-------------------------------------------------------
	String sendParam = "mid="+param_mid;
	sendParam += "&wpayUserKey="+URLEncoder.encode(param_wpayUserKey, "UTF-8");
	sendParam += "&wpayToken="+URLEncoder.encode(param_wpayToken, "UTF-8");
	sendParam += "&ci="+URLEncoder.encode(param_ci, "UTF-8");
	sendParam += "&signature="+param_signature;
	
	//out.println("<br>sendParam : " + sendParam );
	
	String resultCode = "";
	String resultMsg = "";
	String wtid = "";
	String wpayUserKey = "";
	String wpayToken = "";
		
	try {
		
		URL sendUrl = new URL(requestURL);
		URLConnection uc = sendUrl.openConnection();
		uc.setDoOutput(true);	// POST
		//uc.setUseCaches(false);
		
		OutputStream raw = uc.getOutputStream();
		OutputStream buffered = new BufferedOutputStream(raw);
		OutputStreamWriter osw = new OutputStreamWriter(buffered, "UTF-8");
		osw.write(sendParam);
		osw.flush();
		osw.close();
		
		InputStreamReader isr = new InputStreamReader(uc.getInputStream(), "UTF-8");

		JSONObject object = (JSONObject)JSONValue.parse(isr);

		//out.println("<br>obj : ["+object.toJSONString()+"]");

		resultCode = object.get("resultCode").toString();
		resultMsg = object.get("resultMsg").toString();

		// URL Decoding 처리			
		resultMsg = URLDecoder.decode(resultMsg, "UTF-8");
		
		
		if( resultCode.equals("0000") ) {
	wtid = object.get("wtid").toString();
	wpayUserKey = object.get("wpayUserKey").toString();
	wpayToken = object.get("wpayToken").toString();
	
	try {
		// Seed 복호화 처리
		wpayUserKey 	= wpaySample.seedDecrypt(wpayUserKey, g_SEEDKEY, g_SEEDIV);
		wpayToken 		= wpaySample.seedDecrypt(wpayToken, g_SEEDKEY, g_SEEDIV);
		
	} catch(Exception e) {
		System.out.println(e);
	}
	
	
	/*
	* 가맹점 DB 처리 부분
	* ......
	* ........
	* ..........
	*/
		} 

		
	} catch (IOException ex) {
		out.println(ex);
	}
%>

<!DOCTYPE html>
<html>
<head>
	<title>WPAY 표준 결제정보 삭제</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<style type="text/css">
		body { background-color: #efefef;}
		body, tr, td {font-size:9pt; font-family:굴림,verdana; color:#433F37; line-height:19px;}
		table, img {border:none}
	</style>
</head>

<body bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0 >
	<div style="background-color:#f3f3f3;width:100%;font-size:13px;color: #ffffff;background-color: #000000;text-align: center">
		WPAY 표준 결제정보 삭제 결과
	</div>
<table width="450" border="0" cellspacing="0" cellpadding="0" style="padding:10px;" align="center">
		<tr>
			<td bgcolor="6095BC" align="center" style="padding:10px">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="padding:20px">

					<tr>
						<td >
							<span style="font-size:20px"><b>WPAY 표준 결제정보 삭제 결과 파라미터 정보</b></span><br/>
						</td>
					</tr>
					<tr >
						<td >
							<table>
								<tr>
									<td style="text-align:left;">
										
											<br/><b>************** 결과 파라미터 **************</b>
											<div style="border:2px #dddddd double;padding:10px;background-color:#f3f3f3;">
												
												<br/><b>resultCode</b>
												<br/><input style="width:100%;" name="resultCode" value="<%=resultCode%>" >
											
												<br/><b>resultMsg</b>
												<br/><input style="width:100%;" name="resultMsg" value="<%=resultMsg%>" >
											
												<br/><b>wtid</b>
												<br/><input style="width:100%;"  name="wtid" value="<%=wtid%>" >
												
												<br/><b>wpayUserKey</b>
												<br/><input style="width:100%;"  name="wpayUserKey" value="<%=wpayUserKey%>" >
												
												<br/><b>wpayToken</b>
												<br/><input style="width:100%;"  name="wpayToken" value="<%=wpayToken%>" >
												
											</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>
