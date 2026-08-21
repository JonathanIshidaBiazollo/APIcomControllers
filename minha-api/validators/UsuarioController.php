<?php
    class UsuarioController{
        private $pdo;

        public function __construct($pdo){//Vamos criar o construtor pra que a conexão não seja necessária de ser passada a todo momento no usuarios.php, assim vc omite essa parte
            $this->pdo = $pdo;
        }

        public function listar(){
            try {
                $sql = "SELECT *
                        FROM usuarios
                ";

                $stmt = $this->pdo->query($sql);

                $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $usuarios;
            } catch (PDOException $erro) {
                erroInterno();
            }

        }

        public function buscar($id){
            try{
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
            }catch(PDOException $erro){
                erroInterno();
            }

        }

        public function cadastrar($nome, $email){
            try{
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
            }catch(PDOException $erro){
                erroInterno();
            }
        }

        public function atualizar($id, $nome, $email){
            try {
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
            } catch (PDOException $erro) {
                erroInterno();
            }

        }

        public function excluir($id){
            try {
                $sql = "DELETE
                        FROM usuarios
                        WHERE id = ?
                ";

                $stmt = $this->pdo->prepare($sql);

                $stmt->execute([
                    $id
                ]);

                /*
                return[
                    "id" => $id
                ];
                */
            } catch (PDOException $erro) {
                erroInterno();
            }
        }
    }
?>
