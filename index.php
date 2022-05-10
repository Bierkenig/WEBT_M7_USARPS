<?php

namespace Htlw3r\M7usarps;

use Doctrine\DBAL\DriverManager;

require_once 'vendor/autoload.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M7 - USARPS Championship 2020</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
</head>

<?php

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

$body = '<body><h1 class="text-center mt-3">USARPS Championship 2020</h1>';

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

    $body .= '<div><h4>Game ' . $round['pk_gameround_id'] . ' (' . $round['date_time'] . ')</h4>';
    $body .= $player1[0]['firstname'] . ' ' . $player1[0]['lastname'] . ': ' . $round['fk_pk_player_1_pick_id'] . '<br>';
    $body .= $player2[0]['firstname'] . ' ' . $player2[0]['lastname'] . ': ' . $round['fk_pk_player_2_pick_id'] . '</div><br><br>';

}

$body .= '</body></html>';

echo $body;
