<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class WilayahHelper
{
    public static function getProvinsiName($id)
    {
        if(!$id) return null;

        $res = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json");

        foreach ($res->json() as $prov) {
            if ($prov['id'] == $id) {
                return $prov['name'];
            }
        }
        return '-';
    }

    public static function getKabupatenName($id)
    {
        if(!$id) return null;

        $res = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$id}.json");
        return $res->json()['name'] ?? '-';
    }
}
