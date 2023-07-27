<?php
    /****************************************************************************************************
     * WPAY 표준 결제승인정보(신용카드) 요청 페이지
     *****************************************************************************************************/

    $mid = "INIwpayT03"; // 가맹점 ID
    $wpayUserKey = '2dQHJN89t/xI6YcIjLiL21zrLtKvAL9MUv8ckT2fE4k='; // 가입시 이니시스에서 발행한 wpayUserKey
    $wtid = "STWPY202307255444178";
?>
<!DOCTYPE html>
<html>
<head>
    <title>WPAY 표준 결제승인(신용카드) 요청 정보입력</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css">
        body {
            background-color: #efefef;
        }

        body, tr, td {
            font-size: 9pt;
            font-family: 굴림, verdana;
            color: #433F37;
            line-height: 19px;
        }

        table, img {
            border: none
        }
    </style>
</head>
<script language="javascript">
    function goNext(frm) {
        var url = "[YOUR_HOST]/wpay/wpay_apply_card_request";

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
        WPAY 표준 결제승인(신용카드) 요청 샘플(API)</div>

    <table width="650" border="0" cellspacing="0" cellpadding="0"
           style="padding: 10px;" align="center">
        <tr>
            <td bgcolor="6095BC" align="center" style="padding: 10px">
                <table width="100%" border="0" cellspacing="0" cellpadding="0"
                       bgcolor="#FFFFFF" style="padding: 20px">

                    <tr>
                        <td>이 페이지는 WPAY 표준 결제승인(신용카드) 요청을 위한 예시입니다.<br /> <br /> <br />
                            Form에 설정된 모든 필드의 name은 대소문자 구분하며,<br /> 이 Sample은 WPAY 표준 결제승인(신용카드) 요청을 위해서
                            설정된 Form으로 테스트 / 이해를 돕기 위해서 모두 type="text"로 설정되어 있습니다.<br />
                            운영에 적용시에는 API연동방식으로 사용하기 바랍니다.<br /> <br /> <br /> <br />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!-- 결제요청 -->
                            <button type="button" onclick="goNext(this.form);"
                                    style="padding: 10px">결제승인(신용카드) 요청</button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td style="text-align: left;"><br /> <b>***** 필 수 *****</b>
                                        <div style="border: 2px #dddddd double; padding: 10px; background-color: #f3f3f3;">
                                            <br /> <b>mid</b> : 가맹점 ID
                                            <br /> <input class="input" style="width: 100%; color: gray;" name="mid" value="<?php echo $mid ?>" readOnly><br />

                                            <br /> <b>wpayUserKey</b> : 이니시스에서 발행한 wpayUserKey
                                            <br /> <input class="input" style="width: 100%;" name="wpayUserKey" value="<?php echo $wpayUserKey?>"><br />

                                            <br /> <b>wtid</b> : 이니시스에서 결제인증시 발행한 WPAY 트랜잭션ID
                                            <br /> <input class="input" style="width: 100%;" name="wtid" value="<?php echo $wtid?>"><br />
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
