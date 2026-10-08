<?php

include "config.php";
include "db.php";

if (isset($_POST['generate'])) {

    $topic = $_POST['topic'];
    $number = $_POST['number'];

    $prompt = "Generate $number simple educational questions about $topic.
               Give only the questions.
               Number them from 1 onwards.";

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

            break;

        } elseif ($httpCode == 503) {

            if ($attempt < 3) {
                sleep($attempt * 2);
            }

        } else {

            echo "<h3>API Error</h3>";
            echo "<pre>";
            print_r($result);
            echo "</pre>";
            exit;
        }
    }

    if (!isset($questions)) {

        echo "<h3>Google AI service is temporarily busy.</h3>";
        echo "<p>Please try again.</p>";

    } else {

    $questionList = explode("\n", $questions);

    foreach ($questionList as $question) {

        $question = trim($question);

        if ($question != "") {

            $sql = "INSERT INTO questions (topic, question)
                    VALUES ('$topic', '$question')";

            mysqli_query($conn, $sql);
        }
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Generated Questions</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<div class="container">

<h1>Generated Questions</h1>

<p><b>Topic:</b> <?php echo $topic; ?></p>

<p><b>Number of Questions:</b> <?php echo $number; ?></p>

<hr>

<?php echo nl2br($questions); ?>

<br><br>

<a href="index.php">Generate Again</a>
</div>
</body>
</html>

<?php

    }

}

?>