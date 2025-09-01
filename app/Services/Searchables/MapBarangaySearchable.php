<?php
namespace App\Services\Searchables;

use App\Models\MapBarangay;

class MapBarangaySearchable
{
  private $model;

  public function __construct()
  {
    $this->model = MapBarangay::query();
  }

  public function search()
  {
    $this->searchByCity();
    $this->sortBy();
    $this->searchByName();
    $this->searchByColumns();
    return $this->returnData();
  }

  public function cityBarangays($cityId)
  {
    $this->model->having('map_city_id', $cityId);
    $this->sortBy();
    $this->searchByName();
    $this->searchByColumns();
    return $this->returnData();
  }

  public function searchByCity()
  {
    if(Request()->city){
      $this->model->having('map_city_id', Request()->city);
    }
  }

  public function searchByName()
  {
    if(Request()->name){
      $this->model->where('name', Request()->name);
    }
  }

  public function searchByColumns()
  {
    if(Request()->keyword && Request()->keyword!="null"){
      $this->model->where(function($query){
        $keyword = Request()->keyword;
        $query->where('id', $keyword);
        $fillables = ['name'];
        foreach($fillables as $column){
          $query->orWhere($column, 'LIKE', "%$keyword%");
        }
      });
    }
  }

  public function sortBy()
  {
    if(Request()->sort_by){
      $filters = explode('/', Request()->sort_by);
      [$sortKey, $sortType] = $filters;
      $this->model->orderBy($sortKey, $sortType);
    }else{
      $this->model->orderBy('created_at', 'desc');
    }
  }

  private function returnData()
  {
    $perPage = Request()->per_page;
    return $perPage
      ? $this->model->paginate($perPage)
      : $this->model->paginate($this->model->count());
  }

}