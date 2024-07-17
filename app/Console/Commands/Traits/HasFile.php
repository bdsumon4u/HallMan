<?php

namespace App\Console\Commands\Traits;

use App\Facades\Hotash;
use Generator;
use PhpParser\Node;

trait HasFile
{
    private function getPhpFiles(): Generator
    {
        $projectDir = $this->argument('project');

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($projectDir),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php') {
                yield $file->getPathname();
            }
        }
    }

    private function obfuscateFile($file): void
    {
        try {
            $code = file_get_contents($file);
            $nodes = $this->parser->parse($code);
            $stmts = $this->traverser->traverse($nodes);

            if (Hotash::get('t_shuffle_statements') && (count($stmts) > 2)) {
                $last_inst  = array_pop($stmts);
                $last_use_stmt_pos = -1;
                foreach ($stmts as $i => $stmt)                      // if a use statement exists, do not shuffle before the last use statement
                {                                                   //TODO: enhancement: keep all use statements at their position, and shuffle all sub-parts
                    if ($stmt instanceof Node\Stmt\Use_) $last_use_stmt_pos = $i;
                }

                if ($last_use_stmt_pos < 0) {
                    $stmts_to_shuffle = $stmts;
                    $stmts = [];
                } else {
                    $stmts_to_shuffle = array_slice($stmts, $last_use_stmt_pos + 1);
                    $stmts = array_slice($stmts, 0, $last_use_stmt_pos + 1);
                }

                $stmts      = array_merge($stmts, Hotash::shuffle_statements($stmts_to_shuffle));
                $stmts[]    = $last_inst;
            }

            $code = $this->printer->prettyPrintFile($stmts);

            if (Hotash::get('t_strip_indentation')) {
                $code = $this->remove_whitespaces($code);
            }
            $endcode = substr($code, 7);

            $code = '<?php'.PHP_EOL.Hotash::comment();

            file_put_contents($file, $code.$endcode);
        } catch (\PhpParser\Error $e) {
            $this->error("Parse error: {$e->getMessage()}");
        }
    }

    private function remove_whitespaces($str)
    {
        $tmp_filename = @tempnam(sys_get_temp_dir(), 'obfuscator-');
        file_put_contents($tmp_filename, $str);
        $str = php_strip_whitespace($tmp_filename);  // can remove more whitespaces
        unlink($tmp_filename);

        return $str;
    }

    private function outputPath($file): string
    {
        $projectDir = $this->argument('project');
        $outputDir = $this->argument('output');

        $outputFile = str_replace($projectDir, $outputDir, $file);

        if (! file_exists(dirname($outputFile))) {
            mkdir(dirname($outputFile), 0755, true);
        }

        return $outputFile;
    }
}
