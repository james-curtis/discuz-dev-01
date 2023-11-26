<?php
error_reporting(0);
$dirname = "F:\\BaiduNetdiskDownload";
$file=scandir($dirname);
print_r($file);

function listFiles($dir)
{
    //打开目录

    $handle=opendir($dir);
    //阅读目录
    while(false!=($file=readdir($handle)))
    {
        //列出所有文件并去掉'.'和'..'
        if($file!='.'&&$file!='..')
        {
            //所得到的文件名是否是一个目录
            if(is_dir("$dir/$file"))
            {
                //列出目录下的文件
                listFiles("$dir/$file");
            }
            else
            {
                md5("$dir/$file");
                //如果是文件则打开该文件

                $fp=fopen("$dir/$file","r");

                //阅读文件内容
                $data=fread($fp,filesize("$dir/$file"));
                if($data)
                    //将读到的内容赋值给一个数组
                    $file_array[]="$dir/$file";
                /*foreach($file_array as $key=>$value)
                {
                    echo "$value<br>";
                   }
                   */
                //echo count($file_array);
                //输出结果
                while(list($key,$value)=each($file_array))
                {
                    echo"$key=>$value<br>";
                }
            }
        }
    }
}
/*------------------------------------------*/
//调用
$dir="H:/temp";
listFiles($dir);

?>

<html>
<head>
    <title>列出目录下所有文件</title>
    <head>
<body>
<p>Files in <?php echo( $dirname ); ?> </p>
<ul>
    <?php echo( $file_list ); ?>
</ul>
</body>
</html>