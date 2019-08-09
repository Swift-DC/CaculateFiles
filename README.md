CaculateFiles PHP文件代码行数统计
===============
##使用方法
```
$obj = new CaculateFiles();
//是否显示单个文件详情
$obj->setShowFlag(false);
//设置过滤目录
$obj->setDirSkip(array(".", "..","PHPExcel", "WxPay", "vendor", "barcodegen","sql","tests","thinkphp",'.svn','.idea','runtime','js','css'));
//设置统计文件后缀名
$obj->setExt(array('.php','.html','.css','.js'));
//设置过滤文件名（以***开头文件）
$obj->setFileSkip(array('All'));
//设置统计目录
$obj->run("\www\");
```
