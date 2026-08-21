<?php
    function responder($dados, $status = 200){//se eu não passar nada fica por padrão o código 200 que significa que o status está OK
            
        http_response_code($status);

        echo json_encode($dados);

        exit;
    }

    function erroInterno(){
        responder([
            "erro" => "Erro interno do servidor"
        ], 500);
    }

    function obterJson(){
        $dados = json_decode(
            file_get_contents("php://input"),
            true
        );

        if($dados === null){
            responder([
                "erro" => "JSON inválido"
            ]);
        }

        return $dados;
    }
?>
