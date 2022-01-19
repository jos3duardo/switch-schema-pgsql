<?php

namespace Jos3duardo\Schemas;


use Illuminate\Support\ServiceProvider;

/**
 * Class SchemaServiceProvider
 *
 * @package Jos3duardo\Schemas
 */
class SchemaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('pgschema', function () {
            return new Schemas;
        });
    }
}
