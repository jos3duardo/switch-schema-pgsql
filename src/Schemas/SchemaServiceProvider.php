<?php

namespace Jos3duardo\SwitchSchemaPgsql;


use Illuminate\Support\ServiceProvider;

class SchemaServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function register()
    {
        $this->app->bind('pgschema', function () {
            return new Schemas;
        });
    }

    public function provides()
    {
        return [];
    }
}