<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    public function index()
    {
        return view('search');
    }

    public function servePdf($rapport_id, $filename)
    {
        $pdfUrl = env('PDF_PATH') . $rapport_id . '/' . $filename . '.pdf';

        try {
            $response = Http::get($pdfUrl);

            if ($response->successful()) {
                return response($response->body(), 200)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'inline; filename="' . $filename . '.pdf"')
                    ->header('Cache-Control', 'public, max-age=3600')
                    ->header('Access-Control-Allow-Origin', '*');
            }

            return response('PDF not found', 404);
        } catch (\Exception $e) {
            return response('Error loading PDF: ' . $e->getMessage(), 500);
        }
    }
}
