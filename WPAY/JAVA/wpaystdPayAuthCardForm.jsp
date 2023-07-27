<%@page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@page import="java.util.Calendar" %>
<%@page import="java.text.SimpleDateFormat" %>

<%@ include file="wpaystdConfig.jsp"%>

<%
	/****************************************************************************************************
	* WPAY 표준 결제인증(신용카드) 요청 페이지
	*****************************************************************************************************/
%>

<%
	Calendar cal = Calendar.getInstance();
	
	SimpleDateFormat sdf = new SimpleDateFormat("yyyyMMddHHmmss");

	// 가맹점 도메인 입력
	// 페이지 URL에서 고정된 부분을 적는다. 
	// Ex) returnURL이 http://localhost:8080/WpayStdWeb/WpayPayReturn.jsp 라면
	// http://localhost:8080/WpayStdWeb 까지만 기입한다.
	String strCurrentDomain = request.getScheme() + "://" + request.getServerName() + ":" + request.getServerPort() + request.getContextPath();

	String oid = g_MID + "_" + sdf.format(cal.getTime()); // 가맹점 주문번호(가맹점에서 직접 설정)
%>

<!DOCTYPE html>
<html>
<head>
<title>WPAY 표준 결제인증(신용카드) 요청 정보입력</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style type="text/css">
body { background-color: #efefef;}
body, tr, td {font-size:9pt; font-family:굴림,verdana; color:#433F37; line-height:19px;}
table, img {border:none}
</style>
</head>
<script language="javascript">
<!--
	function goNext(frm)
	{
		var url = "wpaystdPayAuthCardRequest.jsp";
		
		MakeNewWindow(frm, url);
	}
	
	function MakeNewWindow(frm, url) {
		var IFWin;
		var OpenOption = 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=450,height=650,top=100,left=400,';
		IFWin = window.open('', 'IfWindow', OpenOption);

		frm.action = url;
		frm.target = "IfWindow";
		frm.method = "POST";
		frm.submit();

		IFWin.focus();
	}
	-->
</script>

<body bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15
	marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0>
	<form id="SendPayForm_id" name="SendPayForm_id" method="POST">

		<div
			style="padding: 10px; background-color: #f3f3f3; width: 100%; font-size: 13px; color: #ffffff; background-color: #000000; text-align: center">
			WPAY 표준 결제인증(신용카드) 요청 샘플</div>

		<table width="650" border="0" cellspacing="0" cellpadding="0"
			style="padding: 10px;" align="center">
			<tr>
				<td bgcolor="6095BC" align="center" style="padding: 10px">
					<table width="100%" border="0" cellspacing="0" cellpadding="0"
						bgcolor="#FFFFFF" style="padding: 20px">

						<tr>
							<td>이 페이지는 WPAY 표준 결제인증(신용카드) 요청을 위한 예시입니다.<br /> <br /> <br /> Form에
								설정된 모든 필드의 name은 대소문자 구분하며,<br /> 이 Sample은 WPAY 표준 결제인증(신용카드) 요청을 위해서 설정된 Form으로
								테스트 / 이해를 돕기 위해서 모두 type="text"로 설정되어 있습니다.<br /> 운영에 적용시에는 일부
								가맹점에서 필요에 의해 사용자가 변경하는 경우를 제외하고<br /> 모두 type="hidden"으로 변경하여
								사용하시기 바랍니다.<br /> <br />
							<br />
							</td>
						</tr>
						<tr>
							<td>
								<!-- 결제요청 -->
								<button type="button" onclick="goNext(this.form);return false;"
									style="padding: 10px">결제인증(신용카드) 요청</button>
							</td>
						</tr>
						<tr>
							<td>
								<table>
									<tr>
										<td style="text-align: left;"><br />
										<b>***** 필 수 *****</b>
											<div
												style="border: 2px #dddddd double; padding: 10px; background-color: #f3f3f3;">

												<br /> <b>mid</b> : 가맹점 ID 
												<br /> <input class="input" style="width: 100%;color:gray;" name="mid" value="<%=g_MID%>" readOnly><br />

												<br /> <b>wpayUserKey</b> : 이니시스에서 발행한 wpayUserKey 
												<br /> <input class="input" style="width: 100%;" name="wpayUserKey" value=""><br />
												
												<br /> <b>wpayToken</b> : 이니시스에서 발행한 wpayToken 
												<br /> <input class="input" style="width: 100%;" name="wpayToken" value=""><br />

												<br /> <b>oid</b> : 가맹점 주문번호 
												<br /> <input class="input" style="width: 100%;" name="oid" value="<%=oid%>"><br />

												<br /> <b>goodsName</b> : 상품명 
												<br /> <input class="input" style="width: 100%;" name="goodsName" value="유기농쌀 10Kg"> <br />
												
												<br /> <b>goodsPrice</b> : 결제금액 
												<br /> <input class="input" style="width: 100%;" name="goodsPrice" value="1000"> <br />
												
												<br /> <b>buyerName</b> : 구매자명
											 	<br /> <input class="input" style="width: 100%;" name="buyerName" value="홍길동"><br />

												<br /> <b>buyerTel</b> : 구매자연락처 
												<br /> <input class="input" style="width: 100%;" name="buyerTel" 	value="01023456789"> <br />

												<br /> <b>buyerEmail</b> : 구매자이메일 
												<br /> <input class="input" style="width: 100%;" name="buyerEmail"	value="wpay_test@inicis.com"> <br />
												
												<br /> <b>cardQuota</b> : 할부개월수 
												<br /> <input class="input" style="width: 100%;" name="cardQuota"	value="00"> <br />
												
												<br /> <b>returnUrl</b> : 결과전달 URL 
												<br /> <input class="input" style="width: 100%;color:gray;" name="returnUrl" value="<%=strCurrentDomain%>/wpaystdPayAuthCardReturn.jsp" readOnly><br />

											</div> 
											<br /><br /> 
											<b>***** 옵션 *****</b>
											<div style="border: 2px #dddddd double; padding: 10px; background-color: #f3f3f3;">
												<br /> <b>ci</b> : 고객의 ci 
												<br /> <input class="input" style="width: 100%;" name="ci"value=""><br />
												
												<br /> <b>payMethod</b> : 결제수단 코드
												<br /> <input class="input" style="width: 100%;" name="payMethod"value="01"><br />
												
												<br /> <b>bankCardCode</b> : 카드사 코드
												<br /> <input class="input" style="width: 100%;" name="bankCardCode"value=""><br />
												
												<br /> <b>cardInterest</b> : 무이자여부 (Y : 무이자, N : 무이자 아님)
												<br /> <input class="input" style="width: 100%;" name="cardInterest"value=""><br />
												
												<br /> <b>couponCode</b> : 선할인 쿠폰코드
												<br /> <input class="input" style="width: 100%;" name="couponCode"value=""><br />
												
												<br /> <b>flagPin</b> : 핀인증 여부 (Y/null:핀인증 필수, N:이니시스 판단) 
												<br /> <input class="input" style="width: 100%;" name="flagPin" value="Y"> <br />
												
												<br /> <b>flagCardPoint</b> : 카드포인트 사용여부 (Y : 사용, 그외 미사용) 
												<br /> <input class="input" style="width: 100%;" name="flagCardPoint" value="N"> <br />
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
