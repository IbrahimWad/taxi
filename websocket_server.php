<?php
require 'vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

// Include the database connection file
require 'connection.php';

// Define your WebSocket server class
class MyWebSocketServer implements MessageComponentInterface {
    protected $clients;
    protected $pdo;

    public function __construct() {
        $this->clients = new \SplObjectStorage();
        require 'connection.php'; // Include the database connection file
        $this->pdo = $pdo; // Assign the database connection to the instance variable
    }
    
    private function fetchAllData($tableName) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM $tableName");
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [$tableName => $data];
        } catch (PDOException $e) {
            echo "Database Error: " . $e->getMessage();
            return [];
        }
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);

        // Send the available table names to the newly connected client
        $tables = ['inregular', 'invip', 'outregular', 'outvip', 'messages'];
        $conn->send(json_encode(['tables' => $tables]));
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // Handle the data from the client request
        $data = json_decode($msg, true);
    
        // Validate the required fields
        if (!isset($data['table'])) {
            // Send an error message back to the client
            $from->send(json_encode(['error' => 'Missing required fields']));
            return;
        }
    
        // Access the table and values fields
        $table = $data['table'];
        $values = $data['values'];
    
        // Validate the table name
        $validTables = ['inregular', 'invip', 'outregular', 'outvip', 'messages'];
        if (!in_array($table, $validTables)) {
            // Send an error message back to the client
            $from->send(json_encode(['error' => 'Invalid table']));
            return;
        }
    
        // Insert or update the data into the SQL database based on the table
        try {
            if ($table === 'messages') {
                $stmt = $this->pdo->prepare("INSERT INTO $table (senderNumber, receiverNumber, message) VALUES (?, ?, ?)");
                $stmt->execute([
                    $values['senderNumber'],
                    $values['receiverNumber'],
                    $values['message'],
                ]);
            } else {
                $stmt = $this->pdo->prepare("INSERT INTO $table (mylatitude, mylongitude, latitude, longitude, phone, salary, payment) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $values['mylatitude'],
                    $values['mylongitude'],
                    $values['latitude'],
                    $values['longitude'],
                    $values['phone'],
                    $values['salary'],
                    $values['payment'],
                ]);
            }
    
            // Fetch all the data from the specified table
            $tableData = $this->fetchAllData($table);
    
            // Send the updated data to the client
            $from->send(json_encode($tableData));
        } catch (PDOException $e) {
            // Handle any errors that occur during database insertion
            echo "Database Error: " . $e->getMessage();
        }
    }
    
    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}

// Create an instance of the WebSocket server
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new MyWebSocketServer()
        )
    ),
    8080
);

// Start the WebSocket server
$server->run();
