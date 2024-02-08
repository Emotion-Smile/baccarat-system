<?php

namespace Tests\Feature;

use Illuminate\Console\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use KravanhEco\LaravelCmd\Console\RequestMakeCommand;
use Orchestra\Testbench\TestCase;

class RequestMakeCommandTest extends TestCase
{
    public function test_it_can_create_a_request()
    {
        Application::starting(function ($artisan) {
            $artisan->add(app(RequestMakeCommand::class));
        });
        
        $filePath = 'Kravanh/Application/Books/Requests/BookRequest.php';
        
        $shouldOutputFilePath = app_path($filePath);
        
        if (File::exists($shouldOutputFilePath)) {
            unlink($shouldOutputFilePath);
        }

        $exitCode = Artisan::call('kravanh:request', [
            'name' => 'Books/Book'
        ]);
    
        $this->assertEquals(0, $exitCode);

        $this->assertStringContainsString('Request created successfully.', Artisan::output());

        $this->assertTrue(File::exists($shouldOutputFilePath));

        $contents = File::get($shouldOutputFilePath);

        $this->assertStringContainsString('namespace App\Kravanh\Application\Books\Requests;', $contents);
        
        $this->assertStringContainsString('class BookRequest extends FormRequest', $contents);
    }
}
