<?php
/*
 *Դ��磺www.ymg6.com
 *������ҵ���/ģ��������� ����Դ���
 *����Դ��Դ�������ռ�,��������ѧϰ����������������ҵ��;����������24Сʱ��ɾ��!
 *����ַ�������Ȩ��,�뼰ʱ��֪����,���Ǽ���ɾ��!
 */
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
//����discuz ʱ��js
echo '<script type="text/javascript" src="static/js/calendar.js"></script>';
//�����ʾ��Ϣ
showtips(lang('plugin/ymg6_vip', 'tips'));
//���ʼ
showtableheader(lang('plugin/ymg6_vip', 'ruletitle'));
showtablerow('','',lang('plugin/ymg6_vip', 'rulenote00'));
showtablerow('','',lang('plugin/ymg6_vip', 'rulenote01'));
showtablerow('','',lang('plugin/ymg6_vip', 'rulenote02'));
showtablerow('','',lang('plugin/ymg6_vip', 'rulenote03'));
showtablefooter();
?>