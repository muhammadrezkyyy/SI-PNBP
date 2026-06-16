<?php
$data = file_get_contents('https://placehold.co/200x200/2B3182/FFFFFF/png?text=Kemenkeu');
file_put_contents('d:/laragon/www/SI-PNBP/public/images/kemenkeu_logo.png', $data);
echo "Done";
