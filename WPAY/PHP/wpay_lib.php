<?php
// WPAY 인증 관련 라이브러리
// PHP는 음수 계산이 힘들어서 Notice 에러는 무시

// require_once APPPATH . 'third_party/KISA_SEED_CBC.php'; # PHP Notice:  Undefined offset 오류 무시하세요

class WPAY_LIB {

    // 주문번호 생성 (난수 생성 최대길이 15)
    function randomString() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 15; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    // 문자열 Base64 복호화 후 16진수 byte 변환
    function getBytesBase64($base64String) {
        $decodedData = base64_decode($base64String);
        $byteArray = [];
        for ($i = 0; $i < strlen($decodedData); $i++) {
            $byteArray[] = ord($decodedData[$i]);
            if ($byteArray[$i] > 127) {
                $byteArray[$i] -= 256;
            }
        }
        return $byteArray;
    }

    // 문자열 16진수 byte 변환
    function getBytes($str) {
        $byteArray = unpack('C*', $str);
        $byteValues = [];
        foreach ($byteArray as $byte) {
            $byteValues[] = $byte;
        }
        return $byteValues;
    }

    // SEED/CBC/PKCS5Padding 암호화
    function encrypt($bszIV, $bszUser_key, $str) {
        $planBytes = $this->getBytes($str);
        $keyBytes = $this->getBytesBase64($bszUser_key);
        $IVBytes = $this->getBytes($bszIV);
        $message_offset = 0;
        $message_length = count($planBytes);
        $bszChiperText = KISA_SEED_CBC::SEED_CBC_Encrypt($keyBytes, $IVBytes, $planBytes, $message_offset, $message_length);
        $string = '';
        foreach ($bszChiperText as $byte) {
            $string .= chr($byte);
        }
        $base64EncodedString = base64_encode($string);
        return $base64EncodedString;
    }

    // SEED/CBC/PKCS5Padding 복호화
    function decrypt($bszIV, $bszUser_key, $str) {
        $planBytes = $this->getBytesBase64($str);
        $keyBytes = $this->getBytesBase64($bszUser_key);
        $IVBytes = $this->getBytes($bszIV);
        $message_offset = 0;
        $message_length = count($planBytes);
        $bszPlainText = null;
        $bszPlainText = KISA_SEED_CBC::SEED_CBC_Decrypt($keyBytes, $IVBytes, $planBytes, $message_offset, $message_length);
        return implode(array_map('chr', $bszPlainText));
    }

    // SHA256 해시 암호화
    function encryptSHA256($hashParam) {
        $hash = hash('sha256', $hashParam);
        return $hash;
    }
}
?>
