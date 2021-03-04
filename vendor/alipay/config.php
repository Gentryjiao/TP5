<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2021001195601250",

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCI897FXjXQ7XJmuh9Pv+Y/WzfIdQybJfttGp/dC4b03b8BpYoD4Rilxv6LK/OgTjBiXeIk88pmxOwKm2SzWUH2Gjokr1MysuxSR3rAVuXB4JoRRtnHOaf6/4Syo/peefsmNFD41fvchC64mVbKaZMX7GRhowCZNEcppUImS85nCLSohKp+5JtpbP4y9sWYJTR5DQwbPS0fKnGtLtH/t5cHCvzMJi5g8oS7U5TNEyVjImotxF1p4s4ZbMYPD9nF/tUa4Jj/tDvO/4KPVqVB64oFUcbzZlZUBipWsoqPRv8IldSkGD5jvv650iDd/OX8wRD4UgMM235IQGM59z0obrarAgMBAAECggEALB2amitR3J5QZsT3xyHC0o/YtUr3J5chmbCk5HiNHZ28pJQllEBTRzuFzWD4FqB5N4aUjWC2x0oR2wzZcGiFNfiW2DBd9YvrUknSQdj8I+rs1jnjdyicct8SaNUnln1mqJTbrHyJqCwwPKiwa318s40jfMwcKj3L2p9wYpOyxj9r/ocQylnxIE2alRkDHc6Auk8Wc7kwmhJR8SOGynyoCqXlvL4NPiIk0Y8aJfmu4laZYaeLTcmDcJn+dsLDrJqTcpItdxRoUe3CTlKfZQ+ehDM9XA8740kigD4H76J4xoT/sGsww3gW5sk7sepVNCkJoU3dKAcavmeV2lJNKPNRqQKBgQDyX4kffsuCLAilVMqq74zXcj5J/kY3ufiZ9NhUtSWUF4jsAmrXmmOFCM+C6h+KI2l//LTj6D9crDK41eAaCdNc/sDQZUqRgURMyZWbo79gJLvWKO2CTVbUanxHAeWaWldYdVRJxVDiMIU4RW6v6FssL7JJViu6jXuUEZKC75lfdQKBgQCQpwWduweIp22mQxSvfOJaIXOYU/M1I/fE7kUys6byg0TMZkxMQoJ/QiErsMZQ7RM/Y3VluWjuN8S3TIBGZN50NBDwwztQOY1SK+U54nrxEUB3km3FBPyn8OLkkgUYLOgnpQX7QZFuXdyyhciWEO1MpFkSUogaGX7+mOmKtTIZnwKBgAFHk9fAuZ5UUS9eRTulzB6PEdkeKIy+xJs7ebDoy+v+O9DTKnE89W2MYFwWlFgMW9A7K1FQtpTsq8/iAS7iAmWpSqdPAZfHEO8+2TVbJfWB/CPjT22yrtSsQz+0uNuZwoBLDkjda9Hl464qX606EtEm51APzmdLu+1KMnXSggLJAoGBAIRkykgeWXviugIVnYYsyEN0IIfINm5p2CmfZyhLs759FOf7rVOTADuXfCKv7iM9iYNX9ahANeDqnBqkYzeKtOwoh2TFW7FGq6kM0gXT23BuuNF37Q4JTXIskI5hc1Y4K9e1O69GyOP59rhSqE9peLfQKFITxcwVj91YeVBiHVPDAoGAPDb2lUpb6YooP8scU4X8aKJW4yVyQz9a/fkYTOXHqcd75hWvTZp6Fq9oBnm9fBrxZVJmCfx3U2fknzdKogY0uA6uWcEAJQpLXrsSaov3oC+bP8bdx9LUNHqdvdyhSqA05GBq88w4HUp2K7BWy6VZzHSNlRhUOCjoyH6Ghml+TF0=",
		
		//异步通知地址
		'notify_url' => "http://工程公网访问地址/alipay.trade.wap.pay-PHP-UTF-8/notify_url.php",
		
		//同步跳转
		'return_url' => "http://mitsein.com/alipay.trade.wap.pay-PHP-UTF-8/return_url.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAntKLafkCB6qgokOHP34gZYwPSO49oc5cvBYv0rljo2j2G8GJPJN90xmAzrYemy/dqljHaFwusQ/q0Q2xyYyMijrFSVJ9bstRofWTlu8Ue1c+CWL7tz3vAVWKeE01YkzjPcaeG675x3/0bhiUSL7f71KUJCZpCK3qgD9Z7SvcruSLGxEWJ/H/uOuvoJUcIznMp+ZAXO9sXiByq+yKgFuXca9qSPUvnkwslQ0U3io/vQtHTwmlVrL2pRxNItxpLCtWnoR3Aiaqk3UnkJKgfjfJWudUqJza/weAVknd5gBQU16lEOlSJAnlTxLuU0KfzwRAjte21g0oKcxz+AOoCutSBwIDAQAB",
		
	
);