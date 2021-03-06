<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 12/06/2017
 * Arquivo responsavel por vincular um registro a uma entidade
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';

//variavel para mostrar o resultado final
$resultado = [];
$mensagem;

try {
    //parametros capturados via post
    $parametros = [
        'id' => $_POST['sala'],
        'predio' => $_POST['idPredio']
    ];
    //validação dos campos
    if (empty($parametros['id']) || empty($parametros['predio']))
    {
        $mensagem = "Os seguintes campos não podem vir vazios: ";
        if (empty($parametros['id'])) {
            $mensagem .= "1 - Sala  ";
        }
        if (empty($parametros['predio'])) {
            $mensagem .= "2 - Predio  ";
        }
        throw new Exception($mensagem);
    }

    //salvar
    $model = $entityManager->find('Sala', $parametros['id']);
    if(empty($model->getPredio())){
        $mensagem = "Registro vinculado com SUCESSO!";
        //busca o administrador o diretor geral
        $predio = $entityManager->find('Predio', $parametros['predio']);

        //seta o objeto diretamernte, o doctrine se encarregará de fazer o resto
        $model->setPredio($predio);

        $entityManager->persist($model);
        $entityManager->flush();

        $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => null];
    }
    else{
        $mensagem = "Sala já é vinculada a um prédio.";
        throw new Exception($mensagem);
    }
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}

echo json_encode($resultado);