<?php

/**
 * Result range summary for dokumen listings.
 *
 * Must assign Pagination::$totalCount before reading page/offset. Otherwise Yii2
 * validatePage clamps the requested page to 0 while totalCount is still 0, and
 * the cached page sticks — so LIMIT/OFFSET never advances on ?page=2.
 *
 * @var yii\data\DataProviderInterface $dataProvider
 */

$totalCount = $dataProvider->getTotalCount();
$pagination = $dataProvider->getPagination();

if ($pagination !== false) {
    $pagination->totalCount = $totalCount;
}

if ($totalCount <= 0 || $pagination === false) {
    echo 'Tidak ada dokumen ditemukan';
    return;
}

$from = $pagination->getOffset() + 1;
$to = min($pagination->getOffset() + $pagination->getLimit(), $totalCount);

echo 'Menampilkan ' . $from . ' - ' . $to . ' dari ' . number_format($totalCount) . ' dokumen';
