<?php

namespace Htlw3r\M7usarps;

use Doctrine\DBAL\DriverManager;

require_once 'vendor/autoload.php';

$connectionParams = [
    'dbname' => 'usarps',
    'user' => 'root',
    'password' => '',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
];
$conn = DriverManager::getConnection($connectionParams);

$result = $conn
    ->createQueryBuilder()
    ->select('*')
    ->from('gameround')
    ->executeQuery()
    ->fetchAllAssociative();

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$template = $twig->load('main.html');

$rounds = null;

foreach ($result as $round){
    $player1 = $conn
        ->createQueryBuilder()
        ->select('firstname','lastname')
        ->from('player')
        ->where('pk_player_id = ' . $round['fk_pk_player_1_id'])
        ->executeQuery()
        ->fetchAllAssociative();
    $player2 = $conn
        ->createQueryBuilder()
        ->select('firstname','lastname')
        ->from('player')
        ->where('pk_player_id = ' . $round['fk_pk_player_2_id'])
        ->executeQuery()
        ->fetchAllAssociative();

    $rounds[] = array('gameround' => $round['pk_gameround_id'], 'datetime' => $round['date_time'],
        'player1_firstname' => $player1[0]['firstname'], 'player1_lastname' => $player1[0]['lastname'],
        'player1_pick' => $round['fk_pk_player_1_pick_id'] , 'player2_firstname' => $player2[0]['firstname'],
        'player2_lastname' => $player2[0]['lastname'], 'player2_pick' => $round['fk_pk_player_2_pick_id']);
}

$allPlayersDB = $conn
    ->createQueryBuilder()
    ->select('*')
    ->from('player')
    ->executeQuery()
    ->fetchAllAssociative();

$allPlayers = null;

foreach ($allPlayersDB as $player) {
    $allPlayers[] = array('player_id' => $player['pk_player_id'], 'name' => $player['firstname'] . ' ' . $player['lastname']);
}

$allPicksDB = $conn
    ->createQueryBuilder()
    ->select('*')
    ->from('pick')
    ->executeQuery()
    ->fetchAllAssociative();

$allPicks = null;

foreach ($allPicksDB as $pick) {
    $allPicks[] = array('pick' => $pick['pk_pick']);
}

echo $template->render([
    'allGames' => $rounds,
    'allPlayers' => $allPlayers,
    'allPicks' => $allPicks
]);