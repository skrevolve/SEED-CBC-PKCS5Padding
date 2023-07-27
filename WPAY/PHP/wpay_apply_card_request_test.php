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

    // 입력 파라미터 // UTF-8 설정
    $param_mid			= f_get_parm_str($_POST['mid']);			// [필수] 가맹점 ID
    $param_wpayUserKey	= f_get_parm_str($_POST['wpayUserKey']);	// [필수] 이니시스에서 발행한 wpayUserKey - (SEED 암호화 대상필드)
    $param_ci			= f_get_parm_str($_POST['ci']);			    // [옵션] 고객의 ci
    $param_wtid			= f_get_parm_str($_POST['wtid']);			// [필수] 이니시스에서 발행한	WPAY 트랜잭션ID
    $param_tax			= f_get_parm_str($_POST['tax']);			// [옵션] 과세금액
    $param_taxFree		= f_get_parm_str($_POST['taxFree']);		// [옵션] 비과세금액

    // signature 파라미터
    $param_signature	= "";

    // 결제요청 URL
    $requestURL = $requestDomain."/stdwpay/std/rest/v1/payappl/card";	// 테스트계

    $wpay_lib = new WPAY_LIB;

    //-------------------------------------------------------
    // 2. 암호화 대상 필드 Seed 암호화
    //-------------------------------------------------------
//    try {
//        // Seed  암호화
//        $param_wpayUserKey = !empty($param_wpayUserKey) ? $wpay_lib->encrypt($g_SEEDIV, $g_SEEDKEY, $param_wpayUserKey) : '';
//    } catch(Exception $e) {
//        echo $e;
//        log_message("DEBUG", $e);
//    }

    //-------------------------------------------------------
    // 3. 위변조 방지체크를 위한 signature 생성
    //   (순서주의:연동규약서 참고)
    //-------------------------------------------------------

    $srcStr = "";
    $srcStr .= "mid=".$param_mid;
    $srcStr .= "&wpayUserKey=".$param_wpayUserKey;
    $srcStr .= "&ci=".$param_ci;
    $srcStr .= "&wtid=".$param_wtid;
    $srcStr .= "&hashKey=".$g_HASHKEY;

    try {
        $param_signature = $wpay_lib->encryptSHA256($srcStr);
    } catch(Exception $e) {
        echo $e;
        log_message("DEBUG", $e);
    }

    //-------------------------------------------------------
    // 4. 결제 승인요청
    //-------------------------------------------------------
    $resultCode = "";
    $resultMsg = "";
    $mid = "";
    $wtid = "";
    $wpayUserKey = "";
    $wpayToken = "";
    $payMethod = "";
    $bankCardCode = "";
    $cardIsscoCode = "";
    $bankCardNo = "";
    $oid = "";
    $goodsName = "";
    $buyerName = "";
    $buyerTel = "";
    $buyerEmail = "";
    $cardQuota = "";
    $cardInterest = "";
    $tid = "";
    $applDate = "";
    $applNum = "";
    $applPrice = "";
    $memberNum = "";
    $applCardNum = "";
    $cardCheckFlag = "";
    $partCancelCode = "";
    $couponFlag = "";
    $couponDiscount = "";

    try {
        $sendParam = "mid=".$param_mid;
        $sendParam .= "&wpayUserKey=".urlencode($param_wpayUserKey);
        $sendParam .= "&ci=".urlencode($param_ci);
        $sendParam .= "&wtid=".$param_wtid;
        $sendParam .= "&tax=".$param_tax;
        $sendParam .= "&taxFree=".$param_taxFree;
        $sendParam .= "&signature=".$param_signature;

        $options = array(
            'http' => array(
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => $sendParam,
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents($requestURL, false, $context);
        if ($result === FALSE) echo "result 에러";

        $parsed = json_decode($result, true);
        if ($parsed === NULL) echo "JSON 파싱 에러";

        $resultCode = $parsed['resultCode'];
        $resultMsg = urldecode($parsed['resultMsg']);

        if($resultCode == "0000") {
            $mid = $parsed['mid'];
            $wtid = $parsed['wtid'];
            $wpayUserKey = $wpay_lib->decrypt($g_SEEDIV, $g_SEEDKEY, (string) $parsed['wpayUserKey']);
            $wpayToken = $wpay_lib->decrypt($g_SEEDIV, $g_SEEDKEY, (string) $parsed['wpayToken']);
            $payMethod = (string) $parsed['payMethod'];
            $bankCardCode = (string) $parsed['bankCardCode'];
            $cardIsscoCode = (string) $parsed['cardIsscoCode'];
            $bankCardNo = (string) $parsed['bankCardNo'];
            $oid = (string) $parsed['oid'];
            $goodsName = urldecode((string) $parsed['goodsName']);
            $buyerName = urldecode((string) $parsed['buyerName']);
            $buyerTel = (string) $parsed['buyerTel'];
            $buyerEmail = (string) $parsed['buyerEmail'];
            $cardQuota = (string) $parsed['cardQuota'];
            $cardInterest = (string) $parsed['cardInterest'];
            $tid = (string) $parsed['tid']; // 이니시스 결제 트랜잭션 ID (TID)
            $applDate = (string) $parsed['applDate'];
            $applNum = (string) $parsed['applNum'];
            $applPrice = (string) $parsed['applPrice'];
            $memberNum = $parsed['memberNum'] == null ? "" : (string) $parsed['memberNum'];
            $applCardNum = (string) $parsed['applCardNum'];
            $cardCheckFlag = (string) $parsed['cardCheckFlag'];
            $partCancelCode = (string) $parsed['partCancelCode'];
            $couponFlag = (string) $parsed['couponFlag'];
            $couponDiscount = (string) $parsed['couponDiscount'];
            /*
            * 가맹점 DB 처리 부분
            * ......
            * ........
            * ..........
            */
        }
    } catch (Exception $e) {
        echo $e;
        log_message("DEBUG", $e);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>WPAY 표준  결제승인요청</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css">
        body { background-color: #efefef;}
        body, tr, td {font-size:9pt; font-family:굴림,verdana; color:#433F37; line-height:19px;}
        table, img {border:none}
    </style>
</head>

<body bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0 >
<div style="background-color:#f3f3f3;width:100%;font-size:13px;color: #ffffff;background-color: #000000;text-align: center">
    WPAY 표준 결제승인요청 결과
</div>
<table width="450" border="0" cellspacing="0" cellpadding="0" style="padding:10px;" align="center">
    <tr>
        <td bgcolor="6095BC" align="center" style="padding:10px">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="padding:20px">

                <tr>
                    <td >
                        <span style="font-size:20px"><b>승인요청 결과 파라미터 정보</b></span><br/>
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
                                        <br/><input style="width:100%;" name="resultCode" value="<?php echo $resultCode?>" >

                                        <br/><b>resultMsg</b>
                                        <br/><input style="width:100%;" name="resultMsg" value="<?php echo $resultMsg?>" >

                                        <br/><b>mid</b>
                                        <br/><input style="width:100%;"  name="mid" value="<?php echo $mid?>" >

                                        <br/><b>wtid</b>
                                        <br/><input style="width:100%;"  name="wtid" value="<?php echo $wtid?>" >

                                        <br/><b>wpayUserKey</b>
                                        <br/><input style="width:100%;"  name="wpayUserKey" value="<?php echo $wpayUserKey?>" >

                                        <br/><b>wpayToken</b>
                                        <br/><input style="width:100%;"  name="wpayToken" value="<?php echo $wpayToken?>" >

                                        <br/><b>payMethod</b>
                                        <br/><input style="width:100%;"  name="payMethod" value="<?php echo $payMethod?>" >

                                        <br/><b>bankCardCode</b>
                                        <br/><input style="width:100%;"  name="bankCardCode" value="<?php echo $bankCardCode?>" >

                                        <br/><b>cardIsscoCode</b>
                                        <br/><input style="width:100%;"  name="cardIsscoCode" value="<?php echo $cardIsscoCode?>" >

                                        <br/><b>bankCardNo</b>
                                        <br/><input style="width:100%;"  name="bankCardNo" value="<?php echo $bankCardNo?>" >

                                        <br/><b>oid</b>
                                        <br/><input style="width:100%;"  name="oid" value="<?php echo $oid?>" >

                                        <br/><b>goodsName</b>
                                        <br/><input style="width:100%;"  name="goodsName" value="<?php echo $goodsName?>" >

                                        <br/><b>buyerName</b>
                                        <br/><input style="width:100%;"  name="buyerName" value="<?php echo $buyerName?>" >

                                        <br/><b>buyerTel</b>
                                        <br/><input style="width:100%;"  name="buyerTel" value="<?php echo $buyerTel?>" >

                                        <br/><b>buyerEmail</b>
                                        <br/><input style="width:100%;"  name="buyerEmail" value="<?php echo $buyerEmail?>" >

                                        <br/><b>cardQuota</b>
                                        <br/><input style="width:100%;"  name="cardQuota" value="<?php echo $cardQuota?>" >

                                        <br/><b>cardInterest</b>
                                        <br/><input style="width:100%;"  name="cardInterest" value="<?php echo $cardInterest?>" >

                                        <br/><b>tid</b>
                                        <br/><input style="width:100%;"  name="tid" value="<?php echo $tid?>" >

                                        <br/><b>applDate</b>
                                        <br/><input style="width:100%;"  name="applDate" value="<?php echo $applDate?>" >

                                        <br/><b>applNum</b>
                                        <br/><input style="width:100%;"  name="applNum" value="<?php echo $applNum?>" >

                                        <br/><b>applPrice</b>
                                        <br/><input style="width:100%;"  name="applPrice" value="<?php echo $applPrice?>" >

                                        <br/><b>memberNum</b>
                                        <br/><input style="width:100%;"  name="memberNum" value="<?php echo $memberNum?>" >

                                        <br/><b>applCardNum</b>
                                        <br/><input style="width:100%;"  name="applCardNum" value="<?php echo $applCardNum?>" >

                                        <br/><b>cardCheckFlag</b>
                                        <br/><input style="width:100%;"  name="cardCheckFlag" value="<?php echo $cardCheckFlag?>" >

                                        <br/><b>partCancelCode</b>
                                        <br/><input style="width:100%;"  name="partCancelCode" value="<?php echo $partCancelCode?>" >

                                        <br/><b>couponFlag</b>
                                        <br/><input style="width:100%;"  name="couponFlag" value="<?php echo $couponFlag?>" >

                                        <br/><b>couponDiscount</b>
                                        <br/><input style="width:100%;"  name="couponDiscount" value="<?php echo $couponDiscount?>" >

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
