<?php

//---Задание 1 ----

//select *
//from a
//inner join b.a_id on a.id where a.prop is null or b.prop is null

//---Задание 2 ----

//      create table city
//      (
//            id int auto_increment primary key,
//            name varchar(64) not null
//      );
//
//      create table shop
//      (
//            id int auto_increment primary key,
//            city_id int not null,
//            lat float not null,
//            lng float not null,
//            constraint shop_city_id_fk
//            foreign key (city_id) references city (id)
//      );
//
//      create table station
//      (
//             id int auto_increment primary key,
//             name varchar(64) not null,
//             color varchar(64),
//             shop_id int not null,
//             constraint station_shop_id_fk
//             foreign key (shop_id) references shop (id)
//      );

//--- Задание 3 ----

class a
{
    private $name = 'a';

    public function getName()
    {
        return $this->name;
    }
}

class b extends a
{
    protected $name = 'b';
}

class c extends a
{
    private $name = 'c';
}

$a =  new a;
$d = new ReflectionClass('c');
$d->getProperty('name')->setAccessible(true);
echo $a->getName();
echo $d->getName();
die($a->getName());


//---Задание 4 -----

$input_array = [
    ['id' => 1, 'parent_id' => 0, 'sort' => 0, 'name' => 'категория 1'],
    ['id' => 2, 'parent_id' => 0, 'sort' => 1, 'name' => 'категория 2'],
    ['id' => 3, 'parent_id' => 1, 'sort' => 2, 'name' => 'категория 1-2'],
    ['id' => 4, 'parent_id' => 1, 'sort' => 1, 'name' => 'категория 1-1'],
    ['id' => 5, 'parent_id' => 3, 'sort' => 0, 'name' => 'категория 1-2-2'],
    ['id' => 6, 'parent_id' => 1, 'sort' => 3, 'name' => 'категория 1-3'],
    ['id' => 7, 'parent_id' => 5, 'sort' => 3, 'name' => 'категория 1-2-2-1'],
];

$base_id = 0;
$result_array = Category::start($input_array);


class Category
{
    protected static $result_array = [];

    public static function start(array $input_array)
    {
        static::split($input_array, 0);
        return static::$result_array['children'];
    }

    protected static function split(array $input_array, int $id, array $loop_array = []) :array
    {
        foreach($input_array as $value){
            if ($value['parent_id'] === $id){
                $loop_array['children'][]  = static::split($input_array, $value['id'], $value);
                static::$result_array = $loop_array;
            }
        }
        return $loop_array;
    }

}

echo('<pre>' . print_r($result_array,true) . '</pre>');
die();


//--- Задание 5 ---

//Защита от CSRF аттак

//--- Задание 6 ----

// На фронте произвоим валидацию, чтобы снизить нагрузку на сервер
// На сервере производим валидацию, чтобы предотвраить умышленное редактирование html-кода на фронте злоумышленниками

// --- Задание 7 ---

//JSON формат



