<?php
$socket = getenv('MYSQL_SOCK');
$username = 'root';
$password = getenv('MYSQL_PASSWORD');
$database = 'demoDb';
$table = 'demoTable';

$results = [];
try {
    // Connect to database
    $handler = new PDO("mysql:unix_socket={$socket}", $username, $password);

    // Create the database and table if they don't already exist
    $query = $handler->prepare("create database {$database}");
    $query->execute();
    $query = $handler->prepare("use {$database}");
    $query->execute();
    $query = $handler->prepare("create table ${database}(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, time INT)");
    $query->execute();

    // Insert a new row with the current time
    $time = (int)microtime(true);
    $query = $handler->prepare("insert into {$database} (time) values ({$time})");
    $query->execute();

    // Fetch all rows
    $query = $handler->prepare("select * from ${database}");
    $query->execute();
    $results = $query->fetchAll();
} catch (PDOException $e) {
    print 'Error!: ' . $e->getMessage() . "<br/>";
    die();
}

?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
  <meta charset="utf-8" />
</head>
<body>
  <table border=1>
    <thead>
      <tr>
        <td>
          ID
        </td>
        <td>
          TIME
        </td>
      </tr>
    </thead>
    <tbody>
<?php
foreach ($results as $result) {
    ?>
      <tr>
        <td>
<?php echo $result['id']; ?>
        </td>
        <td>
<?php echo date('d-m-Y G:i:s', $result['time']); ?>
        </td>
      </tr>
    <?php
}
?>
    </tbody>
  </table>
</body>
</html>
