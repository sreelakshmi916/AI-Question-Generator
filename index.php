<!DOCTYPE html>
<html>
<head>
    <title>AI Question Generator</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>

<h1>AI Question Generator</h1>

<div class="container">

<form method="post" action="generate.php">
    <label>Enter Topic:</label>
    <input type="text" name="topic" required>

    <br><br>

    <label>Number of Questions:</label>
    <input type="number" name="number" min="1" max="10" required>

    <br><br>

    <input type="submit" name="generate" value="Generate Questions">

</form>
</div>

<br><br>

<a href="view.php">View Saved Questions</a>
</body>
</html>