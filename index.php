<?php
// Autoload
$loader = require 'vendor/autoload.php';

// Instanciando Objeto
$app = new \Slim\Slim(array(
    'templates.path' => 'templates'
));

// Listando todas
$app->get('/pessoas/', function() use ($app){
    (new \controllers\Pessoa($app))->lista();
});

// Get Pessoa
$app->get('/pessoa/:id', function($id) use ($app){
    (new \controllers\Pessoa($app))->get($id);
echo "tes";
});


// Nova Pessoa
$app->post('/pessoas/', function() use ($app){
    (new \controllers\Pessoa($app))->nova();
});

// Editando Pessoa
$app->put('/pessoas/:id', function($id) use ($app){
    (new \controllers\Pessoa($app))->editar($id);
});

// Apagar Pessoa
$app->delete('/pessoas/:id', function ($id) use ($app){
    (new \controllers\Pessoa($app))->excluir($id);
});

$app->run();

?>