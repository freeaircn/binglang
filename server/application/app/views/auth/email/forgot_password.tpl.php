<html>
<body>
	<h3><?php echo '用户: ' . $identity;?></h3>
	<p><?php echo '请点击链接(5分钟后失效): ' . anchor('reset_password/'. $identity . '/' . 'post/' . $hash_code, '设置新密码');?></p>
	<p><?php echo '发送时间：' . $dt;?></p>
</body>
</html>