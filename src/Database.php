<?php
namespace App;

use PDO;
use PDOException;

class Database {
    private $hostname = '136.243.8.201';
    private $port = 1521;
    private $service_name = 'ipack';
    private $username = 'Demo';
    private $password = 'Demo';
    private $dsn;
    private $pdo = null;
    private $oci_connect = null;

    public function __construct() {
        $this->dsn = "oci:dbname=//{$this->hostname}:{$this->port}/{$this->service_name};charset=utf8";
        try {
            $hostname = '136.243.8.201'; // Hostname
            $port = 1521; // Port
            $service_name = 'ipack'; // Service name
            $username = 'Demo'; // Oracle username
            $password = 'Demo'; // Oracle password

            // $dsn = "oci:dbname=//$hostname:$port/$service_name;charset=utf8";
            // $conn = new PDO($dsn, $username, $password);
            // $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            
        } catch (PDOException $e) {
            die("Connecdddtion failed: " . $e->getMessage());
        }
    }

    public function connect() {
        if (!$this->pdo) {
            try {
                $this->pdo = new PDO($this->dsn, $this->username, $this->password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return $this->pdo;
    }

    public function Oci_connection() {
        if (!$this->oci_connect) {
            try {
                $this->oci_connect = \oci_connect($this->username, $this->password, "{$this->hostname}/{$this->service_name}");
                if (!$this->oci_connect) {
                    $e = oci_error();
                    die('Connection failed: ' . $e['message']);
                }
            } catch (Exception $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return $this->oci_connect;
    }
    public function close() {
          // Close PDO connection
          if ($this->pdo) {
            $this->pdo = null;
        }

        // Close OCI connection
        if ($this->oci_connect) {
            oci_close($this->oci_connect);
            $this->oci_connect = null;
        }
    }
}
