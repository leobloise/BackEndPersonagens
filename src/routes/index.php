<?php

require_once "vendor/autoload.php";

use leona\crud\controllers\BuscadorController;
use leona\crud\controllers\DeleteController;
use leona\crud\controllers\EspeciesController;
use leona\crud\controllers\ImageController;
use leona\crud\controllers\IndexController;
use leona\crud\controllers\ListController;
use leona\crud\controllers\FilterController;
use leona\crud\controllers\GeneroController;
use leona\crud\controllers\PopulateController;
use leona\crud\controllers\StatusController;

return [
    "/" => IndexController::class,
    "/list" => ListController::class,
    "/image" => ImageController::class,
    "/delete" => DeleteController::class,
    "/buscar" => BuscadorController::class,
    "/especies" => EspeciesController::class,
    "/filtrar" => FilterController::class,
    "/status" => StatusController::class,
    "/genero" => GeneroController::class,
    "/populate" => PopulateController::class
];