<?php
    //Um controller é basicamente um lugar para colocar a lógica responsável por determinada entidade.
    //O Controller recebe uma determinada ação e coordena o que precisa acontecer.
    /*
        Por exemplo:

        public function buscar($pdo, $id)
        {
            ...
        }

        Ele sabe:

        "Preciso buscar um usuário."

        E conversa com o banco para fazer isso.
    */
    //Controller não precisa necessariamente ser o responsável por tudo que envolve uma entidade.
    /*
        usuarios.php
        │
        ├── GET
        ├── POST
        ├── PATCH
        └── DELETE

        A ideia agora é começar a transformar isso em:

        UsuarioController
        │
        ├── listar()
        ├── buscar()
        ├── cadastrar()
        ├── atualizar()
        └── excluir()
    */
    class UsuarioController{

        /*
        Então o Controller passa a carregar sua própria conexão.

        Por isso, quando você posteriormente faz:

        $controller->buscar($id);

        ele já sabe qual banco deve utilizar.

        É justamente a ideia de:

        "Eu entrego ao objeto aquilo que ele precisa para trabalhar."

        Isso é injeção de dependência.
        */
        private $pdo;

        public function __construct($pdo){//Vamos criar o construtor pra que a conexão não seja necessária de ser passada a todo momento no usuarios.php, assim vc omite essa parte
            $this->pdo = $pdo;
        }

        public function listar(){
            $sql = "SELECT *
                    FROM usuarios
            ";

            $stmt = $this->pdo->query($sql);

            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $usuarios;
        }

        public function buscar($id){
            $sql = "SELECT *
                    FROM usuarios
                    WHERE id = ?
            ";

            $stmt = $this->pdo->prepare($sql);
            //query() → quando a SQL já está pronta:
            //prepare() + execute() → quando você precisa colocar valores na SQL:

            $stmt->execute([$id]);

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            return $usuario;
        }

        public function cadastrar($nome, $email){
            $sql = "INSERT INTO usuarios(
                        nome,
                        email
                    )
                    VALUES(
                        ?,
                        ?
                    )
            ";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([
                $nome,
                $email
            ]);

            return [
                "id" => $this->pdo->lastInsertId(),
                "nome" => $nome,
                "email" => $email
            ];
        }

        public function atualizar($id, $nome, $email){
            $sql = "UPDATE usuarios
                    SET
                        nome = ?,
                        email = ?
                    WHERE id = ?
            ";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([
                $nome,
                $email,
                $id
            ]);

            return[
                "id" => $id,
                "nome" => $nome,
                "email" => $email
            ];
        }

        public function excluir($id){
            $sql = "DELETE
                    FROM usuarios
                    WHERE id = ?
            ";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([
                $id
            ]);

            return[
                "id" => $id
            ];
        }
    }
?>