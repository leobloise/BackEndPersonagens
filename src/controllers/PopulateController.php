<?php

namespace leona\crud\controllers;

openlog("myScriptLog", LOG_PID | LOG_PERROR, LOG_USER);

use leona\crud\config\DatabaseInterface;
use leona\crud\DAO\PersonagemDao;
use leona\crud\helpers\Sender;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Nyholm\Psr7\Response;
use Throwable;

require_once "vendor/autoload.php";

class PopulateController implements ControllerInterface {


    use Sender;

    private PersonagemDao $personagemDao;

    public function __construct(DatabaseInterface $db)
    {
        $this->personagemDao = new PersonagemDao($db, "personagens");
    }

    private function getHeaders(): array
    {
        return ["Access-Control-Allow-Origin" => "*"];
    }

    
    private function fabricateResponse(int $status, $headers = [], string $body)
    {

        $headers = array_merge($headers, $this->getHeaders());

        return new Response($status, $headers, $body);

    }

    public function processRequest(ServerRequestInterface $request): ResponseInterface
    {      
        $result = $this->send("https://rickandmortyapi.com/api/character");

        $resultDecoded = json_decode($result, 1);

        try {

            foreach($resultDecoded['results'] as $personagem) {

                ["name" => $nome, "status" => $status, "species" => $especie, "gender" => $genero, "image" => $imagem] = $personagem;
    
                $this->personagemDao->createPersonagem($nome, $especie, $imagem, $status, $genero);
    
            }
        
        } catch(Throwable $error) {

            syslog(LOG_EMERG, $error->getMessage());
            return  $this->fabricateResponse(400, [], "Erro ao Popular tabela");

        }

        return $this->fabricateResponse(200, [], "");

    }

}