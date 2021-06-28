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

class FilterController implements ControllerInterface {

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

    private function mergeFilteredResults(array &$result, array $tobemerged): void
    {

        foreach($tobemerged as $value) {

            $stringValue = json_encode($value);

            $present = false;

            foreach($result as $resultItem) {
                
                $resultItemString = json_encode($resultItem);

                if($resultItemString === $stringValue)
                    $present = true;

            }

            if($present) {
                continue;
            } else {
                $result[] = $value;
            }

        }

    }

    public function processRequest(ServerRequestInterface $request): ResponseInterface
    {     
        $body = $request->getParsedBody();

        ["especie" => $especie, "status" => $status, "genero" => $genero] = $body;

        $results = [];

        try {

            if(!is_null($especie)) {
                $allWithThisSpecie = $this->personagemDao->filterEspecie($especie);
                $this->mergeFilteredResults($results, $allWithThisSpecie);
            }

            if(!is_null($status)) {
                $allWithThisStatus = $this->personagemDao->filterStatus($status);
                $this->mergeFilteredResults($results, $allWithThisStatus);
            }

            if(!is_null($genero)) {
                $allWithThisGenero = $this->personagemDao->filterGenero($genero);
                $this->mergeFilteredResults($results, $allWithThisGenero);
            }

            if($results !== []) {
                return $this->fabricateResponse(200, [], json_encode($results));
            }

            $especies = $this->personagemDao->getAllEspecies();
        
            return  $this->fabricateResponse(200, [], json_encode(["msg" => $especies]));
        
        } catch(Throwable $error) {

            return $this->fabricateResponse(400, [], json_encode(["msg" => $error->getMessage()]));

        }

    }

}