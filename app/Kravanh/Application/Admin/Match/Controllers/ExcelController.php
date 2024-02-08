<?php

namespace App\Kravanh\Application\Admin\Match\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response;

class ExcelController extends Controller
{
    use ValidatesRequests;

    public function __invoke(Request $request, ResponseFactory $response): Response
    {
        $data = $this->validate($request, [
            'path'     => 'required',
            'filename' => 'required',
        ]);

        return $response->download(
            decrypt($data['path']),
            $data['filename']
        )->deleteFileAfterSend(true);
    }
}