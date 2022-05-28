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

if (isset($_GET['insertPlayerButton'])) {
    $lastPlayerId = $conn
        ->createQueryBuilder()
        ->select('MAX(pk_player_id) as lastId')
        ->from('player')
        ->executeQuery()
        ->fetchAllAssociative();
    $insertFirstname = $_GET['insertFirstname'];
    $insertLastname = $_GET['insertLastname'];
    if ($insertFirstname . $insertLastname != "") {
        $conn
            ->createQueryBuilder()
            ->insert('player')
            ->setValue('pk_player_id','?')
            ->setValue('firstname', '?')
            ->setValue('lastname', '?')
            ->setParameter(0, $lastPlayerId[0]['lastId'] + 1)
            ->setParameter(1, $insertFirstname)
            ->setParameter(2, $insertLastname)
            ->executeQuery()
            ->fetchAllAssociative();
    }
    echo '<script type="text/javascript">
    window.location.href = "index.php";
    </script>';
}

if (isset($_GET['insertGameroundButton'])) {
    $lastPlayerId = $conn
        ->createQueryBuilder()
        ->select('MAX(pk_gameround_id) as lastId')
        ->from('gameround')
        ->executeQuery()
        ->fetchAllAssociative();
    $insertPlayer1 = $_GET['insertPlayer1'];
    $insertPlayer1Pick = $_GET['insertPlayer1Pick'];
    $insertPlayer2 = $_GET['insertPlayer2'];
    $insertPlayer2Pick = $_GET['insertPlayer2Pick'];
    if ($insertPlayer1 != "" && $insertPlayer1Pick != "" && $insertPlayer2 != "" && $insertPlayer2Pick != "") {
        // HIER DBAL: SPIEL HINZUFÜGEN
        $winnerId = 0;
        if(($insertPlayer1Pick == 'scissors' && $insertPlayer2Pick == 'paper') ||
            ($insertPlayer1Pick == 'paper' && $insertPlayer2Pick == 'rock') ||
            ($insertPlayer1Pick == 'rock' && $insertPlayer2Pick == 'scissors')){
            $winnerId = $insertPlayer1;
        } elseif (($insertPlayer2Pick == 'scissors' && $insertPlayer1Pick == 'paper') ||
            ($insertPlayer2Pick == 'paper' && $insertPlayer1Pick == 'rock') ||
            ($insertPlayer2Pick == 'rock' && $insertPlayer1Pick == 'scissors')){
            $winnerId = $insertPlayer2;
        } else {
            $winnerId = -1;
        }
        $conn
            ->createQueryBuilder()
            ->insert('gameround')
            ->setValue('pk_gameround_id','?')
            ->setValue('fk_pk_player_1_id', '?')
            ->setValue('fk_pk_player_2_id', '?')
            ->setValue('fk_pk_player_1_pick_id', '?')
            ->setValue('fk_pk_player_2_pick_id', '?')
            ->setValue('fk_pk_winner_id', '?')
            ->setValue('date_time', '?')
            ->setParameter(0, $lastPlayerId[0]['lastId'] + 1)
            ->setParameter(1, $insertPlayer1)
            ->setParameter(2, $insertPlayer2)
            ->setParameter(3, $insertPlayer1Pick)
            ->setParameter(4, $insertPlayer2Pick)
            ->setParameter(5, $winnerId)
            ->setParameter(6, date("Y-m-d H:i:s"))
            ->executeQuery()
            ->fetchAllAssociative();
    }
    echo '<script type="text/javascript">
    window.location.href = "index.php";
    </script>';
}

if (isset($_GET['deletePlayerButton'])) {
    $deletePlayerId = $_GET['deletePlayer'];
    if ($deletePlayerId != "") {
        // HIER DBAL: SPIELER LÖSCHEN
        $conn->delete('player',array('pk_player_id' => $deletePlayerId));
    }
    echo '<script type="text/javascript">
    window.location.href = "index.php";
    </script>';
}

if (isset($_GET['deleteGameroundButton'])) {
    $deleteGameroundId = $_GET['deleteGameround'];
    if ($deleteGameroundId != "") {
        // HIER DBAL: SPIEL LÖSCHEN
        $conn->delete('gameround',array('pk_gameround_id' => $deleteGameroundId));
    }
    echo '<script type="text/javascript">
    window.location.href = "index.php";
    </script>';
}