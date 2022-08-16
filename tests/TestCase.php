<?php


namespace Alisuliman\CodeGen\Tests;


use Alisuliman\CodeGen\ServiceProvider;

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