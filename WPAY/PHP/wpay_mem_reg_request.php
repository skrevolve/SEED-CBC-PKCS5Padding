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
// UTF-8 설정 필수 입니다
$param_mid 		    = f_get_parm_str($_POST['mid']);		// [필수] 가맹점 ID
$param_userId 	    = f_get_parm_str($_POST['userId']);		// [필수] 가맹점 고객 ID (SEED 암호화 대상필드)
$param_ci 		    = f_get_parm_str($_POST['ci']);			// [옵션] 고객의 CI (SEED 암호화 대상필드)
$param_userNm 	    = f_get_parm_str($_POST['userNm']);		// [옵션] 고객실명 - (URL Encoding 대상필드)
$param_hNum 		= f_get_parm_str($_POST['hNum']);		// [옵션] 고객 휴대폰번호 (SEED 암호화 대상필드)
$param_hCorp 		= f_get_parm_str($_POST['hCorp']);		// [옵션] 휴대폰 통신사
$param_birthDay 	= f_get_parm_str($_POST['birthDay']);	// [옵션] 고객 생년월일(yyyymmdd) (SEED 암호화 대상필드)
$param_socialNo2 	= f_get_parm_str($_POST['socialNo2']);	// [옵션] 주민번호 뒤 첫자리
$param_frnrYn 	    = f_get_parm_str($_POST['frnrYn']);		// [옵션] 외국인 여부
$param_returnUrl 	= f_get_parm_str($_POST['returnUrl']);	// [필수] 회원가입 결과전달 URL - (URL Encoding 대상필드)

// signature 파라미터
$signature	= '';

// 회원가입요청 URL
$requestURL = $requestDomain.'/stdwpay/std/u/v1/memreg'; // 테스트계

//-------------------------------------------------------
// 2. 암호화 대상 필드 Seed 암호화
//-------------------------------------------------------
$wpay_lib = new WPAY_LIB;

try {
    // Seed  암호화 (empty 처리 안하면 null string 이 암호화 처리됩니다)
    $param_userId 	 = !empty($param_userId) ? $wpay_lib->encrypt($g_SEEDIV, $g_SEEDKEY, $param_userId) : '';
    $param_hNum 	 = !empty($param_hNum) ? $wpay_lib->encrypt($g_SEEDIV, $g_SEEDKEY, $param_hNum) : '';
    $param_birthDay  = !empty($param_birthDay) ? $wpay_lib->encrypt($g_SEEDIV, $g_SEEDKEY, $param_birthDay) : '';
    
    // URL Encoding
    $param_userNm 	 = urlencode($param_userNm);
    $param_returnUrl = urlencode($param_returnUrl);
} catch (Exception $e) {
    echo $e;
    log_message("DEBUG", $e);
}

//-------------------------------------------------------
// 3. 위변조 방지체크를 위한 signature 생성
//   (순서주의:연동규약서 참고)
//-------------------------------------------------------
$srcStr = '';
$srcStr = 'mid='.$param_mid;
$srcStr .= '&userId='.$param_userId;
$srcStr .= '&ci='.$param_ci;
$srcStr .= '&userNm='.$param_userNm;
$srcStr .= '&hNum='.$param_hNum;
$srcStr .= '&hCorp='.$param_hCorp;
$srcStr .= '&birthDay='.$param_birthDay;
$srcStr .= '&socialNo2='.$param_socialNo2;
$srcStr .= '&frnrYn='.$param_frnrYn;
$srcStr .= '&returnUrl='.$param_returnUrl;
$srcStr .= '&hashKey='.$g_HASHKEY;

try {
    $signature = $wpay_lib->encryptSHA256($srcStr);
} catch (Exception $e) {
    echo $e;
    log_message("DEBUG", $e);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>WPAY 표준 회원가입 요청</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css">
        body { background-color: #efefef;}
        body, tr, td {font-size:9pt; font-family:굴림,verdana; color:#433F37; line-height:19px;}
        table, img {border:none}
    </style>
</head>


<body bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0 >
<form id="SendMemregForm_id" name="SendMemregForm" method="POST" >

    <!-- <br/><b>mid</b> : 가맹점 ID -->
    <input  type="hidden" name="mid" id="mid" value="<?php echo $param_mid?>"  />

    <!-- <br/><b>userId</b> : 가맹점 고객 ID -->
    <input  type="hidden" name="userId" id="userId" value="<?php echo $param_userId?>" />

    <!-- <br/><b>returnUrl</b> : 회원가입 결과전달 URL -->
    <input  type="hidden" name="returnUrl" id="returnUrl" value="<?php echo $param_returnUrl?>"  />


    <!-- <br/><b>ci</b> : 고객의 CI -->
    <input  type="hidden" name="ci" id="ci"  value="<?php echo $param_ci?>" >

    <!-- <br/><b>userNm</b> : 고객실명 -->
    <input  type="hidden" name="userNm" id="userNm" value="<?php echo $param_userNm?>" >

    <!-- <br/><b>hNum</b> : 고객 휴대폰번호 -->
    <input  type="hidden" name="hNum" id="hNum" value="<?php echo $param_hNum?>" >

    <!-- <br/><b>hCorp</b> : 휴대폰 통신사('SKT', 'KTF', 'LGT', 'SKR':SKT알뜰폰, 'LGR':LGT알뜰폰, 'KTR':KT알뜰폰) -->
    <input  type="hidden" name="hCorp" id="hCorp" value="<?php echo $param_hCorp?>" >

    <!-- <br/><b>birthDay</b> : 고객 생년월일(yyyymmdd) -->
    <input  type="hidden" name="birthDay" id="birthDay" value="<?php echo $param_birthDay?>" >

    <!-- <br/><b>socialNo2</b> : 주민번호 뒤 첫자리 -->
    <input  type="hidden" name="socialNo2" id="socialNo2" value="<?php echo $param_socialNo2?>" >

    <!-- <br/><b>frnrYn</b> : 외국인여부(Y:외국인,N:내국인) -->
    <input  type="hidden" name="frnrYn" id="frnrYn" value="<?php echo $param_frnrYn?>" >

    <!-- <br/><b>signature</b> : HashValue -->
    <input  type="hidden" name="signature" id="signature" value="<?php echo $signature?>" >

    <div id="lodingImg" style="position:absolute; left:45%; top:40%; dispaly:none;">
        <div class='loader'  style=""></div>
    </div>

</form>
</body>
</html>

<script language="javascript">
    goWpay();
    function goWpay() {
        var sendfrm = document.getElementById("SendMemregForm_id");
        sendfrm.action = "<?php echo $requestURL ?>";
        sendfrm.submit();
    }
</script>
