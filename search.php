<?php
header('Content-Type: text/html; charset=utf-8');

$db_server = "127.0.0.1";
$db_user = "root";
$db_pass = "";
$db_name = "users";

$connect = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$connect) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

mysqli_set_charset($connect, 'utf8mb4');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search = trim(strip_tags($_POST['search']));

    if (!empty($search)) {
        $sql = "SELECT * FROM books WHERE 
                    Name LIKE ? OR 
                    Author LIKE ? OR 
                    Genre LIKE ? OR 
                    Publication LIKE ? OR 
                    Publishing_house LIKE ?";
        $stmt = mysqli_prepare($connect, $sql);
        $search_param = '%' . $search . '%';
        mysqli_stmt_bind_param($stmt, 'sssss', $search_param, $search_param, $search_param, $search_param, $search_param);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Автор</th>
                    <th>Жанр</th>
                    <th>Год публикации</th>
                    <th>Издательство</th>
                  </tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Author']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Genre']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Publication']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Publishing_house']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Ничего не найдено по запросу: <strong>" . htmlspecialchars($search) . "</strong></p>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<p>Пожалуйста, введите запрос для поиска.</p>";
    }
}

mysqli_close($connect);
?>
