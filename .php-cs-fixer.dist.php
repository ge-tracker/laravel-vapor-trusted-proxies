<?php

use PhpCsFixer\Finder;

$project_path = getcwd();
$finder = Finder::create()
    ->in([
        $project_path . '/src',
        $project_path . '/tests',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return JamesAusten\styles($finder);
