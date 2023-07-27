<?php
	/****************************************************************************************************
     * WPAY 표준 결제인증(신용카드) 요청 페이지
     *****************************************************************************************************/
    require_once APPPATH . "third_party/wpay/wpay_lib.php";
    $wpay_lib = new WPAY_LIB;
    $mid = "INIwpayT03"; // 가맹점 ID
    $wpayUserKey = '2dQHJN89t/xI6YcIjLiL21zrLtKvAL9MUv8ckT2fE4k='; // 가입시 이니시스에서 발행한 wpayUserKey
    $wpayToken = 'uoCtWq4AWclaA1Ytk/EZN9mZaB05vHTXJc4CBwEwPVw=';
    $returnUrl = '[YOUR_HOST]/wpay/wpay_auth_card_reg_return'; // 신용카드 등록 결과전달 URL
    $ci = "oMUtEjPHhU0wPU1F9F4l4yQQhe9HBKHUh9H0M33l4arWsteF735DgEIEXJxaYXDaRfVxfNWNPftU337XFrqXLQ==";
	$oid = $mid . "_" . $wpay_lib->randomString(); // 가맹점 주문번호(가맹점에서 직접 설정) ?
?>
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
	function goNext(frm)
	{
        var url = "[YOUR_HOST]/wpay/wpay_auth_card_reg_request";

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
        <br /> <input class="input" style="width: 100%;color:gray;" name="mid" value="<?php echo $mid ?>"><br />

												<br /> <b>wpayUserKey</b> : 이니시스에서 발행한 wpayUserKey
        <br /> <input class="input" style="width: 100%;" name="wpayUserKey" value="<?php echo $wpayUserKey ?>"><br />

												<br /> <b>wpayToken</b> : 이니시스에서 발행한 wpayToken
        <br /> <input class="input" style="width: 100%;" name="wpayToken" value="<?php echo $wpayToken ?>"><br />

												<br /> <b>oid</b> : 가맹점 주문번호
        <br /> <input class="input" style="width: 100%;" name="oid" value="<?php echo $oid ?>"><br />

												<br /> <b>goodsName</b> : 상품명 (상품코드로 변경)
        <br /> <input class="input" style="width: 100%;" name="goodsName" value="<?php echo '(최대길이255)'?>"> <br />

                                                <br /> <b>goodsQuantity</b> : 상품갯수 (상품코드로 변경)
        <br /> <input class="input" style="width: 100%;" name="goodsQuantity" value="<?php echo ''?>"> <br />

												<br /> <b>goodsPrice</b> : 결제금액 (총 결제금액)
        <br /> <input class="input" style="width: 100%;" name="goodsPrice" value="<?php echo '(최대길이10)'?>"> <br />

												<br /> <b>buyerName</b> : 구매자명
        <br /> <input class="input" style="width: 100%;" name="buyerName" value="<?php echo '(최대길이100)'?>"><br />

												<br /> <b>buyerTel</b> : 구매자연락처
        <br /> <input class="input" style="width: 100%;" name="buyerTel" 	value="<?php echo '(숫자와 “-“만 허용)'?>"> <br />

												<br /> <b>buyerEmail</b> : 구매자이메일
        <br /> <input class="input" style="width: 100%;" name="buyerEmail"	value=""> <br />

												<br /> <b>cardQuota</b> : 할부개월수
        <br /> <input class="input" style="width: 100%;" name="cardQuota"	value="(최대길이2)"> <br />

												<br /> <b>returnUrl</b> : 결과전달 URL
        <br /> <input class="input" style="width: 100%;color:gray;" name="returnUrl" value="<?php echo $returnUrl ?>"><br />

                                                <br /> <b>payMethod</b> : 결제수단 코드 (01: 신용카드)
                                                <br /> <input class="input" style="width: 100%;" name="payMethod"value="기본 설정은 01 입니다"><br />

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
