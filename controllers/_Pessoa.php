<?php

namespace controllers{
    // Classe Pessoa
    class Pessoa{
        //Atributo para banco de dados
        private $PDO;
        // Conectando ao banco de dados
        function __construct(){
            $this->PDO = new \PDO('mysql:host=localhost;dbname=api', 'root','');
            // Habilitando erros do PDO
            $this->PDO->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
        }

        // Método Listar
        public function lista(){
            global $app;
            $sth = $this->PDO->prepare("SELECT * FROM pessoa");
            $sth->execute();
            $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
            $app->render('default.php',["data" => $result],200);
        }

        // Método GET - PARAM $id - PEGA PESSOA PELO ID
        public function get($id){
            global $app;
            $sth = $this->PDO->prepare("SELECT * FROM pessoa WHERE id = :id");
            $sth->bindValue(':id',$id);
            $sth->execute();
            $result = $sth->fetch(\PDO::FETCH_ASSOC);
            $app->render('default.php',["data" => $result],200);
        }

        // Método Cadastrar Pessoa
        public function nova(){
            global $app;
            $dados = json_decode($app->request->getBody(), true);
            $dados = (sizeof($dados) == 0)? $_POST : $dados;
            $keys = array_keys($dados); // Pega as chaves do array

            // O uso de 'Prepare' e 'bindValue' é importante para se EVITAR SQL INJECTION
            $sth = $this->PDO->prepare("INSERT INTO pessoa (".implode(',', $keys).") VALUES (:".implode(",:", $keys).")");
            foreach ($dados as $key => $value){
                $sth->bindValue(':'.$key,$value);
            }

            $sth->execute();
            $app->render('default.php',["data" => ['id' => $this->PDO->lastInsertId()]],200);
        }

        // MÉTODO EDITANDO PESSOA - 'EDITAR' - 'PARAM $id'
        public function editar($id){
            global $app;
            $dados = json_decode($app->request->getBody(), true);
            $dados = (sizeof($dados) == 0)? $_POST : $dados;
            $sets = [];
                foreach ($dados as $key => $VALUES){
                    $sets[] = $key." = :".$key;
                }

            $sth = $this->PDO->prepare("UPDATE pessoa SET ".implode(',', $sets)." WHERE id = :id");
            $sth->bindValue(':id',$id);
                foreach ($dados as $key => $value){
                    $sth->bindValue(':'.$key,$value);
                }
                // Retorna status da edição 
                $app->render('default.php',["data" => ['status' => $sth->execute() == 1]],200);
        }

        // Método EXCLUIR - PARAM $ID 
        public function excluir($id){
            global $app;
            $sth = $this->PDO->prepare("DELETE FROM pessoa WHERE id = :id");
            $sth->bindValue(':id',$id);
            $app->render('default.php',["data" => ['status' => $sth->execute() == 1]],200);
        }

    }
}