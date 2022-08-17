<?php


namespace Alisuliman\CodeGenerator\Tests;


use Alisuliman\CodeGenerator\ServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array<int, string>
     */
    public function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }
}