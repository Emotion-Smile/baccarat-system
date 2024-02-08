<?php

namespace Tests\Feature;

use Illuminate\Console\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use KravanhEco\LaravelCmd\Console\ViewModelMakeCommand;
use Orchestra\Testbench\TestCase;

class ViewModelMakeCommandTest extends TestCase
{
    public function test_it_can_create_a_view_model()
    {
        Application::starting(function ($artisan) {
            $artisan->add(app(ViewModelMakeCommand::class));
        });
        
        $filePath = 'Kravanh/Application/Books/ViewModels/BookViewModel.php';
        
        $shouldOutputFilePath = app_path($filePath);
        
        if (File::exists($shouldOutputFilePath)) {
            unlink($shouldOutputFilePath);
        }

        $exitCode = Artisan::call('kravanh:view-model', [
            'name' => 'Books/Book'
        ]);
    
        $this->assertEquals(0, $exitCode);

        $this->assertStringContainsString('ViewModel created successfully.', Artisan::output());

        $this->assertTrue(File::exists($shouldOutputFilePath));

        $contents = File::get($shouldOutputFilePath);

        $this->assertStringContainsString('namespace App\Kravanh\Application\Books\ViewModels;', $contents);
        
        $this->assertStringContainsString('class BookViewModel extends ViewModel', $contents);
    }
}
