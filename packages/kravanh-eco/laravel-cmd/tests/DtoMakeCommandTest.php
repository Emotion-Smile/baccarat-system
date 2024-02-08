<?php

namespace Tests\Feature;

use Illuminate\Console\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use KravanhEco\LaravelCmd\Console\DtoMakeCommand;
use Orchestra\Testbench\TestCase;

class DtoMakeCommandTest extends TestCase
{
    public function test_it_can_create_a_data_transfter_object()
    {
        Application::starting(function ($artisan) {
            $artisan->add(app(DtoMakeCommand::class));
        });
        
        $filePath = 'Kravanh/Domain/Books/DataTransferObjects/BookData.php';
        
        $shouldOutputFilePath = app_path($filePath);
        
        if (File::exists($shouldOutputFilePath)) {
            unlink($shouldOutputFilePath);
        }

        $exitCode = Artisan::call('kravanh:dto', [
            'name' => 'Books/Book'
        ]);
    
        $this->assertEquals(0, $exitCode);

        $this->assertStringContainsString('DataTransferObject created successfully.', Artisan::output());

        $this->assertTrue(File::exists($shouldOutputFilePath));

        $contents = File::get($shouldOutputFilePath);

        $this->assertStringContainsString('namespace App\Kravanh\Domain\Books\DataTransferObjects;', $contents);
        
        $this->assertStringContainsString('class BookData', $contents);
    }
}
