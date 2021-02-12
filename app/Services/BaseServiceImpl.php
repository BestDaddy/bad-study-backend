<?php


namespace App\Services;


use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;

class BaseServiceImpl implements BaseService
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseServiceImpl constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function allWith(array $relationships)
    {
        return $this->model->with($relationships)->get();
    }

    public function withWhere(array $relationships, array $params)
    {
        $query = $this->model->with($relationships);

        foreach ($params as $field => $value) {
            $query = $query->where($field, $value);
        }

        return $query->get();
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): Model
    {
        $model = $this->model->newInstance();
        $model->fill($attributes);
        $model->save();
        return $model;
    }

    /**
     * @inheritDoc
     */
    public function find($id): ?Model
    {
        $model = $this->model->findOrFail($id);

        return $model;
    }

    public function findWith($id, array $relationships)
    {
        return $this->model->with($relationships)->findOrFail($id);
    }

    public function firstWhere(array $params)
    {
        $query = $this->model;

        foreach ($params as $field => $value) {
            $query = $query->where($field, $value);
        }

        return $query->first();
    }

    public function getWhere(array $params)
    {
        $query = $this->model;

        foreach ($params as $field => $value) {
            $query = $query->where($field, $value);
        }

        return $query->get();
    }

    public function update($id, array $attributes): Model
    {
        $model = $this->model->find($id);

        $model->update($attributes);

        return $model;
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    public function updateFirstWhere(array $params, array $attributes)
    {
        $model = $this->firstWhere($params);

        if (!empty($model)) {
            $model->update($attributes);
        }

        return $model;
    }
}
