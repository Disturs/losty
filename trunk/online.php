<?php
/*
	@ PHP ��������ͳ�Ƴ���
	Copyright (c) www.vgot.cn by Pader 1:25 2009��1��7��
	Homepage:http://www.vgot.cn
	QQ: 270075658
	How to use it: <script src="online.php"></script>
	note: һ�������������ͳ�Ƴ�����ͳ�����ߵ�IP�������Ⲣ��׼ȷ
	����������ķ����ߣ����繫˾��ѧУ���������ɣ���Ȼ����IP��ͬ����������IP����һ��
	���ͬһ�������������۶������˷��������վ��ֻ����Ϊ��һ����
	���С�ɵĳ������˴����⣬���Ե���Ϊ��Ϊ��ÿ̨���Ա���һ��������
	��Ȼ��Ϊʹ�õ���COOKIE���������ͬһ̨������ʹ�����ֲ�ͬ���ĵ�����������Ǿͱ𵱱�����
*/
$filename = 'online.txt';  //�����ļ�
$cookiename = 'VGOTCN_OnLineCount';  //cookie����
$onlinetime = 600;  //������Чʱ�䣬��λ���� (��600����10����)

$online = file($filename); 
$nowtime = time(); 
$nowonline = array();

/*
	@ �õ���Ȼ��Ч������
*/
foreach($online as $line) {
	$row = explode('|',$line);
	$sesstime = trim($row[1]);
	if(($nowtime - $sesstime) <= $onlinetime) {  //���������Чʱ���ڣ������ݼ������棬���򱻷�������ͳ��
		$nowonline[$row[0]] = $sesstime;  //��ȡ�����б����飬�ỰIDΪ���������ͨ��ʱ��Ϊ��ֵ
	}
}

/*
	@ ����������ͨ��״̬
		ʹ��cookieͨ��
		COOKIE ���ڹر������ʱʧЧ����������ر���������� COOKIE ��һֱ��Ч��ֱ���������õ�����ʱ�䳬ʱ
*/
if(isset($_COOKIE[$cookiename])) {  //�����COOKIE�����ǳ��η������������������ͨ��ʱ��
	$uid = $_COOKIE[$cookiename];
} else {  //���û��COOKIE���ǳ��η���
	$vid = 0;  //��ʼ��������ID
	do {  //���û�һ����ID
		$vid++;
		$uid = 'U'.$vid;
	} while (array_key_exists($uid,$nowonline));
	setcookie($cookiename,$uid);
}
$nowonline[$uid] = $nowtime;  //�������ڵ�ʱ��״̬

/*
	@ ͳ��������������
*/
$total_online = count($nowonline);

/*
	@ д������
*/
if($fp = @fopen($filename,'w')) {
	if(flock($fp,LOCK_EX)) {
		rewind($fp);
		foreach($nowonline as $fuid => $ftime) {
			$fline = $fuid.'|'.$ftime."\n";
			@fputs($fp,$fline); 
		}
		flock($fp,LOCK_UN);
		fclose($fp);
	}
}
	echo 'document.write("'.$total_online.'");'; 
?>