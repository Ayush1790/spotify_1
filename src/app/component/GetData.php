<?php

namespace MyApp\component;

use Phalcon\Di\Injectable;

class GetData extends Injectable
{
    public function getData($search, $type)
    {
        $ch = curl_init();
        $header = [
            "Authorization: Bearer " . $this->cookies->get('token'),
        ];
        $search = str_replace(" ", "%20", $search);
        $type = implode("%2C", $type);
        curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/search/?q=$search&type=$type");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        return json_decode(curl_exec($ch), true);
    }
    public function getById($id, $type)
    {
        $ch = curl_init();
        $header = [
            "Authorization: Bearer " . $this->cookies->get('token'),
        ];
        curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/$type/$id");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        return json_decode(curl_exec($ch), true);
    }
}
