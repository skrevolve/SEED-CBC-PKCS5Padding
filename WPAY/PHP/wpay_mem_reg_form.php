<?php
/****************************************************************************************************
 * WPAY 표준 회원가입 내부 테스트 페이지
 *****************************************************************************************************/

/**
 * 꼭 읽어 주세요
 * front/wpay/getWpayInfo 로 먼저 요청 실행하면 아래 데이터를 가져다줌.
 {
 "mid": "INIwpayT03", // 가맹점 ID
 "userId": "sukyu0919", // 가맹점 고객 ID (회원 아이디)
 "returnUrl": "https://data.bankx.co.kr/front/wpay/wpay_mem_reg_return" // 회원가입 결과전달 URL
 "wtid": "", // wpay 트랜잭션 ID
 "wpayUserKey": "", // 이니시스에서 발행한 wpayUserKey
 "ci": "" // 이니시스에서 발행한 고객 ci
 }
 * 요걸 받아서 쓰면됨니다
*/

$g_MID = "INIwpayT03"; // 가맹점 ID
$userId = 'sukyu0919'; // 가맹점 고객 ID (회원 아이디)
$returnUrl = "[YOUR_HOST]/wpay/wpay_mem_reg_return"; // 회원가입 결과전달 URL
?>

<!DOCTYPE html>
<html>
<head>
    <title>WPAY 표준 회원가입 정보입력</title>
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
        var url = "[YOUR_HOST]/wpay/wpay_mem_reg_request"; // 회원가입 요청 URL

        MakeNewWindow(frm, url);
    }

    function MakeNewWindow(frm, url)
    {
        var IFWin;
        var OpenOption = 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=450,height=650,top=100,left=400,';
        IFWin=window.open('', 'IfWindow' ,OpenOption);

        frm.action = url;
        frm.target = "IfWindow";
        frm.method = "POST";
        frm.submit();

        IFWin.focus();
    }
</script>

<body bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0>
<form id="SendMemregForm_id" name="SendMemregForm" method="POST" >

    <div style="padding:10px;background-color:#f3f3f3;width:100%;font-size:13px;color: #ffffff;background-color: #000000;text-align: center">
        WPAY 표준 회원가입 샘플
    </div>

    <table width="650" border="0" cellspacing="0" cellpadding="0" style="padding:10px;" align="center">
        <tr>
            <td bgcolor="6095BC" align="center" style="padding:10px">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="padding:20px">
                    <tr>
                        <td>
                            이 페이지는 WPAY 표준 회원가입을 위한 예시입니다.<br /><br /><br /> Form에
                            설정된 모든 필드의 name은 대소문자 구분하며,<br />이 Sample은 WPAY 표준 회원가입을 위해서 설정된 Form으로
                            테스트 / 이해를 돕기 위해서 모두 type="text"로 설정되어 있습니다.<br />
                            운영에 적용시에는 일부 가맹점에서 필요에 의해 사용자가 변경하는 경우를 제외하고<br />
                            모두 type="hidden"으로 변경하여
                            사용하시기 바랍니다.<br /><br />
                            <br />
                        </td>
                    </tr>
                    <tr>
                        <td >
                            <!-- 회원가입요청 -->
                            <button type="button" onclick="goNext(this.form);return false;" style="padding:10px">회원가입</button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table >
                                <tr>
                                    <td style="text-align:left;">

                                        <br /><b>***** 필 수 *****</b>
                                        <div style="border:2px #dddddd double;padding:10px;background-color:#f3f3f3;">
                                            <br /><b>mid</b> : 가맹점 ID
                                            <br /><input  class="input" style="width:100%;color:gray;" name="mid" id="mid" value="<?php echo $g_MID?>"  readOnly>

                                            <br /><b>userId</b> : 가맹점 고객 ID
                                            <br /><input  class="input" style="width:100%;" name="userId" id="userId" value="<?php echo $userId?>" >

                                            <br /><b>returnUrl</b> : 회원가입 결과전달 URL
                                            <br /><input  class="input" style="width:100%;color:gray;" name="returnUrl" id="returnUrl" value="<?php echo $returnUrl?>"  readOnly>
                                        </div>
                                        <br /><br />

                                        <b>***** 옵션 *****</b>
                                        <div style="border:2px #dddddd double;padding:10px;background-color:#f3f3f3;">

                                            <br /><b>ci</b> : 고객의 CI
                                            <br /><input  class="input" style="width:100%;" name="ci" id="ci"  value="" >

                                            <br /><b>userNm</b> : 고객실명 (CI 입력시 필수값)
                                            <br /><input  class="input" style="width:100%;" name="userNm" id="userNm" value="" >

                                            <br /><b>hNum</b> : 고객 휴대폰번호 (CI 입력시 필수값)
                                            <br /><input  class="input" style="width:100%;" name="hNum" id="hNum" value="" >

                                            <br /><b>hCorp</b> : 휴대폰 통신사 (CI 입력시 필수값)
                                            <br />('SKT', 'KTF', 'LGT', 'SKR':SKT알뜰폰, 'LGR':LGT알뜰폰, 'KTR':KT알뜰폰)
                                            <br />
                                            <select  name="hCorp" id="hCorp" style="width:100%;" onchange="">
                                                <option value="">통신사</option>
                                                <option value="SKT">SKT</option>
                                                <option value="KTF">KT</option>
                                                <option value="LGT">LGT</option>
                                                <option value="SKR">SKT 알뜰폰</option>
                                                <option value="KTR">KT 알뜰폰</option>
                                                <option value="LGR">LGT 알뜰폰</option>
                                            </select>

                                            <br /><b>birthDay</b> : 고객 생년월일(yyyymmdd) (CI 입력시 필수값)
                                            <br /><input  class="input" style="width:100%;" name="birthDay" id="birthDay" value="" >

                                            <br /><b>socialNo2</b> : 주민번호 뒤 첫자리 (CI 입력시 필수값)
                                            <br /><input  class="input" style="width:100%;" name="socialNo2" id="socialNo2">

                                            <br /><b>frnrYn</b> : 외국인여부(Y:외국인,N:내국인) (CI 입력시 필수값)
                                            <br /><input  class="input" style="width:100%;" name="frnrYn" id="frnrYn" value="" >
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
