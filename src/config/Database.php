<?php

use leona\crud\config\DatabaseInterface;

require_once "./vendor/autoload.php";

class Database implements DatabaseInterface
{
    private SQLite3 $db;

    public function __construct(string $filename)
    {   
        $this->prepareName($filename);
        $this->db = new SQLite3($filename);    
        $this->returnVlaue = $this->prepare();
    }

    public function getDb()
    {
        return $this->db;
    }

    private function prepareName(string $tempFilename)
    {
        $sqliteFormat = pathinfo($tempFilename);

        if($sqliteFormat['extension'] !== 'sqlite3') {
            throw new ErrorException("$tempFilename não tem uma extensão válida");
        }
    }

    private function prepare() 
    {
        $stmt = "CREATE TABLE IF NOT EXISTS personagens(
            nome TEXT PRIMARY KEY NOT NULL,
            especie TEXT NOT NULL,
            imagem TEXT,
            status TEXT,
            genero TEXT
        )";

        return $this->db->exec($stmt);
    }

}