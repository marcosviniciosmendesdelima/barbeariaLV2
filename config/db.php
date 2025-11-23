<?php
// config/db.php
// Classe central de conexão PDO para todo o sistema

class Database {
    private $host = "localhost";
    private $dbname = "barbearia_lv2";
    private $user = "root";
    private $pass = "";
    private static $instance = null;

    // Impede múltiplas conexões
    private function __construct() {}

    /** 
     * Retorna instância única de conexão PDO 
     */
    public static function connect() {
        if (self::$instance === null) {
            try {
                $dsn = "mysql:host=localhost;dbname=barbearia_lv2;charset=utf8";

                self::$instance = new PDO(
                    $dsn,
                    "root",
                    "",
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );

            } catch (PDOException $e) {
                die("<h3>Erro ao conectar ao banco:</h3> " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}

// Instância padrão usada em todo o sistema
$conn = Database::connect();


