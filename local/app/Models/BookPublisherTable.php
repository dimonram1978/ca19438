<?php
namespace Models;
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator,
    Bitrix\Main\ORM\Fields\Relations\Reference,
    Bitrix\Main\ORM\Fields\Relations\OneToMany,
    Bitrix\Main\ORM\Fields\Relations\ManyToMany,
    Bitrix\Main\Entity\Query\Join;

use Models\BookTable as Books;
use Models\PublisherTable as Publisher;
use Models\AuthorTable as Author;
/**
 * Class PublisherTable
 *
 * @package Models
 **/

  
 
 class BookPublisherTable extends DataManager
 {
     /**
      * Returns DB table name for entity.
      *
      * @return string
      */
     public static function getTableName()
     {
         return 'book_publisher';
     }
 
     /**
      * Returns entity map definition.
      *
      * @return array
      */
     public static function getMap()
     {
         return [
             new IntegerField(
                 'id',
                 [
                     'primary' => true,
                     'autocomplete' => true,
                     'title' => Loc::getMessage('PUBLISHER_ENTITY_ID_FIELD'),
                 ]
             ),
             new IntegerField(
                 'book_id',
                 [
                     'title' => Loc::getMessage('PUBLISHER_ENTITY_BOOK_ID_FIELD'),
                 ]
             ),
            
             new IntegerField(
                 'publisher_id',
                 [
                     'title' => Loc::getMessage('PUBLISHER_ENTITY_PUBLISHER_ID_FIELD'),
                 ]
             ),

            /*(new Reference('BOOK_ID', Books::class, Join::on('this.book_id', 'ref.id')))
            ->configureJoinType('inner'),

            (new Reference('PUBLISHER_ID', Publisher::class, Join::on('this.publisher_id', 'ref.id')))
            ->configureJoinType('inner'),*/
         ];
     }
 }