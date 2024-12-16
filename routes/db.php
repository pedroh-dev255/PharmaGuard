<?php


date_default_timezone_set('America/Araguaina');

// Carrega as variáveis de ambiente do arquivo .env
$env = parse_ini_file('../.env');

// Verifica se o arquivo .env foi carregado corretamente
if ($env === false) {
    die("Erro ao carregar o arquivo .env");
}

// Create connection
$conn = new mysqli($env['DB_HOST'], $env['DB_USER'], $env['DB_PASS'], $env['DB_NAME']);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


// Create connection
$conn_press = new mysqli($env['DB_HOST_PRESS'], $env['DB_USER_PRESS'], $env['DB_PASS_PRESS'], $env['DB_NAME_PRESS']);

if ($conn_press->connect_error) {
  die("Connection failed: " . $conn_press->connect_error);
}


/*
$sql = "SELECT id, firstname, lastname FROM MyGuests";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "<table><tr><th>ID</th><th>Name</th></tr>";
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "<tr><td>".$row["id"]."</td><td>".$row["firstname"]." ".$row["lastname"]."</td></tr>";
  }
  echo "</table>";
} else {
  echo "0 results";
}
$conn->close();

*/
?>