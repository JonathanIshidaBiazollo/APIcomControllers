<?php
    class UsuarioRepository{
        private $pdo;

        public function __construct($pdo){
            $this->pdo = $pdo;
        }

        public function listar(){
            $sql = "SELECT * 
                    FROM usuarios
            ";

            $stmt = $this->pdo->query($sql);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }

        public function buscar($id){
            $sql = "SELECT *
                    FROM usuarios
                    WHERE id = ?
            ";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
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

            return([
                "id" => $id,
                "nome" => $nome,
                "email" => $email
            ]);
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

            
        }
    }
?>