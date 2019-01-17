<?php

namespace App\Repositories;

abstract class RepositoryAbstract
{

    protected $model;

    public function create(array $attributes = []): Model
    {
        $instance = $this->createModel();

        $instance->fill($attributes);

        $instance->save();

        return $instance;
    }


    public function createModel()
    {
        $instance = (new $this->model);

        if (Session::has('database') && empty($instance->getConnectionName())) {
            $instance->setConnection(Session::get('database'));
        }

        return $instance;
    }
}