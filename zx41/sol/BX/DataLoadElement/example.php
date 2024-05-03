<?php

use ZX41\Sol\BX\DataLoadElement\BxQueryORM;
use ZX41\Sol\BX\DataLoadElement\ElementIBlockHSS;


# Load all data
if (0) {
  $HSS = new ElementIBlockHSS();
  $HSS
    ->filter(['IBLOCK_ID' => 1])
    ->filter(['ID' => [1, 2]])
    ->exec()
    #
  ;

  while ($ElData = $HSS->fetch()) {
    echo var_export($ElData, true) . PHP_EOL;
  }
}


# Load distinct prop value
if (0) {
  $BQO = new BxQueryORM();
  $QueryORM = $BQO->getQueryORM();

  $QueryORM
    ->setSelect([
      'MYPROP_VALUE'
    ])
    ->setFilter([
      'IBLOCK_ID' => 1,
      'PROPERTY.CODE' => 'MYPROP',
    ])
    ->registerRuntimeField(
      'MYPROP_VALUE',
      [
        'data_type' => 'integer',
        'expression' => ['DISTINCT %s', 'ELEMENT_PROPERTY.VALUE']
      ]
    )
    #
  ;

  $ResultORM = $QueryORM->exec();

  while ($row = $ResultORM->fetch())
    $values[] = $row['MYPROP_VALUE'];

  echo var_export($values, true) . PHP_EOL;
}
