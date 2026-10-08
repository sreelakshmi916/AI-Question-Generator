<?php

include "config.php";

$prompt = "Generate 5 simple educational questions about Python programming.";

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.8-flash:generateContent";

$data = [
    "contents" => [
        [
            "parts" => [
                [
                    "text" => $prompt
                ]
            ]
        ]
    ]
];

for ($attempt = 1; $attempt <= 3; $attempt++) {

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "x-goog-api-key: " . $apiKey
    ]);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    $result = json_decode($response, true);

    if ($httpCode == 200) {

        $questions =
            $result["candidates"][0]["content"]["parts"][0]["text"];

        echo "<h2>Generated Questions</h2>";

        echo nl2br($questions);

        break;

    } elseif ($httpCode == 503) {

        if ($attempt < 3) {
            sleep($attempt * 2);
        } else {
            echo "<h3>Google AI service is temporarily busy.</h3>";
            echo "<p>Please try again after a few minutes.</p>";
        }

    } else {

        echo "<h3>API Error</h3>";

        echo "<pre>";
        print_r($result);
        echo "</pre>";

        break;
    }
}

?>