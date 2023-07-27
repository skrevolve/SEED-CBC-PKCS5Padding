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
	String param_ci				= request.getParameter("ci");			// [옵션] 고객의 ci
	String param_wtid			= request.getParameter("wtid");			// [필수] 이니시스에서 발행한	WPAY 트랜잭션ID
	String param_tax			= request.getParameter("tax");			// [옵션] 과세금액
	String param_taxFree		= request.getParameter("taxFree");		// [옵션] 비과세금액
	
	// signature 파라미터
	String param_signature	= "";

	// 결제요청 URL
	String requestURL = requestDomain + "/stdwpay/std/rest/v1/payappl/acct";	// 테스트계
	
	
	WpayStdSample wpaySample = new WpayStdSample();
	try {
		//-------------------------------------------------------
		// 2. 암호화 대상 필드 Seed 암호화  
		//-------------------------------------------------------
		
		// Seed  암호화
		param_wpayUserKey 	= wpaySample.seedEncrypt(param_wpayUserKey, g_SEEDKEY, g_SEEDIV);
		
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
	srcStr = srcStr + "&wtid="+param_wtid;
	srcStr = srcStr + "&hashKey="+g_HASHKEY;
	
	try {
		param_signature = wpaySample.encrypteSHA256(srcStr);
	} catch(Exception e) {
		System.out.println(e);
	}
	
	
	
	//-------------------------------------------------------
	// 4. 결제 승인요청
	//-------------------------------------------------------
	String sendParam = "mid="+param_mid;
	sendParam += "&wpayUserKey="+URLEncoder.encode(param_wpayUserKey, "UTF-8");
	sendParam += "&ci="+URLEncoder.encode(param_ci, "UTF-8");
	sendParam += "&wtid="+param_wtid;
	sendParam += "&tax="+param_tax;
	sendParam += "&taxFree="+param_taxFree;
	sendParam += "&signature="+param_signature;
	
	//out.println("<br>sendParam : " + sendParam );
	
	String resultCode = "";
	String resultMsg = "";
	String mid = "";
	String wtid = "";
	String wpayUserKey = "";
	String wpayToken = "";
	String payMethod = "";
	String bankCardCode = "";
	String bankCardNo = "";
	String oid = "";
	String goodsName = "";
	String buyerName = "";
	String buyerTel = "";
	String buyerEmail = "";
	String tid = "";
	String applDate = "";
	String applPrice = "";
	String cshrApplNum = "";
	String cshrResultCode = "";
	String cshrResultMsg = "";
	String cshrApplPrice = "";
	String cshrSupplyPrice = "";
	String cshrTax = "";
	String cshrServicePrice = "";
	String cshrType = "";
		
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
	mid = object.get("mid").toString();
	wtid = object.get("wtid").toString();
	wpayUserKey = wpaySample.seedDecrypt(object.get("wpayUserKey").toString(), g_SEEDKEY, g_SEEDIV);
	wpayToken = wpaySample.seedDecrypt(object.get("wpayToken").toString(), g_SEEDKEY, g_SEEDIV);
	payMethod = object.get("payMethod").toString();
	bankCardCode = object.get("bankCardCode").toString();
	bankCardNo = object.get("bankCardNo").toString();
	oid = object.get("oid").toString();
	goodsName = URLDecoder.decode(object.get("goodsName").toString(), "UTF-8");
	buyerName = URLDecoder.decode(object.get("buyerName").toString(), "UTF-8");
	buyerTel = object.get("buyerTel").toString();
	buyerEmail = object.get("buyerEmail").toString();
	tid = object.get("tid").toString();
	applDate = object.get("applDate").toString();
	applPrice = object.get("applPrice").toString();
	cshrApplNum = object.get("cshrApplNum") == null ? "" : object.get("cshrApplNum").toString();
	cshrResultCode = object.get("cshrResultCode").toString();
	cshrResultMsg = object.get("cshrResultMsg").toString();
	cshrApplPrice = object.get("cshrApplPrice").toString();
	cshrTax = object.get("cshrTax").toString();
	cshrServicePrice = object.get("cshrServicePrice").toString();
	cshrType = object.get("cshrType").toString();
	
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
	<title>WPAY 표준 결제승인(오픈뱅킹 계좌) 요청</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<style type="text/css">
		body { background-color: #efefef;}
		body, tr, td {font-size:9pt; font-family:굴림,verdana; color:#433F37; line-height:19px;}
		table, img {border:none}
	</style>
</head>

<body bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0 >
	<div style="background-color:#f3f3f3;width:100%;font-size:13px;color: #ffffff;background-color: #000000;text-align: center">
		WPAY 표준 결제승인(오픈뱅킹 계좌) 결과
	</div>
<table width="450" border="0" cellspacing="0" cellpadding="0" style="padding:10px;" align="center">
		<tr>
			<td bgcolor="6095BC" align="center" style="padding:10px">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="padding:20px">

					<tr>
						<td >
							<span style="font-size:20px"><b>WPAY 표준 결제승인(오픈뱅킹 계좌) 결과 파라미터 정보</b></span><br/>
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
											
												<br/><b>mid</b>
												<br/><input style="width:100%;"  name="mid" value="<%=mid%>" >
												
												<br/><b>wtid</b>
												<br/><input style="width:100%;"  name="wtid" value="<%=wtid%>" >
												
												<br/><b>wpayUserKey</b>
												<br/><input style="width:100%;"  name="wpayUserKey" value="<%=wpayUserKey%>" >
												
												<br/><b>wpayToken</b>
												<br/><input style="width:100%;"  name="wpayToken" value="<%=wpayToken%>" >
												
												<br/><b>payMethod</b>
												<br/><input style="width:100%;"  name="payMethod" value="<%=payMethod%>" >
												
												<br/><b>bankCardCode</b>
												<br/><input style="width:100%;"  name="bankCardCode" value="<%=bankCardCode%>" >
												
												<br/><b>bankCardNo</b>
												<br/><input style="width:100%;"  name="bankCardNo" value="<%=bankCardNo%>" >
												
												<br/><b>oid</b>
												<br/><input style="width:100%;"  name="oid" value="<%=oid%>" >
												
												<br/><b>goodsName</b>
												<br/><input style="width:100%;"  name="goodsName" value="<%=goodsName%>" >
												
												<br/><b>buyerName</b>
												<br/><input style="width:100%;"  name="buyerName" value="<%=buyerName%>" >
												
												<br/><b>buyerTel</b>
												<br/><input style="width:100%;"  name="buyerTel" value="<%=buyerTel%>" >
												
												<br/><b>buyerEmail</b>
												<br/><input style="width:100%;"  name="buyerEmail" value="<%=buyerEmail%>" >
												
												<br/><b>tid</b>
												<br/><input style="width:100%;"  name="tid" value="<%=tid%>" >
												
												<br/><b>applDate</b>
												<br/><input style="width:100%;"  name="applDate" value="<%=applDate%>" >
												
												<br/><b>applPrice</b>
												<br/><input style="width:100%;"  name="applPrice" value="<%=applPrice%>" >
												
												<br/><b>cshrApplNum</b>
												<br/><input style="width:100%;"  name="cshrApplNum" value="<%=cshrApplNum%>" >
												
												<br/><b>cshrResultCode</b>
												<br/><input style="width:100%;"  name="cshrResultCode" value="<%=cshrResultCode%>" >
												
												<br/><b>cshrResultMsg</b>
												<br/><input style="width:100%;"  name="cshrResultMsg" value="<%=cshrResultMsg%>" >
												
												<br/><b>cshrApplPrice</b>
												<br/><input style="width:100%;"  name="cshrApplPrice" value="<%=cshrApplPrice%>" >
												
												<br/><b>cshrSupplyPrice</b>
												<br/><input style="width:100%;"  name="cshrSupplyPrice" value="<%=cshrSupplyPrice%>" >
												
												<br/><b>cshrTax</b>
												<br/><input style="width:100%;"  name="cshrTax" value="<%=cshrTax%>" >
												
												<br/><b>cshrServicePrice</b>
												<br/><input style="width:100%;"  name="cshrServicePrice" value="<%=cshrServicePrice%>" >
												
												<br/><b>cshrType</b>
												<br/><input style="width:100%;"  name="cshrType" value="<%=cshrType%>" >
												
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
