<?php

namespace ZX41\Sol\BX\DataLoadElement;

class ElementIBlockHSS extends BxQueryORM
{
  protected array $element_fields = [
    'ID',
    'TIMESTAMP_X',
    'MODIFIED_BY',
    'DATE_CREATE',
    'CREATED_BY',
    'IBLOCK_ID',
    'IBLOCK_SECTION_ID',
    'ACTIVE',
    'ACTIVE_FROM',
    'ACTIVE_TO',
    'SORT',
    'NAME',
    'PREVIEW_PICTURE',
    'PREVIEW_TEXT',
    'PREVIEW_TEXT_TYPE',
    'DETAIL_PICTURE',
    'DETAIL_TEXT',
    'DETAIL_TEXT_TYPE',
    'SEARCHABLE_CONTENT',
    'IN_SECTIONS',
    'XML_ID',
    'CODE',
    'TAGS',
  ];
  protected array $element_property_fields = [
    'VALUE',
    'DESCRIPTION',
    'VALUE_TYPE',
  ];
  protected array $property_fields = [
    'ID',
    'NAME',
    'CODE',
    'ACTIVE',
    'PROPERTY_TYPE',
    'MULTIPLE',
  ];
  protected array $property_enum_fields = [
    'VALUE',
    'DEF',
  ];

  protected ?object $ResultORM = null;

  protected static array $list_ELement = [];

  public static function Load(): void
  {
  }

  public function __construct()
  {
    parent::__construct();
    $this->_setForBasicORM();
  }

  private function _setForBasicORM(): void
  {
    $this->QueryORM
      ->setSelect([
        ...$this->element_fields,
        ...array_map(fn ($_) => "ELEMENT_PROPERTY.$_", $this->element_property_fields),
        ...array_map(fn ($_) => "PROPERTY.$_", $this->property_fields),
        ...array_map(fn ($_) => "PROPERTY_ENUM.$_", $this->property_enum_fields),
      ])
      #
    ;

    $this->QueryORM
      ->setOrder([
        'ID' => 'ASC',
        'PROPERTY.SORT' => 'ASC'
      ])
      #
    ;
  }

  public function exec()
  {
    $this->ResultORM = $this->QueryORM->exec();

    return $this;
  }

  public function filter(array $filter)
  {
    foreach ($filter as $field => $value)
      $this->QueryORM
        ->addFilter($field, $value);

    return $this;
  }

  public function fetch()
  {
    static $await__chunk_ELement = null;

    if (!$this->ResultORM)
      return null;

    do {
      $row = $this->ResultORM->fetch();

      if ($row)
        if ($await__chunk_ELement === null)
          $await__chunk_ELement = $this->_fillData($row);
        elseif (($ID = $await__chunk_ELement->ID) == ($chunk_ELement = $this->_fillData($row))->ID)
          self::$list_ELement[$ID] = $await__chunk_ELement;
        else {
          $await__chunk_ELement = $chunk_ELement;
          return self::$list_ELement[$ID];
        }
      elseif ($await__chunk_ELement && ($ID = $await__chunk_ELement->ID)) {
        self::$list_ELement[$ID] = $await__chunk_ELement;
        $await__chunk_ELement = null;
        return self::$list_ELement[$ID];
      } else
        return null;
    } while ($row);
  }

  public function fetchAll()
  {
    while ($this->fetch());
    return self::$list_ELement;
  }

  private function _fillData(array $row): \stdClass
  {
    $Row = (object) $row;

    if (!(self::$list_ELement[$Row->ID] ?? false))
      $Element = (object)[
        'ID' => $Row->ID,
        'TIMESTAMP_X' => $Row->TIMESTAMP_X,
        'MODIFIED_BY' => $Row->MODIFIED_BY,
        'DATE_CREATE' => $Row->DATE_CREATE,
        'CREATED_BY' => $Row->CREATED_BY,
        'IBLOCK_ID' => $Row->IBLOCK_ID,
        'IBLOCK_SECTION_ID' => $Row->IBLOCK_SECTION_ID,
        'ACTIVE' => $Row->ACTIVE,
        'ACTIVE_FROM' => $Row->ACTIVE_FROM,
        'ACTIVE_TO' => $Row->ACTIVE_TO,
        'SORT' => $Row->SORT,
        'NAME' => $Row->NAME,
        'PREVIEW_PICTURE' => $Row->PREVIEW_PICTURE,
        'PREVIEW_TEXT' => $Row->PREVIEW_TEXT,
        'PREVIEW_TEXT_TYPE' => $Row->PREVIEW_TEXT_TYPE,
        'DETAIL_PICTURE' => $Row->DETAIL_PICTURE,
        'DETAIL_TEXT' => $Row->DETAIL_TEXT,
        'DETAIL_TEXT_TYPE' => $Row->DETAIL_TEXT_TYPE,
        'SEARCHABLE_CONTENT' => $Row->SEARCHABLE_CONTENT,
        'IN_SECTIONS' => $Row->IN_SECTIONS,
        'XML_ID' => $Row->XML_ID,
        'CODE' => $Row->CODE,
        'TAGS' => $Row->TAGS,
        'PROPS' => [],
      ];
    else
      $Element = self::$list_ELement[$Row->ID];


    if (!($Element->PROPS[$Row->IBLOCK_ELEMENT_PROPERTY_CODE] ?? false)) {
      $Element->PROPS[$Row->IBLOCK_ELEMENT_PROPERTY_CODE] = (object)[
        'ID' => $Row->IBLOCK_ELEMENT_PROPERTY_ID,
        'NAME' => $Row->IBLOCK_ELEMENT_PROPERTY_NAME,
        'CODE' => $Row->IBLOCK_ELEMENT_PROPERTY_CODE,
        'ACTIVE' => $Row->IBLOCK_ELEMENT_PROPERTY_ACTIVE,
        'TYPE' => $Row->IBLOCK_ELEMENT_PROPERTY_PROPERTY_TYPE,
        'MULTIPLE' => $Row->IBLOCK_ELEMENT_PROPERTY_MULTIPLE,
        'VALUE' => [],
        'ENUM' => [],
      ];
    }

    $Element->PROPS[$Row->IBLOCK_ELEMENT_PROPERTY_CODE]->VALUE[] = (object)[
      'VALUE' => $Row->IBLOCK_ELEMENT_ELEMENT_PROPERTY_VALUE,
      'DESCRIPTION' => $Row->IBLOCK_ELEMENT_ELEMENT_PROPERTY_DESCRIPTION,
      'TYPE' => $Row->IBLOCK_ELEMENT_ELEMENT_PROPERTY_VALUE_TYPE,
    ];

    $Element->PROPS[$Row->IBLOCK_ELEMENT_PROPERTY_CODE]->ENUM[] = (object)[
      'VALUE' => $Row->IBLOCK_ELEMENT_PROPERTY_ENUM_VALUE,
      'DEF' => $Row->IBLOCK_ELEMENT_PROPERTY_ENUM_DEF,
    ];

    return $Element;
  }
}
