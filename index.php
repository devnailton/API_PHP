<?php
// Configuração do cabeçalho
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Inclui as classes necessárias
include_once 'Database.php';
include_once 'Estudante.php';
include_once 'EstudanteGraduacao.php';

// Conexão com o banco de dados
$database = new Database();
$db = $database->getConnection();

// Determina se é uma requisição para estudante de graduação
$isGraduacao = isset($_GET['tipo']) && $_GET['tipo'] === 'graduacao';

// Instancia o objeto apropriado
if ($isGraduacao) {
    $estudante = new EstudanteGraduacao($db);
} else {
    $estudante = new Estudante($db);
}

// Verifica o método HTTP
$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $estudante->id = intval($_GET['id']);
            $result = $estudante->readOne();
            echo json_encode($result);
        } else {
            $result = $estudante->readAll();
            echo json_encode($result);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if ($isGraduacao) {
            if (!empty($data->nome) && !empty($data->idade) && !empty($data->curso)) {
                $estudante->nome = $data->nome;
                $estudante->idade = $data->idade;
                $estudante->curso = $data->curso;

                if ($estudante->create()) {
                    http_response_code(201);
                    echo json_encode(["message" => "Estudante de graduação cadastrado com sucesso."]);
                } else {
                    http_response_code(503);
                    echo json_encode(["message" => "Não foi possível cadastrar o estudante de graduação."]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Dados incompletos para cadastro."]);
            }
        } else {
            if (!empty($data->nome) && !empty($data->idade)) {
                $estudante->nome = $data->nome;
                $estudante->idade = $data->idade;

                if ($estudante->create()) {
                    http_response_code(201);
                    echo json_encode(["message" => "Estudante cadastrado com sucesso."]);
                } else {
                    http_response_code(503);
                    echo json_encode(["message" => "Não foi possível cadastrar o estudante."]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Dados incompletos para cadastro."]);
            }
        }
        break;

    case 'PUT':
        if (isset($_GET['id'])) {
            $data = json_decode(file_get_contents("php://input"));
            if ($isGraduacao) {
                if (!empty($data->nome) && !empty($data->idade) && !empty($data->curso)) {
                    $estudante->id = intval($_GET['id']);
                    $estudante->nome = $data->nome;
                    $estudante->idade = $data->idade;
                    $estudante->curso = $data->curso;

                    if ($estudante->update()) {
                        echo json_encode(["message" => "Estudante de graduação atualizado com sucesso."]);
                    } else {
                        http_response_code(503);
                        echo json_encode(["message" => "Não foi possível atualizar o estudante de graduação."]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["message" => "Dados incompletos para atualização."]);
                }
            } else {
                if (!empty($data->nome) && !empty($data->idade)) {
                    $estudante->id = intval($_GET['id']);
                    $estudante->nome = $data->nome;
                    $estudante->idade = $data->idade;

                    if ($estudante->update()) {
                        echo json_encode(["message" => "Estudante atualizado com sucesso."]);
                    } else {
                        http_response_code(503);
                        echo json_encode(["message" => "Não foi possível atualizar o estudante."]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["message" => "Dados incompletos para atualização."]);
                }
            }
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $estudante->id = intval($_GET['id']);
            if ($estudante->delete()) {
                echo json_encode(["message" => "Estudante deletado com sucesso."]);
            } else {
                http_response_code(503);
                echo json_encode(["message" => "Não foi possível deletar o estudante."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID do estudante não fornecido."]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Método não permitido."]);
        break;
}
?>