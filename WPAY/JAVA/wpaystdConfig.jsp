<%@page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%
	//Request Domain
	String requestDomain = "https://stgwpaystd.inicis.com"; //상용 Domain.
	
	// 가맹점 ID(가맹점 수정후 고정)
	String g_MID = "INIwpayT03";							

	// 가맹점에 제공된 암호화 키(고정값)
	String g_HASHKEY 	= "F3149950A7B6289723F325833F588STD";
	String g_SEEDKEY 	= "rClo7QA4gdgyITHAPWrfXw==";
	String g_SEEDIV 	= "WPAYSTDWPAY00000";	
	
%>