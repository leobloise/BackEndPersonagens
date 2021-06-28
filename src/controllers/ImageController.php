<?php


namespace leona\crud\controllers;

openlog("myScriptLog", LOG_PID | LOG_PERROR, LOG_USER);

use leona\crud\config\DatabaseInterface;
use leona\crud\DAO\PersonagemDao;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Nyholm\Psr7\Response;
use Throwable;

require_once "vendor/autoload.php";

class ImageController implements ControllerInterface {

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
        $body = $request->getParsedBody();

        ["imagetosearch" => $name] = $body;

        
        try {
            
            $imagem = $this->personagemDao->getImage($name);
        
            $pathToImage = $imagem[0];

            return new Response(200, $this->getHeaders(), fopen($pathToImage['imagem'], "r"));
        
        } catch(Throwable $error) {

            return $this->fabricateResponse(400, [], json_encode(["msg" => $error->getMessage()]));

        }

    }

}