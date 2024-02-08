<?php

namespace Tests\Feature;

use Illuminate\Console\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use KravanhEco\LaravelCmd\Console\QueryMakeCommand;
use Orchestra\Testbench\TestCase;

class QueryMakeCommandTest extends TestCase
{
    public function test_it_can_create_a_query()
    {
        Application::starting(function ($artisan) {
            $artisan->add(app(QueryMakeCommand::class));
        });
        
        $filePath = 'Kravanh/Application/Books/Queries/BookQuery.php';
        
        $shouldOutputFilePath = app_path($filePath);
        
        if (File::exists($shouldOutputFilePath)) {
            unlink($shouldOutputFilePath);
        }

        $exitCode = Artisan::call('kravanh:query', [
            'name' => 'Books/Book'
        ]);
    
        $this->assertEquals(0, $exitCode);

        $this->assertStringContainsString('Query created successfully.', Artisan::output());

        $this->assertTrue(File::exists($shouldOutputFilePath));

        $contents = File::get($shouldOutputFilePath);

        $this->assertStringContainsString('namespace App\Kravanh\Application\Books\Queries;', $contents);
        
        $this->assertStringContainsString('class BookQuery extends QueryBuilder', $contents);
    }
}
