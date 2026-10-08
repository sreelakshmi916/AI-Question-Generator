<?php

include "db.php";

$sql = "SELECT * FROM questions ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Saved Questions</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<div class="container">
<h1>Saved Questions</h1>

<table>

<tr>
    <th>ID</th>
    <th>Topic</th>
    <th>Question</th>
    <th>Created At</th>
</tr>

<?php

while ($row = mysqli_fetch_assoc($result)) {

?>

<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['topic']; ?></td>
    <td><?php echo $row['question']; ?></td>
    <td><?php echo $row['created_at']; ?></td>
</tr>

<?php

}

?>

</table>

<br>

<a href="index.php">Generate New Questions</a>
</div>
</body>

</html>