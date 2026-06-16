<?php
$c = file_get_contents('d:\laragon\www\SI-PNBP\resources\views\livewire\admin\audit-dashboard.blade.php');
$lines = explode("\n", $c);
$depth=0;
foreach($lines as $i=>$l) {
    $open = substr_count($l, '<div');
    $close = substr_count($l, '</div');
    $depth += $open - $close;
    if($open>0 || $close>0) echo ($i+1).': depth '.$depth.' | '.$l.PHP_EOL;
}
?>
