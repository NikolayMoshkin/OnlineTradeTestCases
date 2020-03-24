<?php

class DB
{
    private static $host;
    private static $user;
    private static $password;
    private static $conn;


    public static function init($host, $user, $password, $dbname = 'metro')
    {
        static::$host = $host;
        static::$user = $user;
        static::$password = $password;

        static::initTables($dbname);
        self::$conn = static::connect($dbname);
        return self::class;
    }

    protected static function initTables($dbname)
    {
        $db = new mysqli(static::$host, static::$user, static::$password);
        $query = "create schema IF NOT EXISTS $dbname collate utf8_general_ci;
            use metro;
                
                create table city
                (
                id int auto_increment primary key,
                name varchar(64) not null
                );
                
                create table shop
                (
                id int auto_increment primary key,
                city_id int not null,
                lat float not null,
                lng float not null,
                constraint shop_city_id_fk
                foreign key (city_id) references city (id)
                );
                
                create table station
                (
                id int auto_increment primary key,
                name varchar(64) not null,
                color varchar(64),
                shop_id int not null,
                constraint station_shop_id_fk
                foreign key (shop_id) references shop (id),
                FULLTEXT (name)
                ) ENGINE=InnoDB;
                
                insert into city (name) values ('London'), ('Moskow'), ('Barcelona');
                insert into shop (city_id, lat, lng) values (1, 54.54, 34.45), (2, 54.58, 34.46), (3, 54.62, 34.48);
                insert into station (name , color, shop_id) values ('Петроградская', 'Синий', 1), ('Комендантский проспект', 'Фиолетовый', 2), 
                ('Гражданский проспект', 'Желтый', 1), ('Озерки', 'Синий', 1),
                ('Беговая', 'Желтый', 1), ('Старая деревня', 'Синий', 1),
                ('Горьковская', 'Желтый', 1), ('Дыбенко', 'Синий', 1);
                
        ";


        $db->multi_query($query);
        $db->close();
    }

    protected static function connect($dbname)
    {
        return new mysqli(static::$host, static::$user, static::$password, $dbname);
    }

    public static function count($query){
        $newQuery = preg_replace('/SELECT\s(.)\sFROM/', "SELECT COUNT($1) as counter FROM ", $query);
        return static::exec($newQuery);
    }

    public static function exec($query){
        $result = static::$conn->query($query, MYSQLI_USE_RESULT);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

}