<?php

/**
 * Criado por: Sávio Martins Valentim
 * Data: 01/06/2017
 * Arquivo responsavel por carregar a combobox
 */

define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once BASE_DIR . 'vendor/bootstrap.php';

// Design initial table header
$data = "";
$livre = $_POST['livre'];

//cria a query builder
$qb = $entityManager->createQueryBuilder();
if(empty($livre)){
    $qb->select("p")
        ->from('Predio', "p")
        ->andWhere("p.id IS NOT NULL ");
}
else{
    $qb->select("p")
        ->from('Predio', "p")
        ->where("p.unidade IS NULL")
        ->andWhere("p.id IS NOT NULL ");
}
$rs = $qb->getQuery()->getResult();
//contador de registros
$qCount = clone $qb;
$qCount->select("count(p.id)");
$totalregistro = $qCount->getQuery()->getSingleScalarResult();

// if query results contains rows then featch those rows
if ($totalregistro > 0) {
    foreach ($rs as $idx => $model) {
        $data .= '
        <option value="' . $model->getId() . '">' . $model->getNome() . '</option>
        ';
    }
} else {
    // records now found
    $data = "";
}

echo $data;

?>