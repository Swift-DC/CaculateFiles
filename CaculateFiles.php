<?php
/**
 * 文件的统计（包括文件数及总行数）
 *
 */
class CaculateFiles {
    /**
     * 统计的后缀
     */
    private $ext = array(".php",".html",".css",".js");
    /**
     * 是否显示每个文件的统计数
     */
    private $showEveryFile = true;
    /**
     * 文件的的跳过规则
     */
    private $fileSkip = array();
    /**
     * 统计的跳过行规则
     */
    private $lineSkip = array("*", "/*", "//");
    /**
     * 统计跳过的目录规则
     */
    private $dirSkip = array(".", "..", '.svn');

    public function __construct($ext = array(), $dir = '', $showEveryFile = true, $dirSkip = array(), $lineSkip = array(), $fileSkip = array()) {
        $this->setExt($ext);
        $this->setDirSkip($dirSkip);
        $this->setFileSkip($fileSkip);
        $this->setLineSkip($lineSkip);
        $this->setShowFlag($showEveryFile);
        $this->run($dir);
    }

    public function setExt($ext) {
        $ext && is_array($ext) && $this->ext = $ext;
    }
    public function setShowFlag($flag = true) {
        $this->showEveryFile = $flag;
    }
    public function setDirSkip($dirSkip) {
        $dirSkip && is_array($dirSkip) && $this->dirSkip = $dirSkip;
    }
    public function setFileSkip($fileSkip) {
        $this->fileSkip = $fileSkip;
    }
    public function setLineSkip($lineSkip) {
        $lineSkip && is_array($lineSkip) && $this->lineSkip = array_merge($this->lineSkip, $lineSkip);
    }
    /**
     * 执行统计
     * @param string $dir 统计的目录
     */
    public function run($dir = '') {
        if ($dir == '') return;
        if (!is_dir($dir)) exit('Path error!');
        $this->dump($dir, $this->readDir($dir));
    }

    /**
     * 显示统计结果
     * @param string $dir 目录
     * @param array $result 统计结果（包含总行数，有效函数，总文件数
     */
    private function dump($dir, $result) {
        $totalLine = $result['totalLine'];
        $lineNum = $result['lineNum'];
        $fileNum = $result['fileNum'];
        echo "*************************************************************\r\n";
        echo '统计路径：'.$dir . ":\r\n";
        echo "总行数: " . $totalLine . "\r\n";
        echo "去除注释总行数: " . $lineNum . "\r\n<br/>";
        echo '文件总数:' . $fileNum . "\r\n";
    }

    /**
     * 读取目录
     * @param string $dir 目录
     */
    private function readDir($dir) {
        $num = array('totalLine' => 0, 'lineNum' => 0, 'fileNum' => 0);
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if ($this->skipDir($file)) continue;
                if (is_dir($dir . '/' . $file)) {
                    $result = $this->readDir($dir . '/' . $file);
                    $num['totalLine'] += $result['totalLine'];
                    $num['lineNum'] += $result['lineNum'];
                    $num['fileNum'] += $result['fileNum'];
                } else {
                    if ($this->skipFile($file)) continue;
                    list($num1, $num2) = $this->readfiles($dir . '/' . $file);
                    $num['totalLine'] += $num1;
                    $num['lineNum'] += $num2;
                    $num['fileNum']++;
                }
            }
            closedir($dh);
        } else {
            echo 'open dir <' . $dir . '> error!' . "\r";
        }
        return $num;
    }

    /**
     * 读取文件
     * @param string $file 文件
     */
    private function readfiles($file) {
        $str = file($file);
        $linenum = 0;
        foreach ($str as $value) {
            if ($this->skipLine(trim($value))) continue;
            $linenum++;
        }
        $totalnum = count(file($file));
        if (!$this->showEveryFile) return array($totalnum, $linenum);
        echo '统计文件路径：'.$file . "\r\n";
        echo '总行数:' . $totalnum . "\r\n";
        echo '去除注释总行数:' . $linenum . "\r\n";
        return array($totalnum, $linenum);
    }

    /**
     * 执行跳过的目录规则
     * @param string $dir 目录名
     */
    private function skipDir($dir) {
        if (in_array($dir, $this->dirSkip)) return true;
        return false;
    }

    /**
     * 执行跳过的文件规则
     * @param string $file 文件名
     */
    private function skipFile($file) {
        if (!in_array(strtolower(strrchr($file, '.')),$this->ext)) return true;
        if (!$this->fileSkip) return false;
        foreach ($this->fileSkip as $skip) {
            if (strpos($file, $skip) === 0) return true;
        }
        return false;
    }

    /**
     * 执行文件中行的跳过规则
     * @param string $string 行内容
     */
    private function skipLine($string) {
        if ($string == '') return true;
        foreach ($this->lineSkip as $tag) {
            if (strpos($string, $tag) === 0) return true;
        }
        return false;
    }
}