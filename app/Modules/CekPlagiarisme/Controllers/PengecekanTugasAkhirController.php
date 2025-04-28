<?php

namespace App\Modules\CekPlagiarisme\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PengecekanTugasAkhirController extends Controller
{
    public function cekTugasAkhir(Request $request)
    {
        $request->validate([
            'docfile' => 'required|file|mimes:txt,docx,pdf|max:15000',
        ]);
    
        $file = $request->file('docfile');
    
        try {
            $client = new Client();
            $response = $client->post('http://192.168.116.195:8080/filetest/', [
                'multipart' => [
                    [
                        'name'     => 'docfile',
                        'contents' => fopen($file->getPathname(), 'r'),
                        'filename' => $file->getClientOriginalName(),
                    ],
                ],
            ]);
    
            $data = json_decode($response->getBody()->getContents(), true);
    
            return view('CekPlagiarisme.views.PengecekanTugasAkhir', [
                'percent' => $data['percent'] ?? null,
                'link'    => $data['link'] ?? null,
            ]);
    
        } catch (RequestException $e) {
            return back()->withErrors(['error' => 'Gagal menghubungi server Django.']);
        }
    }
    
}
