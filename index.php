<?php
// ... (código anterior permanece igual)

require_once "EstudanteGraduacao.php";

// Determine se é uma requisição para estudante de graduação
$isGraduacao = isset($_GET['tipo']) && $_GET['tipo'] === 'graduacao';

// Instancia o objeto apropriado
if ($isGraduacao) {
    $estudante = new EstudanteGraduacao($db);
} else {
    $estudante = new Estudante($db);
}

// ... (switch case anterior)

// Modifique o case 'POST' para incluir o tratamento de estudante de graduação
case 'POST':
    $data = json_decode(file_get_contents("php://input"));
    if ($isGraduacao) {
        if(!empty($data->nome) && !empty($data->idade) && !empty($data->curso)) {
            $estudante->nome = $data->nome;
            $estudante->idade = $data->idade;
            $estudante->curso = $data->curso;

            if($estudante->create()){
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
        // ... (código original para estudante regular)
    }
    break;

// Modifique o case 'PUT' de forma similar
case 'PUT':
    if(isset($_GET['id'])) {
        $data = json_decode(file_get_contents("php://input"));
        if ($isGraduacao) {
            if(!empty($data->nome) && !empty($data->idade) && !empty($data->curso)) {
                $estudante->id = intval($_GET['id']);
                $estudante->nome = $data->nome;
                $estudante->idade = $data->idade;
                $estudante->curso = $data->curso;

                if($estudante->update()){
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
            // ... (código original para estudante regular)
        }
    }
    break;

// ... (resto do código permanece igual)
?>