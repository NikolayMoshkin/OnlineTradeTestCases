<?php

require_once dirname(__DIR__).'/Classes/DB.php';

class Controller
{

    /**
     * @var DB
     */
    private static $conn;

    public static function init($query)
    {
        static::$conn = DB::init('localhost', 'root', '', 'metro');

        preg_match("/(\w+)\&?/", $query, $matches);
        $method = $matches[1];
        if (method_exists(self::class, $method)){
            self::$method();
        }
        else{
            require_once dirname(__DIR__).'/Views/main.php';
        }

    }

    protected static function getCities(){
        $data = static::$conn::exec("SELECT * FROM city");
        echo json_encode($data);
        die();
    }

    protected static function findStations(){

        $city = $_GET['city']?:null;
        $term = $_GET['term'];

        if (strlen($term) === 0){
            header("HTTP/1.1 406 Empty string is not allowed");
            die();
        }

        if (!$city == null){
            $query = "SELECT * FROM station where station.name like '%$term%' 
            and shop_id = (SELECT id from shop where city_id = $city) limit 3";
            $data = static::$conn::exec($query);
            $counter = static::$conn::count($query);
        }
        else{

            $query = "SELECT * FROM station where station.name like '%$term%' limit 3";
            $data = static::$conn::exec($query);
            $counter = static::$conn::count($query);
        }

        $response = ['data' => $data, 'counter' => $counter[0]['counter']];

        echo json_encode($response);
        die();
    }

}