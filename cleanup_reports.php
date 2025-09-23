<?php
/**
 * Claude Agent Output Cleanup Script
 * Moves root folder reports to organized directories
 */

$rootDir = __DIR__;
$outputsDir = $rootDir . '/.claude/outputs';
$reportsDir = $outputsDir . '/reports';
$analysisDir = $outputsDir . '/analysis';

// Ensure directories exist
if (!is_dir($outputsDir)) mkdir($outputsDir, 0755, true);
if (!is_dir($reportsDir)) mkdir($reportsDir, 0755, true);
if (!is_dir($analysisDir)) mkdir($analysisDir, 0755, true);

// Files to move from root
$filesToMove = [
    'workflow_state.json' => $analysisDir,
    '*_report_*.md' => $reportsDir,
    '*_analysis_*.md' => $analysisDir,
    '*_audit_*.md' => $reportsDir,
    'security_*.md' => $reportsDir,
    'performance_*.md' => $reportsDir,
    'code_review_*.md' => $reportsDir,
];

$moved = 0;
$timestamp = date('Y-m-d_H-i-s');

foreach (glob($rootDir . '/*') as $file) {
    $filename = basename($file);
    
    // Skip directories and essential files
    if (is_dir($file) || in_array($filename, [
        '.env', '.gitignore', 'composer.json', 'package.json', 
        'artisan', 'README.md', 'CLAUDE.md'
    ])) continue;
    
    // Move report files
    if (preg_match('/_(report|analysis|audit)_/', $filename) || 
        preg_match('/^(security|performance|code_review)_/', $filename) ||
        $filename === 'workflow_state.json') {
        
        $targetDir = $analysisDir;
        if (strpos($filename, 'report') !== false || 
            strpos($filename, 'audit') !== false || 
            preg_match('/^(security|performance|code_review)/', $filename)) {
            $targetDir = $reportsDir;
        }
        
        $newPath = $targetDir . '/' . $timestamp . '_' . $filename;
        
        if (rename($file, $newPath)) {
            echo "Moved: $filename -> $newPath\n";
            $moved++;
        }
    }
}

echo "\nCleanup complete! Moved $moved files to organized directories.\n";
echo "Reports: .claude/outputs/reports/\n";
echo "Analysis: .claude/outputs/analysis/\n";
