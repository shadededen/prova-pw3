<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");


                $servidor = "localhost"; 
                $usuario = "root"; 
                $senha = ""; 
                $banco = "prova_fer";

                $conexao = new mysqli($servidor, $usuario, $senha, $banco);

                if ($conexao->connect_error) {
                    http_response_code(500);
                    echo json_encode(["erro" => "Falha na conexão: " . $conexao->connect_error]);
                    exit;
                }

                $methof = $_SERVER['REQUEST_METHOD'];
                switch($method){
                    case 'GET':
                        if(issey($_GET[pesquisa])){
                            $pesquisa = "%".$_GET['pesquisa']."%";
                            $stmt= $conexao->prepare("SELECT * FROM roupas WHERE categoria LIKE ?");
                            $stmt->bind_param("s",$pesquisa);
                            $stmt->execute();
                            $result = $stmt->get_result();
                        }else{
                             $result = $conexao->query("SELECT * FROM roupas order by Id desc");
                        }
                        $retorno=[];
                        while($linha=$result->fetch_assoc()){
                            $retorno[] = $linha;
                        }
                        echo json_encode($retorno);
                        break;

                        case 'POST':
                            $data=json_decode(file_get_contents("php://input"), true);
                            $stmt = $conexao->prepare("INSERT INTO roupas (categoria, preco, disponivel) VALUES (?, ?, ?)");
                            $stmt->bind_param("ssi", $data['categoria'], $data['preco'], $data['disponivel']);

                            $stmt->execute();

                            echo json_encode(["status" => "ok", "insert_id"=> $stmt->insert_id]);
                            break;

                        case 'PUT':
                            $data = json_decode(file_get_contents("php://input"), true);
                            $stmt = $conexao->prepare("UPDATE roupas SET categoria=?, preco=?, disponivel=? WHERE Id=?");
                            $stmt->bind_param("ssi", $data['categoria'], $data['preco'], $data['disponivel']);
                            $stmt->execute()
                            echo json_encode(["status" => "ok" ]);
                            break;

                        case 'DELETE'
                        $Id =$_GET['Id'];
                        $stmt = $conexao->prepare("DELETE FROM roupas WHERE Id=?");
                        $stmt->bind_param("i",$id);
                        $stmt->execute();
                        echo json_encode(["status" => "ok", "insert_id"=> $stmt->insert_id]);
                        break;
                }
                $conexao->close();
                            

                /* }        
               $stmt = $conexao->prepare("INSERT INTO roupas (categoria, preco, disponivel) VALUES (?, ?, ?)");
                $stmt->bind_param("ss", $nome_materia, $disponivel);

                if ($stmt->execute()) {
                    
                } else {
                    http_response_code(500);
                    
                }

                $stmt->close();
                $conexao->close();
                echo json_encode($dados_recebidos);
            }else{
                echo json_encode("{'erro':'dados inválidos'}");
            }
        break;
case "GET":
         
        $servidor = "localhost"; 
        $usuario = "root"; 
        $senha = ""; 
        $banco = "aula_pw3";

        $conexao = new mysqli($servidor, $usuario, $senha, $banco);

        $sql = "Select * from Materias";

        $resultado = $conexao->query($sql);

        $materias = [];
        while ($linha = $resultado->fetch_assoc()) {
            $materias[] = $linha;
        }
        echo json_encode($materias);
        break;    
}


?>