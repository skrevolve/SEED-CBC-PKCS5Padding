<?php
    require_once APPPATH . "third_party/wpay/wpay_lib.php";
    
    //Request Domain
    $requestDomain = "https://stgwpaystd.inicis.com"; //상용 Domain.
    
    // 가맹점 ID(가맹점 수정후 고정)
    $g_MID = "INIwpayT03";
    
    // 가맹점에 제공된 암호화 키(고정값)
    $g_HASHKEY 	= "F3149950A7B6289723F325833F588STD";
    $g_SEEDKEY 	= "rClo7QA4gdgyITHAPWrfXw==";
    $g_SEEDIV 	= "WPAYSTDWPAY00000";
    
    // null 값을 처리하는 메소드
    function f_get_parm_str($val) {
        if ($val == null) $val = "";
        else if ($val == "") $val = "";
        return  $val;
    }
    
    //-------------------------------------------------------
    // 1. 파라미터 설정
    //-------------------------------------------------------
    
    // 입력 파라미터
    // UTF-8 설정
    $param_mid			= f_get_parm_str($_POST['mid']);		// [필수] 가맹점 ID
    $param_wpayUserKey	= f_get_parm_str($_POST['wpayUserKey']);// [필수] 이니시스에서 발행한 wpayUserKey - (SEED 암호화 대상필드)
    $param_wpayToken	= f_get_parm_str($_POST['wpayToken']);	// [필수] 이니시스에서 발행한 wpaytoken - (SEED 암호화 대상필드)
    $param_ci			= f_get_parm_str($_POST['ci']);			// [옵션] 가맹점 고객의 ci
    $param_payMethod	= f_get_parm_str($_POST['payMethod']);	// [옵션] 결제수단 코드
    $param_bankCardCode	= f_get_parm_str($_POST['bankCardCode']);// [옵션] 카드사 코드
    $param_oid			= f_get_parm_str($_POST['oid']);			// [필수] 가맹점 주문번호
    $param_goodsName	= f_get_parm_str($_POST['goodsName']);	// [필수] 상품명 - (URL Encoding 대상필드)
    $param_goodsPrice	= f_get_parm_str($_POST['goodsPrice']);	// [필수] 결제금액
    $param_buyerName	= f_get_parm_str($_POST['buyerName']);	// [필수] 구매자명 - (URL Encoding 대상필드)
    $param_buyerTel		= f_get_parm_str($_POST['buyerTel']);		// [필수] 구매자연락처
    $param_buyerEmail	= f_get_parm_str($_POST['buyerEmail']);	// [필수] 구매자이메일
    $param_cardQuota	= f_get_parm_str($_POST['cardQuota']);	// [필수] 할부개월수
    $param_cardInterest	= f_get_parm_str($_POST['cardInterest']);	// [옵션] 무이자여부
    $param_couponCode	= f_get_parm_str($_POST['couponCode']);	// [옵션] 선할인 쿠폰코드
    $param_flagPin		= f_get_parm_str($_POST['flagPin']);		// [옵션] 핀인증 여부(Y/null:핀인증 필수, N:이니시스 판단)
    $param_flagCardPoint= f_get_parm_str($_POST['flagCardPoint']);// [옵션] 카드포인트 사용여부(Y : 사용, 그외 미사용)
    $param_returnUrl	= f_get_parm_str($_POST['returnUrl']);	// [필수] 결제처리 결과전달 URL - (URL Encoding 대상필드)

    // signature 파라미터
    $param_signature	= "";
    
    // 결제인증요청 URL
    $requestURL = $requestDomain . "/stdwpay/std/u/v1/payauth/card";	// 테스트계

    //-------------------------------------------------------
    // 2. 암호화 대상 필드 Seed 암호화
    //-------------------------------------------------------
    $wpay_lib = new WPAY_LIB;
    try {
        // Seed  암호화
        // $param_wpayUserKey = !empty($param_wpayUserKey) ? $wpay_lib->encrypt($g_SEEDIV, $g_SEEDKEY, $param_wpayUserKey) : '';
        // $param_wpayToken   = !empty($param_wpayToken) ? $wpay_lib->encrypt($g_SEEDIV, $g_SEEDKEY, $param_wpayToken) : '';

        // URL Encoding
        $param_goodsName = urlencode($param_goodsName);
        $param_buyerName = urlencode($param_buyerName);
        $param_returnUrl = urlencode($param_returnUrl);


    } catch(Exception $e) {
        echo $e;
        log_message("DEBUG", $e);
    }
    //-------------------------------------------------------
    // 3. 위변조 방지체크를 위한 signature 생성
    //   (순서주의:연동규약서 참고)
    //-------------------------------------------------------
    
    $srcStr = "";
    $srcStr = "mid=".$param_mid;
    $srcStr .= "&wpayUserKey=".$param_wpayUserKey;
    $srcStr .= "&wpayToken=".$param_wpayToken;
    $srcStr .= "&ci=".$param_ci;
    $srcStr .= "&payMethod=".$param_payMethod;
    $srcStr .= "&bankCardCode=".$param_bankCardCode;
    $srcStr .= "&oid=".$param_oid;
    $srcStr .= "&goodsName=".$param_goodsName;
    $srcStr .= "&goodsPrice=".$param_goodsPrice;
    $srcStr .= "&buyerName=".$param_buyerName;
    $srcStr .= "&buyerTel=".$param_buyerTel;
    $srcStr .= "&buyerEmail=".$param_buyerEmail;
    $srcStr .= "&cardQuota=".$param_cardQuota;
    $srcStr .= "&cardInterest=".$param_cardInterest;
    $srcStr .= "&couponCode=".$param_couponCode;
    $srcStr .= "&flagPin=".$param_flagPin;
    $srcStr .= "&returnUrl=".$param_returnUrl;
    $srcStr .= "&hashKey=".$g_HASHKEY;

    try {
        $param_signature = $wpay_lib->encryptSHA256($srcStr);
    } catch(Exception $e) {
        echo $e;
        log_message("DEBUG", $e);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>WPAY 표준 결제인증(신용카드) 요청</title>
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
    <br/><input type="hidden" name="mid" value="<?php echo $param_mid?>" >

    <!-- <br/><b>wpayUserKey</b> -->
    <br/><input type="hidden" name="wpayUserKey" value="<?php echo $param_wpayUserKey?>" >

    <!-- <br/><b>wpayToken</b> -->
    <br/><input type="hidden" name="wpayToken" value="<?php echo $param_wpayToken?>" >

    <!-- <br/><b>ci</b> -->
    <br/><input type="hidden"  name="ci" value="<?php echo $param_ci?>" >

    <!-- <br/><b>payMethod</b> -->
    <br/><input type="hidden"  name="payMethod" value="<?php echo $param_payMethod?>" >

    <!-- <br/><b>bankCardCode</b> -->
    <br/><input type="hidden"  name="bankCardCode" value="<?php echo $param_bankCardCode?>" >


    <!-- <br/><b>oid</b> -->
    <br/><input type="hidden"  name="oid" value="<?php echo $param_oid?>" >

    <!-- <br/><b>goodsName</b> -->
    <br/><input type="hidden"  name="goodsName" value="<?php echo $param_goodsName?>" >

    <!-- <br/><b>goodsPrice</b> -->
    <br/><input type="hidden"  name="goodsPrice" value="<?php echo $param_goodsPrice?>" >

    <!-- <br/><b>buyerName</b> -->
    <br/><input type="hidden"  name="buyerName" value="<?php echo $param_buyerName?>" >

    <!-- <br/><b>buyerTel</b> -->
    <br/><input type="hidden"   name="buyerTel" value="<?php echo $param_buyerTel?>" >

    <!-- <br/><b>buyerEmail</b> -->
    <br/><input type="hidden"   name="buyerEmail" value="<?php echo $param_buyerEmail?>" >

    <!-- <br/><b>cardQuota</b> -->
    <br/><input type="hidden"   name="cardQuota" value="<?php echo $param_cardQuota?>" >

    <!-- <br/><b>cardInterest</b> -->
    <br/><input type="hidden"   name="cardInterest" value="<?php echo $param_cardInterest?>" >

    <!-- <br/><b>couponCode</b> -->
    <br/><input type="hidden"   name="couponCode" value="<?php echo $param_couponCode?>" >

    <!-- <br/><b>flagPin</b> -->
    <br/><input type="hidden"   name="flagPin" value="<?php echo $param_flagPin?>" >

    <!-- <br/><b>flagCardPoint</b> -->
    <br/><input type="hidden"   name="flagCardPoint" value="<?php echo $param_flagCardPoint?>" >

    <!-- <br/><b>returnUrl</b> -->
    <br/><input type="hidden"   name="returnUrl" value="<?php echo $param_returnUrl?>" >

    <!-- <br/><b>signature</b> -->
    <br/><input type="hidden"  name="signature" value="<?php echo $param_signature?>" >


    <div id="lodingImg" style="position:absolute; left:45%; top:40%; dispaly:none;">
        <div class='loader'  style=""></div>
    </div>

</form>
</body>
</html>

<script language="javascript">
    goWpay();
    function goWpay() {
        var sendfrm = document.getElementById("SendPayForm_id");
        sendfrm.action = "<?php echo $requestURL?>";
        sendfrm.submit();
    }
</script>
