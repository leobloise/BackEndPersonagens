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

class IndexController implements ControllerInterface {

    private PersonagemDao $personagemDao;

    public function __construct(DatabaseInterface $db)
    {
        $this->personagemDao = new PersonagemDao($db, "personagens");
        $this->allowedFiles = [
            "image/jpeg",
            "image/png"
        ];
    }

    private function getHeaders(): array
    {
        return ["Access-Control-Allow-Origin" => "*"];
    }

    private function transformToMB(int $size)
    {
        return round($size / 1024 / 1024,4);
    }

    private function getImageSize($imagem)
    {
        return $this->transformToMB($imagem->getSize());
    }

    private function checkImage($imagem)
    {
        $kind = $imagem->getClientMediaType();

        if(!in_array($kind, $this->allowedFiles)) {
            return false;
        }

        if($this->getImageSize($imagem) >= 10) { 
            return false;
        }

        

        return true;
    }
    
    private function fabricateResponse(int $status, $headers = [], string $body)
    {

        $headers = array_merge($headers, $this->getHeaders());

        return new Response($status, $headers, $body);

    }

    public function processRequest(ServerRequestInterface $request): ResponseInterface
    {      
        $body = $request->getParsedBody();

        ["nome" => $name, "especie" => $especie, "status" => $status, "genero" => $genero] = $body;


        if(empty($especie)) {
            return $this->fabricateResponse(400, [], json_encode(["msg" => "Espécie é obrigatório o preenchimento"]));
        }

        if(empty($name)) {
            return $this->fabricateResponse(400, [], json_encode(["msg" => "Nome é obrigatório o preenchimento"]));
        }

        if(empty($genero)) {
            $genero = "Não possui";
        }

        if($especie == "Humanoid" && empty($status)) {
            return $this->fabricateResponse(400, [], json_encode(["msg" => "Status não pode ser vazio caso espécie seja Humanoid"]));
        }

        if(empty($status)) {
            $status = "Não possui";
        }

        $imagem = $request->getUploadedFiles()['imagem'];

        if(!$this->checkImage($imagem)) {
            return $this->fabricateResponse(400, [], json_encode(["msg" => "Imagem maior que 10 mb ou não é jpeg ou não é png"]));
        }

        $extension = pathinfo($imagem->getClientFilename())['extension'];

        $pathToImage = "./imgs/image_{$name}.{$extension}";


        $imagem->moveTo($pathToImage);

        try {
            
            $personagem = $this->personagemDao->getPersonagem($name);

            if($personagem) {
                $this->personagemDao->updatePersonagem($name, "especie", $especie);
                $this->personagemDao->updatePersonagem($name, "status", $status);
                $this->personagemDao->updatePersonagem($name, "genero", $genero);
                $this->personagemDao->updatePersonagem($name, "imagem", $pathToImage);
                return $this->fabricateResponse(200, $this->getHeaders(), json_encode(["msg" => "Personagem atualizado com sucesso"]));
            }

            $this->personagemDao->createPersonagem($name, $especie, $pathToImage, $status, $genero);
        
            return  $this->fabricateResponse(200, $this->getHeaders(), json_encode(["msg" => "Personagem criado com sucesso"]));
        
        } catch(Throwable $error) {

            return $this->fabricateResponse(400, [], json_encode(["msg" => $error->getMessage()]));

        }

    }

}