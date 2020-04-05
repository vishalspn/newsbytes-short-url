<?php
include 'database/database.php';

class ShortUrl
{
    public function __construct()
    {
        DB::connection();
    }

    public function createShortUrl(Array $postData)
    {
        $url = trim($postData['url']);
        $singleTimeUse = $postData['single_time_use_flag'];
        if (empty($url)) {
            $reponse['status'] = "success";
            $reponse['code'] = 400;
            $reponse['message'] = "Url Can't be empty";
            return $reponse;
        }
        if (empty($singleTimeUse)) {
            $singleTimeUse = 0;
        }
        try {
            # check url already exists or not
            $urlExists = DB::checkUrlExists($url);
            if (empty($urlExists)) {
                $insertArray['url'] = trim($url);
                $insertArray['short_url'] = $this->shortUrlString();
                $insertArray['status'] = 1;
                $insertArray['single_use'] = $singleTimeUse;
                DB::create($insertArray);
                $reponse['status'] = "success";
                $reponse['code'] = 200;
                $reponse['short_url'] = $_SERVER['SERVER_NAME'] . '/' . $insertArray['short_url'];
                $reponse['message'] = "Url generated successfully";
                return $reponse;
            } else {
                $reponse['status'] = "success";
                $reponse['code'] = 200;
                $reponse['short_url'] = $urlExists['short_url'];
                $reponse['message'] = "Url generated successfully";
                return $reponse;
            }

        } catch (Exception $e) {
            $reponse['status'] = "failed";
            $reponse['code'] = 500;
            $reponse['message'] = $e->getMessage();
            return $reponse;
        }

    }
    public function shortUrlString($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function redirect($shortUrl)
    {
        if (empty($shortUrl)) {
            $reponse['status'] = "success";
            $reponse['code'] = 400;
            $reponse['message'] = "Url Can't be empty";
            return $reponse;
        }
        $path = parse_url($shortUrl);
        # fetch url from short Url
        $url = DB::getUrl(str_replace('/newsbytes-short-url/', '', $path['path']));
        if ($url->num_rows > 0) {
            #check url is one useable or not
            $row = mysqli_fetch_assoc($url);
            if ($row['single_use']) {
                DB::expireUrl($row['id']);
            }
            return $row['url'];
        } else {
            return 404;
        }
    }
}
