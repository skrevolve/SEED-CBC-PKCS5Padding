<?php
header("Content-Type: text/html; charset=utf-8");

//WPAY MID INIAPI 연동 TEST 정보
//MID = INIwpayT03
//KEY = GiEj92QY1qhbGtug
//IV  = bomhp2LTzNYv9s==
//HASHDATA 생성시 사용되는 KEY 값 = GiEj92QY1qhbGtug
//Test Domain = deviniapi.inicis.com
//stgwpay.inicis.com 으로 결제 테스트한 건들은 deviniapi로 취소 요청 해야됨.

// 입력 파라미터 // UTF-8 설정
$param_mid			= $_POST["mid"];			// [필수] 가맹점 ID
$param_tid			= $_POST["tid"];			// [필수] 이니시스에서 발행한	WPAY 트랜잭션ID
$param_cancelMsg    = $_POST["cancelMsg"];	// [필수] 취소 요청 메시지


//step1. 요청을 위한 파라미터 설정
$key         = "GiEj92QY1qhbGtug";
$type        = "Refund";
$paymethod   = "01";
$timestamp   = "20230726175046";//date("YmdHis");
$clientIp    = "192.0.0.1";
$mid         = $param_mid;
$tid         = $param_tid;
$msg         = $param_cancelMsg;

$resultCode = "";
$resultMsg = "";
$wtid = "";

try {

    // INIAPIKey + type + paymethod + timestamp + clientIp + mid + tid
    $hashData = hash("sha512",(string)$key.(string)$type.(string)$paymethod.(string)$timestamp.(string)$clientIp.(string)$mid.(string)$tid); // hash 암호화

    //step2. key=value 로 post 요청
    $data = array(
        'timestamp' => $timestamp,
        'type' => $type,
        'paymethod' => $paymethod,
        'clientIp' => $clientIp,
        'mid' => $mid,
        'tid' => $tid,
        'msg' => $msg,
        'hashData'=> $hashData
    );

    $url = "https://iniapi.inicis.com/api/v1/refund";
    $url = "https://deviniapi.inicis.com/api/v1/refund";
    $url = "https://deviniapi.inicis.com/stg/api/v1/refund";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=utf-8'));
    curl_setopt($ch, CURLOPT_POST, 1);

    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;
    print_r($response);

    $parsed = json_decode($response, true);
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
