<?php
    header("Content-Type: application/json");
    require_once "conexao.php";
    require_once "funcoes.php";
    require_once "validators/UsuarioValidator.php";
    require_once "controllers/UsuarioController.php";
    

    $controller = new UsuarioController($pdo);//Já passa o $pdo aqui, pra não precisar passar toda vez que chamar um método do Controller


    $metodo = $_SERVER["REQUEST_METHOD"];//Informa se a requisição é GET, POST, PUT, PATCH ou DELETE
    if($metodo === "GET"){
        //BUSCAR
        $id = obterIdOpcional();
        if($id != null){
            $usuario = $controller->buscar($id);
            if(!$usuario){
                responder([
                    "erro" => "Usuário não encontrado"
                ], 404);//código de usuário não encontrado
            }

            responder($usuario);
        }else{
            //COM CONTROLLER
            $usuarios = $controller->listar();//"Controller, execute o método listar"
            responder($usuarios);
        }
    }else if($metodo === "POST"){
        //CADASTRAR
        $dados = obterJson();

        $nome = $dados["nome"] ?? "";
        $email = $dados["email"] ?? "";

        validarUsuario($dados);

        $usuario = $controller->cadastrar($nome, $email);

        responder([
            "mensagem" => "Usuário cadastrado com sucesso",
            "usuario" => $usuario
        ], 201);

    }else if($metodo === "PATCH"){
        //ATUALIZAR/EDITAR
        $id = obterId();

        //Agora vou verificar se esse id existe no banco de dados pra poder editá-lo
        $usuario = $controller->buscar($id);

        if(!$usuario){//$usuario vai conter os dados atuais do banco
            responder([
                "erro" => "Usuário não encontrado"
            ], 404);
        }

        $dados = obterJson();

        $nome = $dados["nome"] ?? $usuario["nome"];//se o usuário não digitar os dados utiliza os dados que o banco já possui
        $email = $dados["email"] ?? $usuario["email"];

        //Validando se o nome ou o email foi enviado vazio
        //isso não é a mesma coisa que não enviar como em cima, é se a pessoa no JSON enviou sem nada das aspas duplas
        //nome: "", -> isso não pode
        //email: "jonathan@email.com"
        //Agora se for assim
        //email: "jonathan@email.com" -> agora pode, pq eu não enviei como vazio, só não coloquei nada, então é só usar o último registro do banco, já que no update na maioria das vezes eu não altero tudo, troco só uma coisa ou outra como email, ou endereço, mas nome e cpf por exemplo serão coisas fixas
        //É diferente do POST, pois na hora de criar um novo usuário nenhum dos campos pode ficar sem ser preenchio, já no PATCH, alguns campos podem sim ficar sem preenchimento, já que ás vezes eu só quero alterar uma coisa e pra não ter o trabalho de ficar repetindo o mesmo conteúdo que não precisa ser alterado eu faço essas validações

        validarAtualizacaoUsuario($dados);

        //Com controllers
        $usuario = $controller->atualizar($id, $nome, $email);

        responder([
            "mensagem" => "Usuário atualizado com sucesso",
            "usuario" => $usuario
        ], 200);
    }else if($metodo === "DELETE"){
        //EXCLUIR/APAGAR
        $id = obterId();

        //e depois verificar se ele existe no banco
        $usuario = $controller->buscar($id);

        if(!$usuario){
            responder([
                "erro" => "Usuário não encontrado"
            ], 404);
        }

        //Com controller
        $controller->excluir($id);
        responder([
            "mensagem" => "Usuário deletado com sucesso",
            "usuario" => $usuario
        ]);
    }else{
        responder([
            "erro" => "Método não permitido"
        ], 405);
    }
?>
