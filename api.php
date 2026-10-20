<?php
//Configurações Iniciais
header("Content-Type: application/json; charset=utf-8"); //Define a resposta da API, neste caso em JSON
header("Access-Control-Allow-Origin: *"); //Permite o acesso à API de todas as origens
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE"); //Perimite os métodos

require "config.php";

$rota = $_GET["rota"] ?? "";
$metodo = $_SERVER["REQUEST_METHOD"];

$corpo = json_decode(file_get_contents("php://input"), true) ?? []; //Necessário para utilizarmos o PUT

function teste(){
    echo json_encode(["mensagem" => "Back-end da transportadora respondendo!"]);
    exit;
}

//CRUD - Cliente
//CREATE
function createCliente($con, $dados){
    $stmt = $con->prepare("INSERT INTO Cliente (nome, endereco, telefone, email) VALUES (?, ?, ?, ?)");
    $stmt->execute([$dados["nome"], $dados["endereco"], $dados["telefone"], $dados["email"]]);
    http_response_code(201);
    echo json_encode(["mensagem" => "Cliente criado com sucesso!", "id" => $con->lastInsertId()]);
}
//READ
function readCliente($con){
    $stmt = $con->query("SELECT * FROM Cliente");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
//UPDATE
function updateCliente($con, $id, $dados){
    $stmt = $con->prepare("UPDATE Cliente SET nome = ?, endereco = ?, telefone = ?, email = ? WHERE idCliente = ?");
    $stmt->execute([$dados["nome"], $dados["endereco"], $dados["telefone"], $dados["email"], $id]);
    echo json_encode(["mensagem" => "Cliente atualizado com sucesso!"]);
}
//DELETE
function deleteCliente($con, $id){
    $stmt = $con->prepare("DELETE FROM Cliente WHERE idCliente = ?");
    $stmt->execute([$id]);
    echo json_encode(["mensagem" => "Cliente removido com sucesso!"]);
}

//CRUD -Funcionário
//CREATE
function createFuncionario($con, $dados){
    $stmt = $con->prepare("INSERT INTO Funcionario (nome, cargo, salario) VALUES (?, ?, ?)");
    $stmt->execute([$dados["nome"], $dados["cargo"], $dados["salario"]]);
    http_response_code(201);
    echo json_encode(["mensagem" => "Funcionário criado com sucesso!", "id" => $con->lastInsertId()]);
}
//READ
function readFuncionario($con){
    $stmt = $con->query("SELECT * FROM Funcionario");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
//UPDATE
function updateFuncionario($con, $id, $dados){
    $stmt = $con->prepare("UPDATE Funcionario SET nome = ?, cargo = ?, salario = ? WHERE idFuncionario = ?");
    $stmt->execute([$dados["nome"], $dados["cargo"], $dados["salario"], $id]);
    echo json_encode(["mensagem" => "Funcionário atualizado com sucesso!"]);
}
//DELETE
function deleteFuncionario($con, $id){
    $stmt = $con->prepare("DELETE FROM Funcionario WHERE idFuncionario = ?");
    $stmt->execute([$id]);
    echo json_encode(["mensagem" => "Funcionário removido com sucesso!"]);
}

//CRUD - Rota
//CREATE
function createRota($con, $dados){
    $stmt = $con->prepare("INSERT INTO Rota (origem, destino, distancia) VALUES (?, ?, ?)");
    $stmt->execute([$dados["origem"], $dados["destino"], $dados["distancia"]]);
    http_response_code(201);
    echo json_encode(["mensagem" => "Rota criada com sucesso!", "id" => $con->lastInsertId()]);
}
//READ
function readRota($con){
    $stmt = $con->query("SELECT * FROM Rota");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
//UPDATE
function updateRota($con, $id, $dados){
    $stmt = $con->prepare("UPDATE Rota SET origem = ?, destino = ?, distancia = ? WHERE idRota = ?");
    $stmt->execute([$dados["origem"], $dados["destino"], $dados["distancia"], $id]);
    echo json_encode(["mensagem" => "Rota atualizada com sucesso!"]);
}
//DELETE
function deleteRota($con, $id){
    $stmt = $con->prepare("DELETE FROM Rota WHERE idRota = ?");
    $stmt->execute([$id]);
    echo json_encode(["mensagem" => "Rota removida com sucesso!"]);
}
//ROTAS
if ($rota == "cliente"){
    switch($metodo){
        case "GET":
            readCliente($con);
            break;
        case "POST":
            createCliente($con, $corpo);
            break;
        case "PUT":
            updateCliente($con, $_GET["id"], $corpo);
            break;
        case "DELETE":
            deleteCliente($con, $_GET["id"]);
            break;
        default:
            teste();
    }
}elseif ($rota == "funcionario"){
    switch($metodo){
        case "POST":
            createFuncionario($con, $corpo);
            break;
        case "GET":
            readFuncionario($con);
            break;
        case "PUT":
            updateFuncionario($con, $_GET["id"], $corpo);
            break;
        case "DELETE":
            deleteFuncionario($con, $_GET["id"]);
            break;
        default:
            teste();
    }
}elseif ($rota == "rota"){
    switch($metodo){
        case "POST":
            createRota($con, $corpo);
            break;
        case "GET":
            readRota($con);
            break;
        case "PUT":
            updateRota($con, $_GET["id"], $corpo);
            break;
        case "DELETE":
            deleteRota($con, $_GET["id"]);
            break;
        default:
            teste();
    }
}