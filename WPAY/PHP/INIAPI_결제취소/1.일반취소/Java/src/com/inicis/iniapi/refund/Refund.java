package com.inicis.iniapi.refund;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;
import java.nio.charset.StandardCharsets;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

public class Refund {

	public static void main(String[] args) {

		Date date_now = new Date(System.currentTimeMillis());
		SimpleDateFormat fourteen_format = new SimpleDateFormat("yyyyMMddHHmmss");
		SHA512 sha512 = new SHA512();

		//step1. 요청을 위한 파라미터 설정
		String key = "ItEQKi3rY7uvDS8l"; 
		String type = "Refund";
		String paymethod = "Card";
		String timestamp = fourteen_format.format(date_now);
		String clientIp = "111.222.333.889";
		String mid = "INIpayTest";
		String tid = ""; 
		String msg = "취소요청";
		
		// Hash Encryption
		String data_hash = key + type + paymethod + timestamp + clientIp + mid + tid;
		String hashData = sha512.hash(data_hash);
		
		// Request URL
		String apiUrl = "https://iniapi.inicis.com/api/v1/refund";

		Map<String, Object> resultMap = new HashMap<String, Object>();

		resultMap.put("type", type);
		resultMap.put("paymethod", paymethod);
		resultMap.put("timestamp", timestamp);
		resultMap.put("clientIp", clientIp);
		resultMap.put("mid", mid);
		resultMap.put("tid", tid);
		resultMap.put("msg", msg);
		resultMap.put("hashData", hashData);
		
		StringBuilder postData = new StringBuilder();
		for(Map.Entry<String, Object> params: resultMap.entrySet()) {
			
			if(postData.length() != 0) postData.append("&");
			try {
				postData.append(URLEncoder.encode(params.getKey(), "UTF-8"));
				postData.append("=");
				postData.append(URLEncoder.encode(String.valueOf(params.getValue()), "UTF-8"));
			} catch (Exception e) {
				e.printStackTrace();
			}
		}
		
		//step2. key=value 로 post 요청
		try {
			URL url = new URL(apiUrl);
			HttpURLConnection conn = (HttpURLConnection) url.openConnection();
			
			if (conn != null) {
				conn.setRequestProperty("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
				conn.setRequestMethod("POST");
				conn.setDefaultUseCaches(false);
				conn.setDoOutput(true);
				
				if (conn.getDoOutput()) {
					conn.getOutputStream().write(postData.toString().getBytes("UTF-8"));
					conn.getOutputStream().flush();
					conn.getOutputStream().close();
				}

				conn.connect();
				
					BufferedReader br = new BufferedReader(new InputStreamReader(conn.getInputStream(), StandardCharsets.UTF_8));
					
					//step3. 요청 결과
					System.out.println(br.readLine());
					br.close();
				}

		}catch(Exception e ) {
			e.printStackTrace();
		} 
	}
}

