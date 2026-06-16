<?php
$c = file_get_contents('d:\laragon\www\SI-PNBP\resources\views\livewire\admin\audit-dashboard.blade.php');
$lines = explode("\n", $c);
$depth=0;
foreach($lines as $i=>$l) {
    $open = substr_count($l, '<div');
    $close = substr_count($l, '</div');
    $depth += $open - $close;
    if($depth < 0) {
        echo 'Negative depth at line '.($i+1).': '.$l.PHP_EOL;
        $depth=0;
    }
    if($depth == 0) {
        echo 'Zero depth at line '.($i+1).': '.$l.PHP_EOL;
    }
}
echo 'Final depth: '.$depth.PHP_EOL;
?>
