<?php

namespace Tests\Feature;

use Illuminate\Console\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use KravanhEco\LaravelCmd\Console\CollectionMakeCommand;
use Orchestra\Testbench\TestCase;

class CollectionMakeCommandTest extends TestCase
{
    public function test_it_can_create_a_collection()
    {
        Application::starting(function ($artisan) {
            $artisan->add(app(CollectionMakeCommand::class));
        });
        
        $filePath = 'Kravanh/Domain/Books/Collections/BookCollection.php';
        
        $shouldOutputFilePath = app_path($filePath);
        
        if (File::exists($shouldOutputFilePath)) {
            unlink($shouldOutputFilePath);
        }

        $exitCode = Artisan::call('kravanh:collection', [
            'name' => 'Books/Book'
        ]);
    
        $this->assertEquals(0, $exitCode);

        $this->assertStringContainsString('Collection created successfully.', Artisan::output());

        $this->assertTrue(File::exists($shouldOutputFilePath));

        $contents = File::get($shouldOutputFilePath);

        $this->assertStringContainsString('namespace App\Kravanh\Domain\Books\Collections;', $contents);
        
        $this->assertStringContainsString('class BookCollection extends Collection', $contents);
    }
}
