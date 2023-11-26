<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
if (submitcheck('submit')){
    //删除
    if($_GET['delete']){
        $t_ids = array();
        foreach ($_GET['delete'] as $k => $v) {
            if(intval($v)){
                $t_ids[] = intval($v);
            }
        }
        C::t("#ticket#types")->delete($t_ids);
        require_once libfile('function/cloudaddons');
        //删除目录
        foreach ($t_ids as $t_id) {
            cloudaddons_deltree(DISCUZ_ROOT.'./source/plugin/ticket/attach/'.$t_id."/");
            C::t("#ticket#main")->delete_by_tid($t_id);
        }
    }
    //修改
    foreach ($_GET['orders'] as $k => $v) {
        $t_id = intval($k);
        if(!$t_id) continue;
        $data = array();
        $data['t_order']    = intval($v);
        $data['t_name']     = addslashes($_GET['names'][$t_id]);
        if(!$data['t_name']) continue;
        $data['t_desc']     = addslashes($_GET['descs'][$t_id]);
        C::t("#ticket#types")->update($t_id,$data);
    }
    //增加
    foreach ($_GET['newnames'] as $k => $v) {
        $data = array();
        $data['t_order']    = intval($_GET['neworders'][$k]);
        $data['t_name']     = addslashes($v);
        if(!$data['t_name']) continue;
        $data['t_desc']     = addslashes($_GET['newdescs'][$k]);
        C::t("#ticket#types")->insert($data);
    }
    cpmsg('groups_setting_succeed', 'action=plugins&operation=config&do=' . $_GET['do'] . '&identifier=' . $_GET['identifier'] . '&pmod=types', 'succeed');
}else{
    showtips(lang('plugin/ticket', 'slang29'));
    showformheader('plugins&operation=config&do=' . $_GET['do'] . '&identifier=' . $_GET['identifier'] . '&pmod=types');
    showtableheader(lang('plugin/ticket', 'slang30'));
    showtablerow('', array("width='40'",'class="td25"','class="td31"','') , 
        array(
            lang('plugin/ticket', 'slang31'),
            "<b>".lang('plugin/ticket', 'slang32')."<b>",
            "<b>".lang('plugin/ticket', 'slang33')."<b>",
            "<b>".lang('plugin/ticket', 'slang34')."<b>"
    ));

    showtagheader('tbody', '', true);
    $list = C::t("#ticket#types")->fetch_all();
    foreach ($list as $type) {
        showtablerow('', array("width='40'",'class="td25"','class="td31"','') , 
        array(
            "<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$type[t_id]\">",
            "<input type=\"text\" class=\"txt\" name=\"orders[$type[t_id]]\" value=\"$type[t_order]\">",
            "<input type=\"text\" class=\"txt\" name=\"names[$type[t_id]]\" value=\"$type[t_name]\">",
            "<input type=\"text\" class=\"txt\" style='width:50%' name=\"descs[$type[t_id]]\" value=\"$type[t_desc]\"><span class='lightfont'>".lang('plugin/ticket', 'slang35')."$type[t_id]</span>"
        ));
    }
    showtagfooter('tbody');
    echo '<tr><td colspan="4"><div><a href="###" onclick="addrow(this, 0, 0)" class="addtr">'.lang('plugin/ticket', 'slang36').'</a></div></td></tr>';
    showsubmit("submit","submit");
    showtablefooter();
    showformfooter();
?>
<script type="text/JavaScript">
    var rowtypedata = [
        [[1, '', ''], [1,'<input name="neworders[]" type="text" class="txt">', 'td25'], [1, '<div><input name="newnames[]" type="text" class="txt"><a href="javascript:;" class="deleterow" onclick="deleterow(this)"><?php cplang('delete', null, true);?></a></div>', 'td31'], [1, '<input name="newdescs[]" style="width:50%" type="text" class="txt">']]
    ];

</script>
<?php
}
?>