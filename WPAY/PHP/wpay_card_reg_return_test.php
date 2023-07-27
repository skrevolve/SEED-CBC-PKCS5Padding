<?php
/****************************************************************************************************
 * WPAY 표준 회원가입 결과 테스트 페이지
 *****************************************************************************************************/

require_once APPPATH."third_party/wpay/wpay_lib.php";

// 가맹점에 제공된 암호화 키(고정값)
$g_HASHKEY 	= "F3149950A7B6289723F325833F588STD";
$g_SEEDKEY 	= "rClo7QA4gdgyITHAPWrfXw==";
$g_SEEDIV 	= "WPAYSTDWPAY00000";


// null 값을 처리하는 메소드
function f_get_parm_str($val) {
    if ( $val == null ) $val = "";
    if ( $val == ""   ) $val = "";
    return  $val;
}
?>
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
<?php
    //-------------------------------------------------------------
    // 1. 결과 파라미터 수신 (UTF-8 필수)
    //-------------------------------------------------------------
    $param_resultCode 	    = $_POST['resultCode'];		// 결과코드
    $param_resultMsg 		= $_POST['resultMsg'];		// 결과메세지 - (URL Encoding 대상필드)
    $param_mid 			    = $_POST['mid'];			// WPAY 트랜잭션 ID(이니시스에서 생성)
    $param_wtid 		    = $_POST['wtid'];			// WPAY 트랜잭션 ID(이니시스에서 생성)
    $param_wpayUserKey 	    = $_POST['wpayUserKey'];	// 이니시스에서 발행한 wpayUserKey  - (SEED 암호화 대상필드)
    $param_wpayToken 		= $_POST['wpayToken'];		// 이니시스에서 발행한 wpayToken  - (SEED 암호화 대상필드)
    $param_payMethod 		= $_POST['payMethod'];		// 이니시스에서 발행한 payMethod
    $param_bankCardCode	    = $_POST['bankCardCode'];	// 이니시스에서 발행한 bankCardCode
    $param_bankCardNo		= $_POST['bankCardNo'];		// 이니시스에서 발행한 bankCardNo
    $param_cardName		    = $_POST['cardName'];		// 이니시스에서 발행한 cardName - (URL Encoding 대상필드)
    $param_cardIsscoCode	= $_POST['cardIsscoCode'];	// 이니시스에서 발행한 cardIsscoCode
    $param_checkFlg		    = $_POST['checkFlg'];		// 이니시스에서 발행한 checkFlg
    $param_cardTypeFlg	    = $_POST['cardTypeFlg'];	// 이니시스에서 발행한 cardTypeFlg
    $param_cardPdNum		= $_POST['cardPdNum'];		// 이니시스에서 발행한 cardPdNum
    $param_cardBImgUrl	    = $_POST['cardBImgUrl'];	// 이니시스에서 발행한 cardBImgUrl - (URL Encoding 대상필드)
    $param_wpayTokenNcnm	= $_POST['wpayTokenNcnm'];	// 이니시스에서 발행한 wpayTokenNcnm - (URL Encoding 대상필드)
    $param_signature 		= $_POST['signature'];		// Hash Value

    $paramMap = [
        'resultCode' => $_POST['resultCode'],
        'resultMsg' => $_POST['resultMsg'],
        'mid' => $_POST['mid'],
        'wtid' => $_POST['wtid'],
        'wpayUserKey' => $_POST['wpayUserKey'],
        'wpayToken' => $_POST['wpayToken'],
        'payMethod' => $_POST['payMethod'],
        'bankCardCode' => $_POST['bankCardCode'],
        'bankCardNo' => $_POST['bankCardNo'],
        'cardName' => $_POST['cardName'],
        'cardIsscoCode' => $_POST['cardIsscoCode'],
        'checkFlg' => $_POST['checkFlg'],
        'cardTypeFlg' => $_POST['cardTypeFlg'],
        'cardPdNum' => $_POST['cardPdNum'],
        'cardBImgUrl' => $_POST['cardBImgUrl'],
        'wpayTokenNcnm' => $_POST['wpayTokenNcnm'],
        'signature' => $_POST['signature']
    ];

    //-------------------------------------------------------------
    // 3. 결과 처리
    //-------------------------------------------------------------
    $wpay_lib = new WPAY_LIB;

    $srcStr = '';
    $signature = '';

    // 결과코드 성공(0000)인 경우
    if($param_resultCode == "0000"){

        // URL Decoding 처리
        $param_resultMsg = urlencode($param_resultMsg);
        $param_cardName = urlencode($param_cardName);
        $param_cardBImgUrl = urlencode($param_cardBImgUrl);
        $param_wpayTokenNcnm = urlencode($param_wpayTokenNcnm);

        try {
            // Seed 복호화 처리 (empty 처리 안하면 null string 이 복호화 처리됩니다)
            $param_wpayUserKey 	= !empty($param_wpayUserKey) ? $wpay_lib->decrypt($g_SEEDIV, $g_SEEDKEY, $param_wpayUserKey) : '';
            $param_wpayToken 	= !empty($param_wpayToken) ? $wpay_lib->decrypt($g_SEEDIV, $g_SEEDKEY, $param_wpayToken) : '';
        } catch(Exception $e) {
                echo $e;
                log_message("DEBUG", $e);
        }
        /*
        * 가맹점 DB 처리 부분
        * ......
        * ........
        * ..........
        */
    
    } else {

        // if ($param_resultCode == "7028") { // 이미 등록된 카드

        // URL Decoding 처리
        $param_resultMsg = urldecode($param_resultMsg);
        echo "<br/>";
        echo "#### WPAY 표준 결제정보(신용카드) 등록 실패 ####";
        echo "<pre>";
        echo "<br/>resultCode : ".$param_resultCode;
        echo "<br/>resultMsg : ".$param_resultMsg;
        echo "<br/>".json_encode($paramMap);
        echo "</pre>";
    }
?>

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
                                        <br/><input  style="width:100%;" name="resultCode" value="<?php echo $param_resultCode?>" >

                                        <br/><b>resultMsg</b>
                                        <br/><input  style="width:100%;" name="resultMsg" value="<?php echo $param_resultMsg?>" >

                                        <br/><b>mid</b>
                                        <br/><input  style="width:100%;" name="mid" value="<?php echo $param_mid?>" >

                                        <br/><b>wtid</b>
                                        <br/><input  style="width:100%;" name="wtid" value="<?php echo $param_wtid?>" >

                                        <br/><b>wpayUserKey</b>
                                        <br/><input  style="width:100%;" name="wpayUserKey" value="<?php echo $param_wpayUserKey?>" >

                                        <br/><b>wpayToken</b>
                                        <br/><input  style="width:100%;" name="wpayToken" value="<?php echo $param_wpayToken?>" >

                                        <br/><b>payMethod</b>
                                        <br/><input  style="width:100%;" name="payMethod" value="<?php echo $param_payMethod?>" >

                                        <br/><b>bankCardCode</b>
                                        <br/><input  style="width:100%;" name="bankCardCode" value="<?php echo $param_bankCardCode?>" >

                                        <br/><b>bankCardNo</b>
                                        <br/><input  style="width:100%;" name="bankCardNo" value="<?php echo $param_bankCardNo?>" >

                                        <br/><b>cardName</b>
                                        <br/><input  style="width:100%;" name="cardName" value="<?php echo $param_cardName?>" >

                                        <br/><b>cardIsscoCode</b>
                                        <br/><input  style="width:100%;" name="cardIsscoCode" value="<?php echo $param_cardIsscoCode?>" >

                                        <br/><b>checkFlg</b>
                                        <br/><input  style="width:100%;" name="checkFlg" value="<?php echo $param_checkFlg?>" >

                                        <br/><b>cardTypeFlg</b>
                                        <br/><input  style="width:100%;" name="cardTypeFlg" value="<?php echo $param_cardTypeFlg?>>" >

                                        <br/><b>cardPdNum</b>
                                        <br/><input  style="width:100%;" name="cardPdNum" value="<?php echo $param_cardPdNum?>" >

                                        <br/><b>cardBImgUrl</b>
                                        <br/><input  style="width:100%;" name="cardBImgUrl" value="<?php echo $param_cardBImgUrl?>" >

                                        <br/><b>wpayTokenNcnm</b>
                                        <br/><input  style="width:100%;" name="wpayTokenNcnm" value="<?php echo $param_wpayTokenNcnm?>" >

                                        <br/><b>signature</b>
                                        <br/><input  style="width:100%;" name="signature" value="<?php echo $param_signature?>" >

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