<?php

namespace App\Modules\CekPlagiarisme\Services;

set_time_limit(300);

use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory;
use simplehtmldom\HtmlWeb;

class PlagiarismChecker
{
    public function extractText($filePath)
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        if ($extension === 'pdf') {
            $parser = new Parser();
            $pdf = $parser->parseFile($filePath);
            return $pdf->getText();
        } elseif (in_array($extension, ['doc', 'docx'])) {
            $phpWord = IOFactory::load($filePath);
            $text = '';
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . ' ';
                    }
                }
            }
            return $text;
        }

        return null;
    }

    public function checkPlagiarism($filePath)
    {
        $text = $this->extractText($filePath);
        if (!$text) return ['results' => [], 'percentage' => 0];

        $sentences = explode('.', $text);
        $totalSentences = count($sentences);
        $plagiarizedCount = 0;
        $results = [];

        foreach ($sentences as $sentence) {
            $query = urlencode(trim($sentence));
            $url = "https://scholar.google.com/scholar?q=$query";

            try {
                $html = $this->fetchUrl($url);

                if (!$html) {
                    $results[$sentence] = ["Gagal mengambil data dari Bing"];
                    continue;
                }

                $sources = $this->extractLinksFromHtml($html);

                if (!empty($sources)) {
                    $plagiarizedCount++;
                }

                $results[$sentence] = !empty($sources) ? $sources : ["Tidak ditemukan."];

            } catch (\Exception $e) {
                $results[$sentence] = ["Error: " . $e->getMessage()];
            }
        }

        $plagiarismPercentage = ($totalSentences > 0) ? ($plagiarizedCount / $totalSentences) * 100 : 0;

        return [
            'results' => $results,
            'percentage' => round($plagiarismPercentage, 2)
        ];
    }

    private function fetchUrl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36");

        $output = curl_exec($ch);

        if (curl_errno($ch)) {
            return 'cURL Error: ' . curl_error($ch);
        }

        curl_close($ch);
        return $output;
    }

    private function extractLinksFromHtml($html)
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);

        $links = [];
        foreach ($dom->getElementsByTagName('a') as $link) {
            $href = $link->getAttribute('href');

            // Filter hanya URL valid yang bukan milik Bing sendiri
            if (strpos($href, 'http') === 0 && !strpos($href, 'bing.com')) {
                $links[] = $href;
            }

            if (count($links) >= 5) break; // Ambil maksimal 5 sumber
        }

        return $links;
    }
}
