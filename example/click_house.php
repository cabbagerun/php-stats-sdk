<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Jianzhi\Stats\Dispatch;

try {
    $config = ['swoole_http' => [], 'ch_db' => [], 'redis' => []];
    $tick = new Dispatch($config);
    $qb = $tick->clickHouseOperator();
    $ex = $qb->expr();
    $a = $qb->select('uid, date')
        ->distinct()
        ->from('parts')
        ->database('system')
        ->where(
            $ex->andX(
                $qb->expr()->in('uid', ':widgetUid'),
                $qb->expr()->eq('composite', ':composite')
            )
        )
        ->setParameter('widgetUid', [12, 13, 14, 15])
        ->setParameter('composite', 10)
        ->limit(10, 20)
        ->orderBy('uid', 'item')
        ->orderBy('uid desc', 'item ask')
        ->orderBy('uid, item', 'ask')
        ->orderBy('uid, item desc')
        ->orderBy(['uid' => 'desc', 'item' => 'asc']);
    var_dump($a->__toString());

    $b = $qb->select('uid, date')
        ->distinct()
        ->from('parts')
        ->database('system')
        ->where(
            $ex->andX(
                $qb->expr()->in('uid', ':widgetUid'),
                $qb->expr()->eq('composite', ':composite')
            )
        )
        ->setParameter('widgetUid', [12, 13, 14, 15])
        ->setParameter('composite', 10)
        ->groupBy(['uid'])->andGroupBy('sid')->having(['count()' => ['>', 100]])->andHaving('sum(a) > 10');
    var_dump($b->__toString());
} catch (\Throwable $e) {
    var_dump($e->getMessage());
}