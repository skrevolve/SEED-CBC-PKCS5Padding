<?php
    require_once APPPATH . "third_party/wpay/wpay_lib.php";

    // https://manual.inicis.com/pay/cancel.html#popup_21
    // 중요) 해당 망 취소 API는 결제 세션간 실패건 취소를 위한 API로 일반 결제건에 대한 취소용도로 사용 시 정상
    // 적인 취소가 되지 않습니다. 결제건에 대한 취소(환불), 부분취소는 INIAPI를 사용하여 처리 가능합니다.
    // 사용중인 MID에 대한 INIAPI연동 암호화 키 확인이 필요한 경우 영업담당자에게 문의 부탁드립니다

    // stgwpay.inicis.com 으로 결제 테스트한 건들은 deviniapi로 취소 요청 해야됨 (문서내용 업데이트안되있음)

    //Request Domain
    $requestDomain = "https://deviniapi.inicis.com/stg/api/v1/refund"; // "https://stgwpaystd.inicis.com";

    // 가맹점 ID(가맹점 수정후 고정)
    $g_MID = "INIwpayT03";

    // 가맹점에 제공된 암호화 키(고정값)
    $g_HASHKEY 	= "F3149950A7B6289723F325833F588STD";
    $g_SEEDKEY 	= "rClo7QA4gdgyITHAPWrfXw==";
    $g_SEEDIV 	= "WPAYSTDWPAY00000";

    $KEY 	= 'GiEj92QY1qhbGtug';
    $IV 	= 'bomhp2LTzNYv9s==';

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
    $param_mid			= f_get_parm_str($_POST["mid"]);			// [필수] 가맹점 ID
    $param_wpayUserKey	= f_get_parm_str($_POST["wpayUserKey"]);	// [필수] 이니시스에서 발행한 wpayUserKey - (SEED 암호화 대상필드)
    $param_wtid			= f_get_parm_str($_POST["wtid"]);			// [필수] 이니시스에서 발행한	WPAY 트랜잭션ID
    $param_cancelMsg    = f_get_parm_str($_POST["cancelMsg"]);	// [필수] 취소 요청 메시지

    // signature 파라미터
    $param_signature	= "";

    // 결제요청 URL
    $requestURL = $requestDomain."/stdwpay/std/rest/v1/payreqapplcancel";	// 테스트계

    $wpay_lib = new WPAY_LIB;

    //-------------------------------------------------------
    // 2. 암호화 대상 필드 Seed 암호화
    //-------------------------------------------------------
    try {

        //  WPAY 암호화 키 정보로 복호화
        //$param_wpayUserKey = !empty($param_wpayUserKey) ? $wpay_lib->decrypt($g_SEEDIV, $g_SEEDKEY, $param_wpayUserKey) : '';

        // INI API 암호화 키 정보로 다시 암호화
        //$param_wpayUserKey = !empty($param_wpayUserKey) ? $wpay_lib->encrypt($g_INI_SEEDIV, $g_INI_SEEDKEY, $param_wpayUserKey) : '';

        $param_cancelMsg = urlencode($param_cancelMsg);
    } catch(Exception $e) {
        echo $e;
        log_message("DEBUG", $e);
    }

    //-------------------------------------------------------
    // 3. 위변조 방지체크를 위한 signature 생성
    //   (순서주의:연동규약서 참고)
    //-------------------------------------------------------

    $srcStr  = "mid=".$param_mid;
    $srcStr .= "&wpayUserKey=".$param_wpayUserKey;
    $srcStr .= "&wtid=".$param_wtid;
    $srcStr .= "&cancelMsg=".$param_cancelMsg;
    $srcStr .= "&hashKey=".$g_HASHKEY;

    try {
        $param_signature = $wpay_lib->encryptSHA256($srcStr);
    } catch(Exception $e) {
        echo $e;
        log_message("DEBUG", $e);
    }

    //-------------------------------------------------------
    // 4. 결제 취소요청
    //-------------------------------------------------------
    $resultCode = "";
    $resultMsg = "";
    $wtid = "";

    try {
        $sendParam = "mid=".$param_mid;
        $sendParam .= "&wpayUserKey=".$param_wpayUserKey;
        $sendParam .= "&wtid=".$param_wtid;
        $sendParam .= "&cancelMsg=".$param_cancelMsg;
        $sendParam .= "&signature=".$param_signature;

        $options = array(
            'http' => array(
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => $sendParam,
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($requestURL, false, $context);
        if ($result === FALSE) echo "result 에러";

        $parsed = json_decode($result, true);
        if ($parsed === NULL) echo "JSON 파싱 에러";

        $resultCode = (string) $parsed['resultCode'];
        $resultMsg = urldecode((string) $parsed['resultMsg']);

        if($resultCode == "0000") {
            $wtid = $parsed['wtid'];

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
    <title>WPAY 표준 결제취소 요청</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css">
        body { background-color: #efefef;}
        body, tr, td {font-size:9pt; font-family:굴림,verdana; color:#433F37; line-height:19px;}
        table, img {border:none}
    </style>
</head>

<body bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0 >
<div style="background-color:#f3f3f3;width:100%;font-size:13px;color: #ffffff;background-color: #000000;text-align: center">
    WPAY 표준 결제취소 요청 결과
</div>
<table width="450" border="0" cellspacing="0" cellpadding="0" style="padding:10px;" align="center">
    <tr>
        <td bgcolor="6095BC" align="center" style="padding:10px">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="padding:20px">

                <tr>
                    <td >
                        <span style="font-size:20px"><b>WPAY 표준 결제취소 결과 파라미터 정보</b></span><br/>
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

                                        <br/><b>wtid</b>
                                        <br/><input style="width:100%;"  name="wtid" value="<?php echo $wtid?>" >

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
