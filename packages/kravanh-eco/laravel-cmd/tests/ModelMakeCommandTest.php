<?php

namespace Tests\Feature;

use Illuminate\Console\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use KravanhEco\LaravelCmd\Console\PolicyMakeCommand;
use KravanhEco\LaravelCmd\Console\ControllerMakeCommand;
use Orchestra\Testbench\TestCase;

class ModelMakeCommandTest extends TestCase
{
    public function test_it_can_create_a_model()
    {
        Application::starting(function ($artisan) {
            $artisan->add(app(PolicyMakeCommand::class));
        });

        $filePath = 'Kravanh/Domain/Books/Models/Book.php';

        $shouldOutputFilePath = app_path($filePath);

        if (File::exists($shouldOutputFilePath)) {
            unlink($shouldOutputFilePath);
        }

        $exitCode = Artisan::call('kravanh:model', [
            'name' => 'Books/Book'
        ]);

        $this->assertEquals(0, $exitCode);

        $this->assertStringContainsString('Model created successfully.', Artisan::output());

        $this->assertTrue(File::exists($shouldOutputFilePath));

        $contents = File::get($shouldOutputFilePath);

        $this->assertStringContainsString('namespace App\Kravanh\Domain\Books\Models;', $contents);

        $this->assertStringContainsString('class Book extends Model', $contents);
    }

    public function test_it_can_create_a_model_with_a_controller_option()
    {
        Application::starting(function ($artisan) {
            $artisan->add(app(PolicyMakeCommand::class));
            $artisan->add(app(ControllerMakeCommand::class));
        });

        $modelFilePath = 'Kravanh/Domain/Books/Models/Book.php';
        $controllFilePath = 'Kravanh/Application/Books/Controllers/BookController.php';

        $modelShouldOutputFilePath = app_path($modelFilePath);
        $controllerShouldOutputFilePath = app_path($controllFilePath);

        if (File::exists($modelShouldOutputFilePath)) {
            unlink($modelShouldOutputFilePath);
        }

        if (File::exists($controllerShouldOutputFilePath)) {
            unlink($controllerShouldOutputFilePath);
        }

        $exitCode = Artisan::call('kravanh:model', [
            'name' => 'Books/Book',
            '--controller' => null
        ]);

        $this->assertEquals(0, $exitCode);

        $this->assertStringContainsString('Model created successfully.', Artisan::output());

        $this->assertTrue(File::exists($modelShouldOutputFilePath));

        $modelContents = File::get($modelShouldOutputFilePath);

        $this->assertStringContainsString('namespace App\Kravanh\Domain\Books\Models;', $modelContents);

        $this->assertStringContainsString('class Book extends Model', $modelContents);

        $this->assertTrue(File::exists($controllerShouldOutputFilePath));

        $controllerContents = File::get($controllerShouldOutputFilePath);

        $this->assertStringContainsString('namespace App\Kravanh\Application\Books\Controllers;', $controllerContents);

        $this->assertStringContainsString('class BookController extends Controller', $controllerContents);
    }
}
