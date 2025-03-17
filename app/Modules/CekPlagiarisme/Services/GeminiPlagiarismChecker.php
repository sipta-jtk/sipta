<?php

namespace App\Modules\CekPlagiarisme\Services;

use Google\Client;
use Google\Service\VertexAI;
use Illuminate\Support\Facades\Http;

class GeminiPlagiarismChecker
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName("PlagiarismChecker");
        $this->client->setDeveloperKey(env('GEMINI_API_KEY'));
    }

    public function checkPlagiarism($text)
    {
        $apiKey = env('GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateText?key={$apiKey}";

        $data = [
            "prompt" => [
                "text" => "Cek plagiarisme untuk teks berikut: \"$text\" dan berikan persentase kemungkinan plagiarisme beserta sumbernya."
            ]
        ];

        $response = Http::post($url, $data);

        return $response->json();
    }
}
