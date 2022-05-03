<?php

namespace Htlw3r\M7usarps;

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
$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);
$queryBuilder = $conn->createQueryBuilder();

$result = $queryBuilder
    ->select('*')
    ->from('gameround')
    ->executeQuery()
    ->fetchAllAssociative();

$body = '
<body>
    <h1 class="text-center mt-3">USARPS Championship 2020</h1>';


//FEHLER
foreach ($result as $round){
    $player1 = $queryBuilder
        ->select('firstname','lastname')
        ->from('player')
        ->where('pk_player_id = '.$round['fk_pk_player_1_id'])
        ->executeQuery()
        ->fetchAllAssociative();
    echo $round['fk_pk_player_1_id'];
    var_dump($player1);
    $body .= '<div>'.implode($round).'</div>';
}

$body .= '    
</body>
</html>';

echo $body;
