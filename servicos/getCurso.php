<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 21/09/2017
 * Arquivo responsavel pela listagem de todos os cursos para retornar para o APP
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';
include 'getHash.php';

$mensagem;
$data = [];

try {
    if(empty($_POST)){
        $mensagem = "HASH não informado";
        throw new Exception($mensagem);
    }
    else
    if(sha1($_POST["hash"]) != $hash) {
        $mensagem = "HASH incorreto!";
        throw new Exception($mensagem);
    }
    else {
        $qb = $entityManager->createQueryBuilder();
        $qb->select("c, u")
            ->from('Vwcurso', "c")
            ->leftJoin("c.unidade", "u")
            ->andWhere("c.id IS NOT NULL ");

        $rs = $qb->getQuery()->getResult();
        //contador de registros
        $qCount = clone $qb;
        $qCount->select("count(c.id)");
        $totalregistro = $qCount->getQuery()->getSingleScalarResult();

        if ($totalregistro > 0) {
            foreach ($rs as $idx => $model) {
                $data[$idx]["id"] = $model->getId();
                $data[$idx]["nome"] = $model->getNome();
                $data[$idx]["codcurso"] = $model->getCodcurso();
                $data[$idx]["idunidade"] = $model->getUnidade()->getId();
            }
            $mensagem =  $totalregistro." registros encontrados";
            $resultado = ['status' => true, 'mensagem' => $mensagem, 'data' => $data];
        } else {
            // records now found
            $mensagem = "Nenhum registro foi encontrado.";
            $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
        }
    }
} catch (Exception $ex) {
    $mensagem = "Atenção: ".$ex->getMessage();
    $resultado = ['status' => false, 'mensagem' => $mensagem, 'data' => null];
}
$retorno = json_encode($resultado);

echo $retorno;
?>