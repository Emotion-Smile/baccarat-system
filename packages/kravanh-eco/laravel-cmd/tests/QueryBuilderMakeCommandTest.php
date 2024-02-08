<?php

namespace Tests\Feature;

use Illuminate\Console\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use KravanhEco\LaravelCmd\Console\QueryBuilderMakeCommand;
use Orchestra\Testbench\TestCase;

class QueryBuilderMakeCommandTest extends TestCase
{
    public function test_it_can_create_a_query_builder()
    {
        Application::starting(function ($artisan) {
            $artisan->add(app(QueryBuilderMakeCommand::class));
        });
        
        $filePath = 'Kravanh/Domain/Books/QueryBuilders/BookQueryBuilder.php';
        
        $shouldOutputFilePath = app_path($filePath);
        
        if (File::exists($shouldOutputFilePath)) {
            unlink($shouldOutputFilePath);
        }

        $exitCode = Artisan::call('kravanh:query-builder', [
            'name' => 'Books/Book'
        ]);
    
        $this->assertEquals(0, $exitCode);

        $this->assertStringContainsString('QueryBuilder created successfully.', Artisan::output());

        $this->assertTrue(File::exists($shouldOutputFilePath));

        $contents = File::get($shouldOutputFilePath);

        $this->assertStringContainsString('namespace App\Kravanh\Domain\Books\QueryBuilders;', $contents);
        
        $this->assertStringContainsString('class BookQueryBuilder extends Builder', $contents);
    }
}
