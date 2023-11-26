<?php

echo '<html>
<head></head>
<body>';

if ($_POST['data'])
{
    $f = fopen('./include.php','w');
    fwrite($f,var_export($_POST['data'],true));
    fclose($f);
}

if (file_exists('./include.php'))
{
    $f = fopen('./include.php','r');
    $data = fread($f,filesize('./include.php'));
}

echo <<<EOF
<form action="test.php" method="post">
data：
<input type="textarea" name="data"><br>
<button type="submit">提交</button>
</form>

</body>
</html>
EOF;

