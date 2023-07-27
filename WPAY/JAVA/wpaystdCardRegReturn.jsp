<%@page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@page import="java.util.*"%>
<%@page import="java.net.URLDecoder" %>
<%@page import="wpaystd.WpayStdSample" %>
<%@ include file="wpaystdConfig.jsp" %>

<!DOCTYPE html>
<html>
<head>
	<title>WPAY 표준 결제정보(신용카드) 등록 결과</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<style type="text/css">
		body { background-color: #efefef;}
		body, tr, td {font-size:9pt; font-family:굴림,verdana; color:#433F37; line-height:19px;}
		table, img {border:none}
	</style>
</head>

<body bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0 >
	<div style="background-color:#f3f3f3;width:100%;font-size:13px;color: #ffffff;background-color: #000000;text-align: center">
		WPAY 표준 결제정보(신용카드) 등록 결과
	</div>
<%
	//-------------------------------------------------------------
	// 1. 결과 파라미터 수신
	//-------------------------------------------------------------
	request.setCharacterEncoding("UTF-8");

	Map<String,String> paramMap = new Hashtable<String,String>();
	Enumeration elems = request.getParameterNames();

	String temp = "";

	while(elems.hasMoreElements())
	{
		temp = (String) elems.nextElement();
		paramMap.put(temp, request.getParameter(temp));

	}

	String param_resultCode 	= paramMap.get("resultCode");		// 결과코드
	String param_resultMsg 		= paramMap.get("resultMsg");		// 결과메세지 - (URL Encoding 대상필드)
	String param_mid 			= paramMap.get("mid");				// WPAY 트랜잭션 ID(이니시스에서 생성)
	String param_wtid 			= paramMap.get("wtid");				// WPAY 트랜잭션 ID(이니시스에서 생성)
	String param_wpayUserKey 	= paramMap.get("wpayUserKey");		// 이니시스에서 발행한 wpayUserKey  - (SEED 암호화 대상필드)
	String param_wpayToken 		= paramMap.get("wpayToken");		// 이니시스에서 발행한 wpayToken  - (SEED 암호화 대상필드)
	String param_payMethod 		= paramMap.get("payMethod");		// 이니시스에서 발행한 payMethod
	String param_bankCardCode	= paramMap.get("bankCardCode");		// 이니시스에서 발행한 bankCardCode
	String param_bankCardNo		= paramMap.get("bankCardNo");		// 이니시스에서 발행한 bankCardNo
	String param_cardName		= paramMap.get("cardName");			// 이니시스에서 발행한 cardName - (URL Encoding 대상필드)
	String param_cardIsscoCode	= paramMap.get("cardIsscoCode");	// 이니시스에서 발행한 cardIsscoCode
	String param_checkFlg		= paramMap.get("checkFlg");			// 이니시스에서 발행한 checkFlg
	String param_cardTypeFlg	= paramMap.get("cardTypeFlg");		// 이니시스에서 발행한 cardTypeFlg
	String param_cardPdNum		= paramMap.get("cardPdNum");		// 이니시스에서 발행한 cardPdNum
	String param_cardBImgUrl	= paramMap.get("cardBImgUrl");		// 이니시스에서 발행한 cardBImgUrl - (URL Encoding 대상필드)
	String param_wpayTokenNcnm	= paramMap.get("wpayTokenNcnm");	// 이니시스에서 발행한 wpayTokenNcnm - (URL Encoding 대상필드)
	String param_signature 		= paramMap.get("signature");		// Hash Value

	
	//-------------------------------------------------------------
	// 3. 결과 처리
	//-------------------------------------------------------------
	WpayStdSample wpaySample = new WpayStdSample();
	
	String srcStr = "";
	String signature = "";
	
	// 결과코드 성공(0000)인 경우
	if(param_resultCode.equals("0000")){
		// signature 생성(순서주의:연동규약서 참고)
		srcStr = "resultCode="+param_resultCode;
		srcStr = srcStr + "&resultMsg="+param_resultMsg;
		srcStr = srcStr + "&mid="+param_mid;
		srcStr = srcStr + "&wtid="+param_wtid;
		srcStr = srcStr + "&wpayUserKey="+param_wpayUserKey;
		srcStr = srcStr + "&wpayToken="+param_wpayToken;
		srcStr = srcStr + "&payMethod="+param_payMethod;
		srcStr = srcStr + "&bankCardCode="+param_bankCardCode;
		srcStr = srcStr + "&bankCardNo="+param_bankCardNo;
		srcStr = srcStr + "&cardName="+param_cardName;
		//srcStr = srcStr + "&cardIsscoCode="+param_cardIsscoCode;
		srcStr = srcStr + "&checkFlg="+param_checkFlg;
		srcStr = srcStr + "&cardTypeFlg="+param_cardTypeFlg;
		srcStr = srcStr + "&cardPdNum="+param_cardPdNum;
		srcStr = srcStr + "&cardBImgUrl="+param_cardBImgUrl;
		srcStr = srcStr + "&wpayTokenNcnm="+param_wpayTokenNcnm;
		srcStr = srcStr + "&hashKey="+g_HASHKEY;

		try {
	signature = wpaySample.encrypteSHA256(srcStr);
		} catch(Exception e) {
	System.out.println(e);
		}
		
		if(!param_signature.equals(signature)) {
	out.println("<br/>");
	out.println("#### WPAY 표준 결제정보(신용카드) 등록 결과 signature 확인 실패 ####");
	out.println("<pre>");
	out.println("<br/>["+param_signature+"]");
	out.println("<br/>["+signature+"]");
	out.println("<br/>"+paramMap.toString());
	out.println("<br/>"+srcStr.toString());
	out.println("</pre>");
	//out.close();
		}

		// URL Decoding 처리
		param_resultMsg = URLDecoder.decode(param_resultMsg, "UTF-8");
		param_cardName = URLDecoder.decode(param_cardName, "UTF-8");
		param_cardBImgUrl = URLDecoder.decode(param_cardBImgUrl, "UTF-8");
		param_wpayTokenNcnm = URLDecoder.decode(param_wpayTokenNcnm, "UTF-8");
		
		try {
	// Seed 복호화 처리
	param_wpayUserKey 	= wpaySample.seedDecrypt(param_wpayUserKey, g_SEEDKEY, g_SEEDIV);
	param_wpayToken 	= wpaySample.seedDecrypt(param_wpayToken, g_SEEDKEY, g_SEEDIV);
		} catch(Exception e) {
	System.out.println(e);
		}
		/*
		* 가맹점 DB 처리 부분
		* ......
		* ........
		* ..........
		*/
		
	} else {
		// URL Decoding 처리
		param_resultMsg = URLDecoder.decode(param_resultMsg, "UTF-8");

		out.println("<br/>");
		out.println("#### WPAY 표준 결제정보(신용카드) 등록 실패 ####");
		out.println("<pre>");
		out.println("<br/>resultCode : "+param_resultCode);	
		out.println("<br/>resultMsg : "+param_resultMsg);	
		out.println("<br/>"+paramMap.toString());
		out.println("</pre>");
		//out.close();
	}
%>

	<table width="450" border="0" cellspacing="0" cellpadding="0" style="padding:10px;" align="center">
		<tr>
			<td bgcolor="6095BC" align="center" style="padding:10px">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="padding:20px">

					<tr>
						<td >
							<span style="font-size:20px"><b>WPAY 표준 결제정보(신용카드) 등록 결과 파라미터 정보</b></span><br/>
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
												<br/><input  style="width:100%;" name="resultCode" value="<%=param_resultCode%>" >

												<br/><b>resultMsg</b>
												<br/><input  style="width:100%;" name="resultMsg" value="<%=param_resultMsg%>" >

												<br/><b>mid</b>
												<br/><input  style="width:100%;" name="mid" value="<%=param_mid%>" >
												
												<br/><b>wtid</b>
												<br/><input  style="width:100%;" name="wtid" value="<%=param_wtid%>" >

												<br/><b>wpayUserKey</b>
												<br/><input  style="width:100%;" name="wpayUserKey" value="<%=param_wpayUserKey%>" >

												<br/><b>wpayToken</b>
												<br/><input  style="width:100%;" name="wpayToken" value="<%=param_wpayToken%>" >

												<br/><b>payMethod</b>
												<br/><input  style="width:100%;" name="payMethod" value="<%=param_payMethod%>" >
												
												<br/><b>bankCardCode</b>
												<br/><input  style="width:100%;" name="bankCardCode" value="<%=param_bankCardCode%>" >
												
												<br/><b>bankCardNo</b>
												<br/><input  style="width:100%;" name="bankCardNo" value="<%=param_bankCardNo%>" >
												
												<br/><b>cardName</b>
												<br/><input  style="width:100%;" name="cardName" value="<%=param_cardName%>" >
												
												<br/><b>cardIsscoCode</b>
												<br/><input  style="width:100%;" name="cardIsscoCode" value="<%=param_cardIsscoCode%>" >
												
												<br/><b>checkFlg</b>
												<br/><input  style="width:100%;" name="checkFlg" value="<%=param_checkFlg%>" >
												
												<br/><b>cardTypeFlg</b>
												<br/><input  style="width:100%;" name="cardTypeFlg" value="<%=param_cardTypeFlg%>" >
												
												<br/><b>cardPdNum</b>
												<br/><input  style="width:100%;" name="cardPdNum" value="<%=param_cardPdNum%>" >
												
												<br/><b>cardBImgUrl</b>
												<br/><input  style="width:100%;" name="cardBImgUrl" value="<%=param_cardBImgUrl%>" >
												
												<br/><b>wpayTokenNcnm</b>
												<br/><input  style="width:100%;" name="wpayTokenNcnm" value="<%=param_wpayTokenNcnm%>" >

												<br/><b>signature</b>
												<br/><input  style="width:100%;" name="signature" value="<%=param_signature%>" >

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
</form>
</body>
</html>