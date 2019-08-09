# CaculateFiles
##PHP代码行数统计

##使用方法
```
$obj = new CaculateFiles();
//是否显示每个文件详情
$obj->setShowFlag(false);
//设置过滤目录
$obj->setDirSkip(array(".", "..","PHPExcel", "WxPay", "vendor", "barcodegen","sql","tests","thinkphp",'.svn','.idea','runtime','js','css'));
//设置统计文件后缀名
$obj->setExt(array('.php','.html','.css','.js'));
//设置过滤统计文件名
$obj->setFileSkip(array(''));
//开始搜索目录
$obj->run("D:\www\bom");
```
