<?php

/*$query = 'SELECT name as \'نام\', tel as "تلفن" FROM users WHERE id=<id> and email=email and add=<Add52>';

if (preg_match_all('/<(?!\d|_)([\w\d]+)>/', $query, $arguments)) {
    var_dump($arguments[1]);
} else {
    echo 'no';
}*/

//var_dump(array_column([['id' => 'hi']], 'id'));

//var_dump((bool) array_diff(['a', 'b'], ['a', 'b']));

$pdo = new PDO('mysql:dbname=form-maker;host=localhost', 'root');
//$sth = $pdo->prepare('UPDATE users set updated_at=NOW()');
//$sth = $pdo->prepare('SELECT * FROM users');
//$sth = $pdo->prepare('SELECT * FROM users LIMIT 1');
//$sth = $pdo->prepare('SELECT COUNT(*) FROM users');
$sth = $pdo->prepare('SET FOREIGN_KEY_CHECKS=0;DELETE FROM users WHERE id=1;SET FOREIGN_KEY_CHECKS=1;');
$sth->execute();
var_dump($sth->columnCount());
var_dump($sth->rowCount());
var_dump($sth->fetchObject());
var_dump($sth->fetchAll(PDO::FETCH_OBJ));
