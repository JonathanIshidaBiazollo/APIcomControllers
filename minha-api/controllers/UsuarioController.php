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
require_once __DIR__ . "/../repositories/UsuarioRepository.php";
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
        private $repository;

        public function __construct($pdo){//Vamos criar o construtor pra que a conexão não seja necessária de ser passada a todo momento no usuarios.php, assim vc omite essa parte
            $this->pdo = $pdo;
            $this->repository = new UsuarioRepository($pdo);
        }

        public function listar(){
            try {
                /*
                $sql = "SELECT *
                        FROM usuarios
                ";

                $stmt = $this->pdo->query($sql);

                $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $usuarios;
                */
                //Agora toda a lógica de cima é feita pelo repositório
                return $this->repository->listar();
            } catch (PDOException $erro) {
                erroInterno();
            }

        }

        public function buscar($id){
            try{
                /*
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
                */
                return $this->repository->buscar($id);
            }catch(PDOException $erro){
                erroInterno();
            }

        }

        public function cadastrar($nome, $email){
            try{
                return $this->repository->cadastrar($nome, $email);
            }catch(PDOException $erro){
                erroInterno();
            }
        }

        public function atualizar($id, $nome, $email){
            try {
                return $this->repository->atualizar($id, $nome, $email);
            } catch (PDOException $erro) {
                erroInterno();
            }

        }

        public function excluir($id){
            try {
                return $this->repository->excluir($id);
            } catch (PDOException $erro) {
                erroInterno();
            }
        }
    }
?>
