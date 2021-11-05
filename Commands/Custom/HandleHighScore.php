<?php

class HandleHighScore
{

    public function handleGameScore($user_id, $highScore)
    {
        $config = require __DIR__ . '/../../config.php';
        $mysqlHost = $config['mysql']['host'];
        $mysqlDbName = $config['mysql']['database'];
        $mysqlUser = $config['mysql']['user'];
        $mysqlPassword = $config['mysql']['password'];

        $pdoObj = new PDO("mysql:host=$mysqlHost;dbname=$mysqlDbName", $mysqlUser, $mysqlPassword);

        $sqlQuery = "SELECT id, user_id FROM game_score WHERE user_id = ?";
        $pdoSqlObj = $pdoObj->prepare($sqlQuery);
        $pdoSqlObj->execute([$user_id]);
        $gameUser = $pdoSqlObj->fetchAll(PDO::FETCH_ASSOC);

        if (!$pdoSqlObj->rowCount()) {
            $sqlQuery = "INSERT INTO game_score (user_id, score) VALUES (?, ?)";
            $pdoSqlObj = $pdoObj->prepare($sqlQuery);
            $pdoSqlObj->execute([$user_id, $highScore]);
        } else {
            $gameUserId = $gameUser[0]['id'];
            $sqlQuery = "UPDATE game_score SET score = ? WHERE id = ?";
            $pdoSqlObj = $pdoObj->prepare($sqlQuery);
            $pdoSqlObj->execute([$highScore, $gameUserId]);
        }

        $sqlQuery = "
            SELECT
              g.id,
              g.user_id,
              g.score,
              u.first_name,
              u.last_name
            FROM
              user AS u
            INNER JOIN
              game_score AS g ON u.id = g.user_id
            ORDER BY
              g.user_id DESC
        ";

        $pdoSqlObj = $pdoObj->prepare($sqlQuery);
        $pdoSqlObj->execute([$user_id]);
        $gameUsers = $pdoSqlObj->fetchAll(PDO::FETCH_ASSOC);

        return json_encode($gameUsers);
    }
}