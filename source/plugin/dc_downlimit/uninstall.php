<?php
/*
 *Ѷ������www.xhkj5.com
 *������ҵ���/ģ��������� ����Ѷ����
 *����Դ��Դ�������ռ�,��������ѧϰ����������������ҵ��;����������24Сʱ��ɾ��!
 *����ַ�������Ȩ��,�뼰ʱ��֪����,���Ǽ���ɾ��!
 */


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

DROP TABLE IF EXISTS `pre_dc_downlimit`;
DROP TABLE IF EXISTS `pre_dc_downlimit_user`;
EOF;

runquery($sql);

$finish = TRUE;

?>