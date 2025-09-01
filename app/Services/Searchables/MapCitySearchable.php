<?php
namespace App\Services\Searchables;

use App\Models\MapCity;

class MapCitySearchable
{
  private $model;

  public function __construct()
  {
    $this->model = MapCity::query();
  }

  public function search()
  {
    $this->searchByState();
    $this->sortBy();
    $this->searchByName();
    $this->searchByColumns();
    return $this->returnData();
  }

  public function stateCities($stateId)
  {
    $this->model->having('map_state_id', $stateId);
    $this->sortBy();
    $this->searchByName();
    $this->searchByColumns();
    return $this->returnData();
  }

  public function searchByState()
  {
    if(Request()->state_id){
      $this->model->having('map_state_id', Request()->state_id);
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