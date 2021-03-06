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
        'id' => $_POST['curso'],
        'unidade' => $_POST['idUnidade']
    ];
    //validação dos campos
    if (empty($parametros['id']) || empty($parametros['unidade']))
    {
        $mensagem = "Os seguintes campos não podem vir vazios: ";
        if (empty($parametros['id'])) {
            $mensagem .= "1 - Curso  ";
        }
        if (empty($parametros['unidade'])) {
            $mensagem .= "2 - Unidade  ";
        }
        throw new Exception($mensagem);
    }

    //salvar
    $model = $entityManager->find('Vwcurso', $parametros['id']);
    if(empty($model->getUnidade())){
        $mensagem = "Registro vinculado com SUCESSO!";
        //busca o administrador o diretor geral
        $unidade = $entityManager->find('Unidade', $parametros['unidade']);

        //seta o objeto diretamernte, o doctrine se encarregará de fazer o resto
        $model->setUnidade($unidade);

        $entityManager->persist($model);
        $entityManager->flush();

        $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => null];
    }
    else{
        $mensagem = "Curso já é vinculado a uma unidade.";
        throw new Exception($mensagem);
    }
} catch (Exception $ex) {
    $mensagem = "Atenção: " . $ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}

echo json_encode($resultado);