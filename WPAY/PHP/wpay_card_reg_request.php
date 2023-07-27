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
$param_ci           = f_get_parm_str($_POST['ci']);			// [옵션] 가맹점 고객의 ci
$param_returnUrl	= f_get_parm_str($_POST['returnUrl']);	// [필수] 결제처리 결과전달 URL - (URL Encoding 대상필드)

// signature 파라미터
$signature	= "";

// 카드등록요청 URL
$requestURL = $requestDomain . "/stdwpay/std/u/v1/payreg/card";	// 테스트계

//-------------------------------------------------------
// 2. 암호화 대상 필드 Seed 암호화
//-------------------------------------------------------
$wpay_lib = new WPAY_LIB;

try {
    // Seed  암호화 (empty 처리 안하면 null string 이 암호화 처리됩니다)

    // UserKey 는 프론트에서 암호화된걸 전달받아 쓰기때문에 따로 다시 암호화 할 필요 없습니다
    // $param_wpayUserKey = !empty($param_wpayUserKey) ? $wpay_lib->encrypt($g_SEEDIV, $g_SEEDKEY, $param_wpayUserKey) : '';

    $param_returnUrl = urlencode($param_returnUrl);// URL Encoding
} catch(Exception $e) {
    echo $e;
    log_message("DEBUG", $e);
}

//-------------------------------------------------------
// 3. 위변조 방지체크를 위한 signature 생성
//   (순서주의:연동규약서 참고)
//-------------------------------------------------------
$srcStr = '';
$srcStr .= 'mid='.$param_mid;
$srcStr .= '&wpayUserKey='.$param_wpayUserKey;
$srcStr .= '&ci='.$param_ci;
$srcStr .= '&returnUrl='.$param_returnUrl;
$srcStr .= '&hashKey='.$g_HASHKEY;

try {
    $signature = $wpay_lib->encryptSHA256($srcStr);
} catch(Exception $e) {
    echo $e;
    log_message("DEBUG", $e);
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>WPAY 표준 결제정보(신용카드) 등록</title>
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

    <!-- <br/><b>ci</b> -->
    <br/><input type="hidden"  name="ci" value="<?php echo $param_ci?>" >

    <!-- <br/><b>returnUrl</b> -->
    <br/><input type="hidden"   name="returnUrl" value="<?php echo $param_returnUrl?>" >

    <!-- <br/><b>signature</b> -->
    <br/><input type="hidden"  name="signature" value="<?php echo $signature?>" >


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
        sendfrm.action = "<?php echo $requestURL ?>";
        sendfrm.submit();
    }
</script>
