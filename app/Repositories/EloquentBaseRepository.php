<?php

namespace App\Repositories;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EloquentBaseRepository implements BaseRepositoryInterface
{

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all($user_filter = false)
    {
        if ($user_filter && !Auth::user()->isAdmin())
            return $this->model::orderBy("id", "desc")->whereUserId(Auth::user()->id)->get();

        return $this->model->all();
    }

    public function active()
    {
        return $this->model->whereStatus(1)->get();
    }

    public function disable()
    {
        return $this->model->whereStatus(0)->get();
    }

    public function find($id)
    {
        return $this->model->whereId(strtoupper($id))->first();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function insert(array $data)
    {
        return $this->model->insert($data);
    }

    public function insertIgnore(array $data)
    {
        $result = null;
        try {
            $result = $this->model->insert($data);
        } catch (\Exception $exception) {
            Log::error("Record duplicated.");
        }
        return $result;
    }

    public function update(array $data, $id)
    {
        $record = $this->find($id);
        return $record->update($data);
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    public function with($relations)
    {
        return $this->model->with($relations);
    }


}
