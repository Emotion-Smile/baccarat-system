<?php

namespace Tests\Feature;

use Illuminate\Console\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use KravanhEco\LaravelCmd\Console\ControllerMakeCommand;
use Orchestra\Testbench\TestCase;

class ControllerMakeCommandTest extends TestCase
{
    public function test_it_can_create_a_controller()
    {
        Application::starting(function ($artisan) {
            $artisan->add(app(ControllerMakeCommand::class));
        });
        
        $filePath = 'Kravanh/Application/Books/Controllers/BookController.php';
        
        $shouldOutputFilePath = app_path($filePath);
        
        if (File::exists($shouldOutputFilePath)) {
            unlink($shouldOutputFilePath);
        }

        $exitCode = Artisan::call('kravanh:controller', [
            'name' => 'Books/Book'
        ]);
    
        $this->assertEquals(0, $exitCode);

        $this->assertStringContainsString('Controller created successfully.', Artisan::output());

        $this->assertTrue(File::exists($shouldOutputFilePath));

        $contents = File::get($shouldOutputFilePath);

        $this->assertStringContainsString('namespace App\Kravanh\Application\Books\Controllers;', $contents);
        
        $this->assertStringContainsString('class BookController extends Controller', $contents);
    }
}
