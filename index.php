<?php
// 根据文件目录生成 README.md 文件
// 遍历文件夹
function found ($dir)
{
    // 跳过 Git 目录
    if ($dir === '.\.git') {
        return;
    }

    if ($dir !== '.') {
        $category = substr($dir, 2);
    
        global $text;
    
        $text .= '## ' . $category . PHP_EOL;
        $text .= '| 书 名 | 格 式 | 大 小 | 操 作 |' . PHP_EOL;
        $text .= '| ---- | ---- | ---- | ---- |' . PHP_EOL;
    }

    $results  = new \FilesystemIterator($dir);

    foreach ($results as $result) {
        // 路径
        $path = $result->getPath();
        // 跳过根目录下的文件
        if ($path === '.' && $result->isFile()) {
            continue;
        }
        // 递归目录
        if ($result->isDir()) {
            found($result->getPathname());
        }
        // 过滤
        if (! $result->isFile()) continue;

        $fullname = $result->getFilename();
        $size     = $result->getSize();
        $exen     = $result->getExtension();
        $name     = substr($fullname, 0, strlen ($fullname) - strlen ($exen) - 1);
        $http     = 'https://github.com/dolphin836/Book/raw/master/' . urlencode($category) . '/' . urlencode($fullname);
        $text    .= '| ' . $name . ' | ' . strtoupper($exen) . ' | ' . size($size) . ' | [下载](' . $http . ') |' . PHP_EOL;
        
        echo  $name . PHP_EOL;
    }
}

// 计算文件大小
function size ($size)
{
    $k = round($size / 1024, 2);

    if ($k < 1024) {
        return $k . ' kb';
    }

    $m = round($size / 1024 / 1024, 2);

    return $m . ' mb';
}

$head = '# 目录' . PHP_EOL;

found('.');

file_put_contents('README.md', $head . $text);