<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Page;
use App\Models\Contact;
use App\Models\ProductImage;
use Barryvdh\DomPDF\Facade\Pdf;
use GuzzleHttp\Client;

function getCategories()
{
    return Category::orderBy("name", "asc")
        ->with('subCategories')
        ->withCount('products')
        ->where('status', 1)
        ->where('showHome', 'Yes')
        ->get();
}

function getContact()
{
    return Contact::first();
}

function getProductImage($productId){
    return ProductImage::where('product_id', $productId)->first();
}

function sendSMS( $phoneNumber, $text)
{
    try {
        $client = new Client();
        $response = $client->post('https://sms.aakashsms.com/sms/v3/send', [
            'form_params' => [
                'auth_token' => config('app.sms_token'),
                'to' => $phoneNumber,
                'text' => $text,
            ],
        ]);

        if ($response->getStatusCode() === 200) {
            $message = 'SMS sent successfully';
            session()->flash('success', $message);

            return response()->json(['status' => true, 'message' => $message]);
        } else {
            $message = 'Failed to send SMS';
            session()->flash('error', $message);
            return response()->json(['error' => $message, 'status' => false]);
        }
    } catch (\Exception $e) {
        $message = 'Failed to send SMS. Check your Internet Connection.';
        session()->flash('error', $message);
        return response()->json(['error' => 'Failed to send SMS: ' . $e->getMessage(), 'message' => $message]);
    }
}

function orderSMS($orderId){
    $order = Order::where('id', $orderId)->with('orderItems')->first();

    if ($order) {
        try {
            $invoiceLink = route('front.invoice',$orderId); // Replace with the actual route name for your invoice

            $client = new Client();
            $response = $client->post('https://sms.aakashsms.com/sms/v3/send', [
                'form_params' => [
                    'auth_token' => config('app.sms_token'),
                    'to' => $order->mobile,
                    'text' => "You have successfully placed your order::: $invoiceLink",
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $message = 'SMS sent to customer successfully';
                session()->flash('success',$message);

                return response()->json(['status' => true, 'message' => $message]);
            } else {
                $message = 'Failed to send SMS';
                session()->flash('error',$message);
                return response()->json(['error' => $message, 'status' => false]);
            }
        } catch (\Exception $e) {
            $message = 'Failed to send SMS. Check your Internet Connection.';
            session()->flash('error',$message);
            return response()->json(['error' => 'Failed to send SMS: ' . $e->getMessage(), 'message' => $message]);
        }
    }

}

function staticPages(){
    $pages = Page::orderBy('name','ASC')->get();

    return $pages;

}

function euclideanDistance($vectorA, $vectorB)
{
    $sumSquaredDifferences = 0;

    foreach (array_map(null, $vectorA, $vectorB) as [$a, $b]) {
        $sumSquaredDifferences += ($a - $b) ** 2;
    }

    $euclideanDistance = sqrt($sumSquaredDifferences);

    // Convert Euclidean distance to similarity
    $similarity = 1 / (1 + $euclideanDistance);

    return $similarity;
}




function cosineSimilarity($vectorA, $vectorB)
{
    $dotProduct = array_sum(array_map(function ($a, $b) {
        return $a * $b;
    }, $vectorA, $vectorB));

    $magnitudeA = sqrt(array_sum(array_map(function ($a) {
        return $a * $a;
    }, $vectorA)));

    $magnitudeB = sqrt(array_sum(array_map(function ($b) {
        return $b * $b;
    }, $vectorB)));

    if ($magnitudeA == 0 || $magnitudeB == 0) {
        return 0; // Avoid division by zero
    }

    return $dotProduct / ($magnitudeA * $magnitudeB);
}

function tfidf($text, $allTexts)
{
    $words = explode(' ', strtolower($text));
    $tfidfWeights = [];

    foreach ($words as $word) {
        $tfidfWeights[] = calculateTfIdf($word, $text, $allTexts);
    }

    return empty($tfidfWeights) ? 0 : array_sum($tfidfWeights) / count($tfidfWeights);
}

function calculateTfIdf($word, $paragraph, $allParagraphs)
{
    $termFrequency = calculateTermFrequency($word, $paragraph);
    $inverseDocumentFrequency = calculateInverseDocumentFrequency($word, $allParagraphs);

    return $termFrequency * $inverseDocumentFrequency;
}

function calculateTermFrequency($word, $paragraph)
{
    $wordCount = str_word_count(strtolower($paragraph));
    $termCount = substr_count(strtolower($paragraph), $word);

    return $wordCount > 0 ? $termCount / $wordCount : 0;
}

function calculateInverseDocumentFrequency($word, $allParagraphs)
{
    $documentCount = count($allParagraphs);
    $documentsWithTerm = 0;

    foreach ($allParagraphs as $otherParagraph) {
        if (stripos($otherParagraph, $word) !== false) {
            $documentsWithTerm++;
        }
    }

    return $documentCount > 0 ? log($documentCount / ($documentsWithTerm + 1), 10) : 0;
}
