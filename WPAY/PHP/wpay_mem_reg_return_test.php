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
?>
<!DOCTYPE html>
<html>
<head>
    <title>WPAY 표준 회원가입 결과</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css">
        body { background-color: #efefef;}
        body, tr, td {font-size:9pt; font-family:굴림,verdana; color:#433F37; line-height:19px;}
        table, img {border:none}
    </style>
</head>

<body bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0 >
<div style="background-color:#f3f3f3;width:100%;font-size:13px;color: #ffffff;background-color: #000000;text-align: center">
    WPAY 회원가입 결과
</div>
<?php
//-------------------------------------------------------------
// 1. 결과 파라미터 수신
//-------------------------------------------------------------

$param_resultCode 	= $_POST['resultCode'];	// 결과코드
$param_resultMsg 	= $_POST['resultMsg'];	// 결과메세지 - (URL Encoding 대상필드)
$param_mid 			= $_POST['mid'];		// 가맹점 ID
$param_wtid 		= $_POST['wtid'];		// WPAY 트랜잭션 ID(이니시스에서 생성)
$param_userId 		= $_POST['userId'];		// 가맹점 유저ID(Request userId 필드 데이터) - (SEED 암호화 대상필드)
$param_wpayUserKey 	= $_POST['wpayUserKey'];// 이니시스에서 발행한 wpayUserKey  - (SEED 암호화 대상필드)
$param_ci 			= $_POST['ci'];			// 가맹점 고객의 ci  - (SEED 암호화 대상필드)
$param_signature 	= $_POST['signature'];	// Hash Value

$paramMap = [
    'resultCode' => $_POST['resultCode'],
    'resultMsg' => $_POST['resultMsg'],
    'mid' => $_POST['mid'],
    'wtid' => $_POST['wtid'],
    'userId' => $_POST['userId'],
    'wpayUserKey' => $_POST['wpayUserKey'],
    'ci' => $_POST['ci'],
    'signature' => $_POST['signature']
];
//-------------------------------------------------------------
// 3. 결과 처리
//-------------------------------------------------------------
$wpay_lib = new WPAY_LIB;

$srcStr = "";
$signature = "";

// 결과코드 성공(0000)인 경우
if($param_resultCode == "0000"){

    // URL Decoding 처리
    $param_resultMsg = urldecode($param_resultMsg);

    try {
        // Seed 복호화 처리
        $param_userId 		= !empty($param_userId) ? $wpay_lib->decrypt($g_SEEDIV, $g_SEEDKEY, $param_userId) : '';
        $param_wpayUserKey 	= !empty($param_wpayUserKey) ? $wpay_lib->decrypt($g_SEEDIV, $g_SEEDKEY, $param_wpayUserKey) : '';
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
                        <span style="font-size:20px"><b>WPAY 표준 회원가입 결과 파라미터 정보</b></span><br/>
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

                                        <br/><b>userId</b>
                                        <br/><input  style="width:100%;" name="userId" value="<%=param_userId%>" >

                                        <br/><b>wpayUserKey</b>
                                        <br/><input  style="width:100%;" name="wpayUserKey" value="<%=param_wpayUserKey%>" >

                                        <br/><b>ci</b>
                                        <br/><input  style="width:100%;" name="ci" value="<%=param_ci%>" >

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