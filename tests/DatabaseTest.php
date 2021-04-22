<?php namespace tests\Foothing\Laravel\Consent;

class DatabaseTest extends \Orchestra\Testbench\TestCase {

    protected function getPackageProviders($app) {
        return [
            'Orchestra\Database\ConsoleServiceProvider',
            'Foothing\Laravel\Consent\ConsentServiceProvider',
        ];
    }

    protected function getEnvironmentSetUp($app) {
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'gdpr_consent',
            'username'  => 'gdpr_consent',
            'password'  => 'gdpr_consent',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ]);
    }

    public function setUp() {
        parent::setUp();

        $this->artisan('migrate');

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }

    /**
     * @test
     */
    public function iDontWantPhpunitWarnings() {}
}
