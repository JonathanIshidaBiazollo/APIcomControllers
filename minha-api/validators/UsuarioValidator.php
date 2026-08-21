<?php
    function validarUsuario($dados){
        if(empty($dados["nome"]) || empty($dados["email"])){
            responder([
                "erro" => "Nome e email são obrigatórios"
            ], 400);
        }

        if(!filter_var($dados["email"], FILTER_VALIDATE_EMAIL)){//não basta colocar só o type email no html, pois o usuário pode mudar lá, tem que tratar aqui tbm
            responder([
                "erro" => "Email inválido"
            ], 400);
        }
    }

    function validarAtualizacaoUsuario($dados){
        if(!isset($dados["nome"]) && !isset($dados["email"])){
            responder([
                "erro" => "Informe pelo menos nome ou email"
            ], 400);
        }

        if(isset($dados["nome"]) && empty($dados["nome"])){
            responder([
                "erro" => "Nome não pode ser vazio"
            ], 400);
        }

        if(isset($dados["email"]) && empty($dados["email"])){
            responder([
                "erro" => "Email não pode ser vazio"
            ], 400);
        }

        if(isset($dados["email"]) && !filter_var($dados["email"], FILTER_VALIDATE_EMAIL)){
            responder([
                "erro" => "Email inválido"
            ], 400);
        }
    }

    function obterId(){
        //Primeiro verifico se o id não está vazio
        if(!isset($_GET["id"])){
            responder([
                "erro" => "ID do usuário é obrigatório"
            ], 400);
        }

        //Pra depois pegar o valor e atribuir a uma variável e poder usar 
        $id = $_GET["id"];
        if(!is_numeric($id)){
            responder([
                "erro" => "ID inválido"
            ], 400);
        }

        return $id;
    }

    function obterIdOpcional(){
        //Primeiro verifico se o id não está vazio
        if(!isset($_GET["id"])){
            return null;
        }

        //Pra depois pegar o valor e atribuir a uma variável e poder usar 
        if(!is_numeric($_GET["id"])){
            responder([
                "erro" => "ID inválido"
            ], 400);
        }

        return $_GET["id"];
    }
?>
