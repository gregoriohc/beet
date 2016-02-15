<?php

namespace Gregoriohc\Beet\Support;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class Service
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder|Model
     * @throws \Exception
     */
    public static function model()
    {
        $modelClass = self::modelClass();

        if (!class_exists($modelClass)) {
            throw new Exception('');
        }

        return new $modelClass();
    }

    /**
     * @return string
     */
    public static function modelClass()
    {
        $modelName = substr(class_basename(get_called_class()), 0, -7);

        return 'App\\Models\\' . $modelName;
    }

    /**
     * @return \App\Models\Object
     */
    public static function modelInfo()
    {
        $modelName = substr(class_basename(get_called_class()), 0, -7);

        $objectModelClass = 'App\\Models\\Object';

        /** @var \Illuminate\Database\Eloquent\Builder $objectModel */
        $objectModel = new $objectModelClass;

        return $objectModel->where('name', '=', $modelName)->first();
    }

    /**
     * @param $id
     * @return Model
     */
    public static function get($id)
    {
        if (!$model = self::model()->find($id)->first()) {
            throw new NotFoundHttpException();
        }

        return $model;
    }

    /**
     * @param array $data
     * @return Model
     */
    public static function create($data)
    {
        /** @var Model $class */
        $class = self::modelClass();

        return $class::create($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Model
     */
    public static function update($id, $data)
    {
        $model = self::get($id);

        $model->update($data);

        return $model;
    }

    /**
     * @param int $id
     * @return Model
     */
    public static function delete($id)
    {
        $model = self::get($id);

        $model->delete();

        return $model;
    }
}