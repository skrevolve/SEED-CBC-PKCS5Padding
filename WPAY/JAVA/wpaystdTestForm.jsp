<%@page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>

<%@ include file="wpaystdConfig.jsp" %>
<%
	// 가맹점 도메인 입력
	// 페이지 URL에서 고정된 부분을 적는다. 
	// Ex) returnURL이 http://localhost:8080/StdWpaySample/stdWpayMemCiReturn.jsp 라면
	// http://localhost:8080/StdWpaySample 까지만 기입한다.
	String strCurrentDomain = request.getScheme() + "://" + request.getServerName() + ":" + request.getServerPort() + request.getContextPath(); 
%>

<!DOCTYPE html>
<html>
<head>
	<title>WPAY 표준 메뉴리스트</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<style type="text/css">
		body { background-color: #efefef;}
		body, tr, td {font-size:9pt; font-family:굴림,verdana; color:#433F37; line-height:19px;}
		table, img {border:none}
	</style>
</head>

<body bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0>
<form id="" name="" method="POST" >

	<div style="padding:10px;background-color:#f3f3f3;width:100%;font-size:13px;color: #ffffff;background-color: #000000;text-align: center">
		WPAY 표준
	</div>
	
	<table width="450" border="0" cellspacing="0" cellpadding="0" style="padding:10px;" align="center">
		<tr>
			<td bgcolor="6095BC" align="center" style="padding:10px">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="padding:20px">

					<tr>
						<td>
							이 페이지는 WPAY 표준 메뉴 리스트 입니다.<br/>
							<br/>

							<br/>
							테스트 하고자 하는 메뉴를 클릭 하여,<br/>
							테스트를 진행해 주시기 바랍니다.<br/>
							<br/><br/>
						</td>
					</tr>
					<tr>
						<td>
							<table >
								<tr>
									<td style="text-align:left;">

											<div style="border:2px #dddddd double;padding:10px;background-color:#f3f3f3;">

												<a  target="_blank" href="<%=strCurrentDomain%>/wpaystdMemRegForm.jsp">1. WPAY 표준 회원가입요청</a>
												<br/><br/>

<%--												<a  target="_blank" href="<%=strCurrentDomain%>/wpaystdMemUnregForm.jsp">2. WPAY 표준 회원탈퇴요청</a>--%>
<%--												<br/><br/>--%>

												<a  target="_blank" href="<%=strCurrentDomain%>/wpaystdCardRegForm.jsp" >3. WPAY 표준 신용카드등록</a>
												<br/><br/>

<%--												<a  target="_blank" href="<%=strCurrentDomain%>/wpaystdAcctRegForm.jsp" >4. WPAY 표준 오픈뱅킹 계좌 등록</a>--%>
<%--												<br/><br/>--%>

<%--												<a  target="_blank" href="<%=strCurrentDomain%>/wpaystdDelPayInfoForm.jsp" >5. WPAY 표준 결제정보삭제</a>--%>
<%--												<br/><br/>--%>

												<a  target="_blank" href="<%=strCurrentDomain%>/wpaystdPayAuthCardForm.jsp" >6. WPAY 표준 결제인증-신용카드</a>
												<br/><br/>

<%--												<a  target="_blank" href="<%=strCurrentDomain%>/wpaystdPayAuthAcctForm.jsp" >7. WPAY 표준 결제인증-오픈뱅킹 계좌</a>--%>
<%--												<br/><br/>--%>

												<a  target="_blank" href="<%=strCurrentDomain%>/wpaystdPayApplCardForm.jsp" >8. WPAY 표준 결제승인-신용카드</a>
												<br/><br/>

<%--												<a  target="_blank" href="<%=strCurrentDomain%>/wpaystdPayApplAcctForm.jsp" >9. WPAY 표준 결제승인-오픈뱅킹 계좌</a>--%>
<%--												<br/><br/>--%>

<%--												<a  target="_blank" href="<%=strCurrentDomain%>/wpaystdPayCancelForm.jsp" >10. WPAY 표준 망취소</a>--%>
<%--												<br/><br/>	--%>

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