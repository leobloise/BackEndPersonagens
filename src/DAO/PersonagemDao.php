<?php

namespace leona\crud\DAO;

use Error;
use Exception;
use leona\crud\config\DatabaseInterface;
use SQLite3;
use Throwable;

require "vendor/autoload.php";

class PersonagemDao 
{

    private DatabaseInterface $db;
    private string $tablename;

    public function __construct(DatabaseInterface $db, string $tablename) 
    {
        $this->db = $db;
        $this->tablename = $tablename;
    }

    public function createPersonagem(string $nome, string $especie, $imagem, $status, $genero): bool
    {
        try {
            $stmt = "INSERT INTO {$this->tablename}('nome', 'especie', 'imagem', 'status', 'genero') VALUES(:nome, :especie, :imagem, :status, :genero)";
        
            $prepared = $this->db->getDb()->prepare($stmt);
            $prepared->bindValue(":nome", $nome);
            $prepared->bindValue(":especie", $especie);
            $prepared->bindValue(":imagem", $imagem);
            $prepared->bindValue(":status", $status);
            $prepared->bindValue(":genero", $genero);

            $result = $prepared->execute();

            return $result->finalize();
        } catch(Throwable $error) {
            syslog(LOG_ERR, $error->getMessage());
            throw new Exception("Não foi possível criar um novo personagem.");
        }
        
    }

    public function deletePersonagem(string $nome)
    {

        try {
            
            $stmt = "DELETE FROM {$this->tablename} WHERE nome = :nome";

            $prepared = $this->db->getDb()->prepare($stmt);
            $prepared->bindValue(":nome", $nome);

            $result = $prepared->execute();

            return $result->finalize();
        
        } catch(Throwable $error) {

            syslog(LOG_ERR, $error->getMessage());
            throw new Exception("Não foi possível deletar o personagem");

        }

    }


    public function updatePersonagem(string $nome, string $fieldtochange, string $newvalue)
    {
        try {
            
            $stmt = "UPDATE {$this->tablename} SET $fieldtochange = :newvalue WHERE nome = :nome";

            $prepared = $this->db->getDb()->prepare($stmt);
            $prepared->bindValue(":nome", $nome);
            $prepared->bindValue(":newvalue", $newvalue);

            $result = $prepared->execute();

            return $result->finalize(); 
        
        } catch(Throwable $error) {

            syslog(LOG_ERR, $error->getMessage());
            throw new Exception("Não foi possível atualizar o personagem");

        }
    }

    public function filterGenero (string $value)
    {

        try {

            $stmt = "SELECT * FROM {$this->tablename} WHERE genero LIKE :genero";
        
            $prepared = $this->db->getDb()->prepare($stmt);
            $prepared->bindValue(":genero", "%$value%");

            $result = $prepared->execute();

            $rows = [];

            while($res = $result->fetchArray(SQLITE3_ASSOC)) {
                $rows[] = $res;
            }

            return $rows;
            
        } catch(Throwable $error) {
            syslog(LOG_ERR, $error->getMessage());
            return false;
        }

    }

    public function filterStatus (string $value)
    {

        try {

            $stmt = "SELECT * FROM {$this->tablename} WHERE status LIKE :especie";
        
            $prepared = $this->db->getDb()->prepare($stmt);
            $prepared->bindValue(":especie", "%$value%");

            $result = $prepared->execute();

            $rows = [];

            while($res = $result->fetchArray(SQLITE3_ASSOC)) {
                $rows[] = $res;
            }

            return $rows;
            
        } catch(Throwable $error) {
            syslog(LOG_ERR, $error->getMessage());
            return false;
        }

    }

    public function filterByName(string $value)
    {

        try {

            $stmt = "SELECT nome, especie, status, genero FROM {$this->tablename} WHERE nome LIKE :valuetofilter";
        
            $prepared = $this->db->getDb()->prepare($stmt);
            $prepared->bindValue(":valuetofilter", "%$value%");

            $result = $prepared->execute();

            $rows = [];

            while($res = $result->fetchArray(SQLITE3_ASSOC)) {
                $rows[] = $res;
            }

            return $result->fetchArray();
            
        } catch(Throwable $error) {
            syslog(LOG_ERR, $error->getMessage());
            return false;
        }

    }
    
    public function getPersonagem(string $nome)
    {

        try {

            $stmt = "SELECT nome, especie, status, genero FROM {$this->tablename} WHERE nome = :nome";
        
            $prepared = $this->db->getDb()->prepare($stmt);
            $prepared->bindValue(":nome", $nome);

            $result = $prepared->execute();

            $rows = [];

            while($res = $result->fetchArray(SQLITE3_ASSOC)) {
                $rows[] = $res;
            }

            return $result->fetchArray();
            
        } catch(Throwable $error) {
            syslog(LOG_ERR, $error->getMessage());
            return false;
        }
    }
    
    public function getAllEspecies()
    {
        try {

            $stmt = "SELECT especie FROM {$this->tablename}";
        
            $prepared = $this->db->getDb()->prepare($stmt);

            $result = $prepared->execute();

            $rows = [];

            while($res = $result->fetchArray(SQLITE3_ASSOC)) {
                $rows[] = $res;
            }

            return $rows;
            
        } catch(Throwable $error) {
            syslog(LOG_ERR, $error->getMessage());
            return false;
        }
    }

    public function getAllStatus()
    {
        try {

            $stmt = "SELECT status FROM {$this->tablename}";
        
            $prepared = $this->db->getDb()->prepare($stmt);

            $result = $prepared->execute();

            $rows = [];

            while($res = $result->fetchArray(SQLITE3_ASSOC)) {
                $rows[] = $res;
            }

            return $rows;
            
        } catch(Throwable $error) {
            syslog(LOG_ERR, $error->getMessage());
            return false;
        }
    }

    public function getAllGenero()
    {
        try {

            $stmt = "SELECT genero FROM {$this->tablename}";
        
            $prepared = $this->db->getDb()->prepare($stmt);

            $result = $prepared->execute();

            $rows = [];

            while($res = $result->fetchArray(SQLITE3_ASSOC)) {
                $rows[] = $res;
            }

            return $rows;
            
        } catch(Throwable $error) {
            syslog(LOG_ERR, $error->getMessage());
            return false;
        }
    }


    public function getAll()
    {
        try {

            $stmt = "SELECT nome, especie, status, genero FROM {$this->tablename}";
        
            $prepared = $this->db->getDb()->prepare($stmt);

            $result = $prepared->execute();

            $rows = [];

            while($res = $result->fetchArray(SQLITE3_ASSOC)) {
                $rows[] = $res;
            }

            return $rows;
            
        } catch(Throwable $error) {
            syslog(LOG_ERR, $error->getMessage());
            return false;
        }
    }

    public function getImage(string $nome) 
    {
        try {

            $stmt = "SELECT imagem FROM {$this->tablename} WHERE nome = :nome";
        
            $prepared = $this->db->getDb()->prepare($stmt);
            $prepared->bindValue(":nome", $nome);

            $result = $prepared->execute();

            $rows = [];

            while($res = $result->fetchArray(SQLITE3_ASSOC)) {
                $rows[] = $res;
            }

            return $rows;
            
        } catch(Throwable $error) {
            syslog(LOG_ERR, $error->getMessage());
            return false;
        }
    }

    public function filterEspecie(string $especie) 
    {

        try {

            $stmt = "SELECT * FROM {$this->tablename} WHERE especie LIKE :especie";
        
            $prepared = $this->db->getDb()->prepare($stmt);
            $prepared->bindValue(":especie", "%$especie%");

            $result = $prepared->execute();

            $rows = [];

            while($res = $result->fetchArray(SQLITE3_ASSOC)) {
                $rows[] = $res;
            }

            return $rows;
            
        } catch(Throwable $error) {
            syslog(LOG_ERR, $error->getMessage());
            return false;
        }

    }

}