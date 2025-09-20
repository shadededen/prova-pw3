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

    $method = $_SERVER['REQUEST_METHOD'];
    switch($method){
        case 'GET':
            if(isset($_GET['pesquisa'])){
                $pesquisa = "%". $_GET['pesquisa']."%";
                $stmt= $conexao->prepare("SELECT * FROM roupas WHERE categoria LIKE ?");
                $stmt->bind_param("s",$pesquisa);
                $stmt->execute();
                $result = $stmt->get_result();
            }else{
                    $result = $conexao->query("SELECT * FROM roupas order by Id desc");
            }
            $retorno = [];

            while($linha=$result->fetch_assoc()){
                $retorno[] = $linha;
            }
            echo json_encode($retorno);
            break;

        case 'POST':
                $data=json_decode(file_get_contents("php://input"), true);
                $stmt = $conexao->prepare("INSERT INTO roupas (categoria, preco, disponivel) VALUES (?, ?, ?)");
                $stmt->bind_param("ssii", $data['categoria'], $data['preco'], $data['disponivel']);

                $stmt->execute();

                echo json_encode(["status" => "ok", "insert_id"=> $stmt->insert_id]);
                break;

        case 'PUT':
                $data = json_decode(file_get_contents("php://input"), true);
                $stmt = $conexao->prepare("UPDATE roupas SET categoria=?, preco=?, disponivel=? WHERE Id=?");
                $stmt->bind_param("ssii", $data['categoria'], $data['preco'], $data['disponivel'], $data['Id']);
                $stmt->execute();
                echo json_encode(["status" => "ok" ]);
                break;

        case 'DELETE':
            $id =$_GET['id'];
            $stmt = $conexao->prepare("DELETE FROM roupas WHERE Id=?");
            $stmt->bind_param("i",$id);
            $stmt->execute();
            echo json_encode(["status" => "ok"]);
            break;
}
$conexao->close();
