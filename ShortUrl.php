<?php
include 'database/database.php';

class ShortUrl
{
    public function __construct()
    {
        DB::connection();
    }

    public function createShortUrl(array $postData)
    {
        $url = trim($postData['url']);
        $singleTimeUse = $postData['single_time_use_flag'];
        if (empty($url)) {
            $response['status'] = "success";
            $response['code'] = 400;
            $response['message'] = "Url Can't be empty";
            return $response;
        }
        if (empty($singleTimeUse)) {
            $singleTimeUse = 0;
        }
        try {
            # check url already exists or not
            $urlExists = DB::checkUrlExists($url);
            if ($urlExists->num_rows <= 0) {
                $insertArray['url'] = trim($url);
                $insertArray['short_url'] = $this->shortUrlString();
                $insertArray['status'] = 1;
                $insertArray['single_use'] = $singleTimeUse;
                DB::create($insertArray);
                $response['status'] = "success";
                $response['code'] = 200;
                $response['short_url'] = $_SERVER['SERVER_NAME'] . '/newsbytes-short-url/' . $insertArray['short_url'];
                $response['message'] = "Url generated successfully";
                return $response;
            } else {
                $row = mysqli_fetch_assoc($urlExists);
                $response['status'] = "success";
                $response['code'] = 200;
                $response['short_url'] = $_SERVER['SERVER_NAME'] . '/newsbytes-short-url/' . $row['s_url'];
                $response['message'] = "Url generated successfully";
                return $response;
            }

        } catch (Exception $e) {
            $response['status'] = "failed";
            $response['code'] = 500;
            $response['message'] = $e->getMessage();
            return $response;
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
        # check that random string already exists in db or not
        $response = DB::checkShortString($randomString);
        if ($response->num_rows > 0) {
            $this->shortUrlString(8);
        }
        return $randomString;
    }
    public function redirect($shortUrl)
    {
        if (empty($shortUrl)) {
            $response['status'] = "success";
            $response['code'] = 400;
            $response['message'] = "Url Can't be empty";
            return $response;
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
            return null;
        }
    }
}
