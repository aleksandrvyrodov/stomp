<?php

namespace ZX41\Sol\BX\DataLoadElement;

class BxQueryORM
{
  protected object $QueryORM;

  protected function _buildBasicORM()
  {
    $QueryORM = new \Bitrix\Main\Entity\Query(\Bitrix\Iblock\ElementTable::getEntity());

    $QueryORM
      ->registerRuntimeField(
        'ELEMENT_PROPERTY',
        [
          'data_type' => \Bitrix\Iblock\ElementPropertyTable::class,
          'reference' => ['=this.ID' => 'ref.IBLOCK_ELEMENT_ID',],
          'join_type' => 'LEFT',
        ]
      )
      ->registerRuntimeField(
        'PROPERTY',
        [
          'data_type' => \Bitrix\Iblock\PropertyTable::class,
          'reference' => ['=this.ELEMENT_PROPERTY.IBLOCK_PROPERTY_ID' => 'ref.ID'],
          'join_type' => 'LEFT',
        ]
      )
      ->registerRuntimeField(
        'PROPERTY_ENUM',
        [
          'data_type' => \Bitrix\Iblock\PropertyEnumerationTable::class,
          'reference' => [
            '=this.PROPERTY.ID' => 'ref.PROPERTY_ID',
            '=this.ELEMENT_PROPERTY.VALUE_ENUM' => 'ref.ID'
          ],
          'join_type' => 'LEFT',
        ]
      )
      #
    ;

    $QueryORM
      ->setOrder([
        'ID' => 'ASC',
        'PROPERTY.SORT' => 'ASC'
      ])
      #
    ;

    return $QueryORM;
  }

  public function __construct()
  {
    $this->QueryORM = $this->_buildBasicORM();
  }

  public function getQueryORM()
  {
    return $this->QueryORM;
  }
}
