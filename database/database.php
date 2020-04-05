<?php
class DB
{
# function for database connection
    static $conn;
    public static function connection()
    {
        $serverName = "localhost";
        $userName = "root";
        $password = "root";
        $dataBase = "newsbytes";
        self::$conn = new mysqli($serverName, $userName, $password, $dataBase);
    }
# function for insertion
    public static function create(array $data): int
    {
        $colums = "url,s_url,status,single_use,created_at,updated_at";
        $url = $data['url'];
        $shortUrl = $data['short_url'];
        $singleUse = $data['single_use'];
        $status = $data['status'];

        $insertSql = "INSERT INTO short_url($colums) VALUES('$url','$shortUrl',$status,$singleUse,now(),now())";
        self::$conn->query($insertSql);
        return true;
    }
# function to check url already exists or not
    public static function checkUrlExists(string $url)
    {
        $sql = "SELECT s_url from short_url where url = '$url' and status = 1";
        return self::$conn->query($sql);
    }
# fetch Original Url from short url
    public static function getUrl(string $shortUrl)
    {
        $sql = "SELECT id,url,single_use from short_url where s_url = '$shortUrl' and status = 1";
        return self::$conn->query($sql);
    }
# expire url
    public static function expireUrl(int $id): int
    {
        $sql = "UPDATE short_url set status = 0 where id  = '$id'";
        self::$conn->query($sql);
        return true;
    }
# check short string already exists or not
    public static function checkShortString($string)
    {
        $sql = "SELECT s_url from short_url where s_url = '$string' and status = 1";
        return self::$conn->query($sql);
    }
}
