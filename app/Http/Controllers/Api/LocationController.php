<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Searchables\MapBarangaySearchable;
use App\Services\Searchables\MapCitySearchable;
use App\Services\Searchables\MapStateSearchable;

class LocationController extends Controller
{
    protected $firebase;

    public function __construct()
    {

    }

    public function getMapStates()
    {
        $result = (new MapStateSearchable)->search();
        $items = collect($result->items())->map(function($item){
            $text = ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', ' $0', $item->name)), '_');
            $displayText = $item->name == 'Tawi-Tawi' ? 'tawi-tawi' : $text;
            return [
                'id' => $item->id,
                'name' => $item->name,
                'text' => trim($displayText),
            ];
        });
        return $items;
    }

    public function getStateCities($id)
    {
        $result = (new MapCitySearchable)->stateCities($id);
        $items = collect($result->items())->map(function($item){
        $text = ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', ' $0', $item->name)), '_');
            return [
                'id' => $item->id,
                'name' => $item->name,
                'text' => trim($text),
            ];
        });
        return $items;
    }

    public function getCityBarangays($id)
    {
        $result = (new MapBarangaySearchable)->cityBarangays($id);
        $items = collect($result->items())->map(function($item){
        $text = ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', ' $0', $item->name)), '_');
            return [
                'id' => $item->id,
                'name' => $item->name,
                'text' => trim($text),
            ];
        });
        return $items;
    }

}