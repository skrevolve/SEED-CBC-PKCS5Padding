import javax.xml.bind.DatatypeConverter;
import java.security.MessageDigest;
import java.util.Arrays;
import java.util.Base64;

public class WpayStdSample {

	static public void main(String[] args) {
		//String test = seedEncrypt("TESTuserId", "rClo7QA4gdgyITHAPWrfXw==", "WPAYSTDWPAY00000");
		String test = seedDecrypt("rw8yiQE00dA5Bzi4J+GVDg==", "rClo7QA4gdgyITHAPWrfXw==", "WPAYSTDWPAY00000");
		System.out.println(test);
	}

	static public byte[] getBytes(String data) {
		String[] str = data.split(",");
		byte[] result = new byte[str.length];
		for (int i = 0; i < result.length; i++) {
			result[i] = getHex(str[i]);
		}
		return result;
	}

	static public String getString(byte[] data) {
		String result = "";
		for (int i = 0; i < data.length; i++) {
			result = result + toHex(data[i]);
			if (i < data.length - 1)
				result = result + ",";
		}
		return result;
	}

	static public byte getHex(String str) {
		str = str.trim();
		if (str.length() == 0)
			str = "00";
		else if (str.length() == 1)
			str = "0" + str;

		str = str.toUpperCase();
		return (byte) (getHexNibble(str.charAt(0)) * 16 + getHexNibble(str.charAt(1)));
	}

	static public byte getHexNibble(char c) {
		if (c >= '0' && c <= '9')
			return (byte) (c - '0');
		if (c >= 'A' && c <= 'F')
			return (byte) (c - 'A' + 10);
		return 0;
	}

	static public String toHex(int b) {
		char c[] = new char[2];
		c[0] = toHexNibble((b >> 4) & 0x0f);
		c[1] = toHexNibble(b & 0x0f);
		return new String(c);
	}

	static public char toHexNibble(int b) {
		if (b >= 0 && b <= 9)
			return (char) (b + '0');
		if (b >= 0x0a && b <= 0x0f)
			return (char) (b + 'A' - 10);
		return '0';
	}

	static public String BinToHex(byte[] buf) {
		String res = "";
		String token = "";

		for (int ix = 0; ix < buf.length; ix++) {
			token = Integer.toHexString(buf[ix]);

			if (token.length() >= 2) {
				token = token.substring(token.length() - 2);
			} else {
				for (int jx = 0; jx < 2 - token.length(); jx++) {
					token = "0" + token;
				}
			}
			if (ix == 0) {
				res += token;
			} else {
				res += "," + token;
			}
		}

		return res.toUpperCase();
	}

	static public byte[] hexToByteArray(String hex) {
		if (hex == null || hex.length() == 0) {
			return null;
		}

		byte[] ba = new byte[hex.length() / 2];

		for (int i = 0; i < ba.length; i++) {
			ba[i] = (byte) Integer.parseInt(hex.substring(2 * i, 2 * i + 2), 16);
		}
		return ba;
	}

	static public String seedEncrypt(String decryptTxt, String seedKey, String seedIv) {

		byte[] plainText;
		byte[] key; // SEED KEY
		byte[] iv; // SEED IV

		String cipherTextStr = "";

		if ("".equals(decryptTxt) || null == decryptTxt) return "";

		try {
			plainText = getBytes(BinToHex(decryptTxt.getBytes()));
			key = getBytes(BinToHex(DatatypeConverter.parseBase64Binary(seedKey)));
			iv = getBytes(BinToHex(seedIv.getBytes()));
			
			cipherTextStr = getString(KISA_SEED_CBC.SEED_CBC_Encrypt(key, iv, plainText, 0, plainText.length));
			cipherTextStr = new String(DatatypeConverter.printBase64Binary(hexToByteArray(cipherTextStr.replaceAll(",", ""))));
		} catch (Exception e) {
			e.printStackTrace();
		}

		return cipherTextStr;
	}

	static public String seedDecrypt(String encryptTxt, String seedKey, String seedIv) {

		byte[] cipherText;
		byte[] key; // SEED KEY
		byte[] iv; // SEED IV

		String decryptTxt = "";

		if ("".equals(encryptTxt) || null == encryptTxt) return "";

		try {
			cipherText = getBytes(BinToHex(DatatypeConverter.parseBase64Binary(encryptTxt)));
			key = getBytes(BinToHex(DatatypeConverter.parseBase64Binary(seedKey))); // SEEDKEY BASE64DECODE
			iv = getBytes(BinToHex(seedIv.getBytes()));

			decryptTxt = getString(KISA_SEED_CBC.SEED_CBC_Decrypt(key, iv, cipherText, 0, cipherText.length));
			decryptTxt = new String(hexToByteArray(decryptTxt.replaceAll(",", "")));
		} catch (Exception e) {
			e.printStackTrace();
		}

		return decryptTxt;
	}

	static public String encrypteSHA256(String hashParam) throws Exception {

		String req_param = hashParam;

		MessageDigest md = MessageDigest.getInstance("SHA-256");
		md.update(req_param.getBytes());

		byte[] md5Sig = md.digest();
		StringBuffer sb = new StringBuffer();
		for (int i = 0; i < md5Sig.length; i++) {
			String hex = Integer.toHexString(0xff & md5Sig[i]);
			if (hex.length() == 1)
				sb.append('0');
			sb.append(hex);
		}
		return sb.toString();
	}
}
