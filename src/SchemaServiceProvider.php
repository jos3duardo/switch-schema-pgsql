<?php

namespace Jos3duardo\SwitchSchemaPgsql;


use Illuminate\Support\ServiceProvider;

class SchemaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('pgschema', function () {
            return new Schemas;
        });
    }
}
