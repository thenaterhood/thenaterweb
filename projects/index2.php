<html>
<body>
<?php
if ($handle = opendir('./')) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "projects_home.html" && $file != ".." && $file != "index.php"
		&& $file != "index2.php") {
            $page_name=substr($file, 0, strpos($file, "."));
			if ($page_name != "" && $page_name != "index" && $page_name != "projects_error") {
				echo "<li><a href=\"";
				echo "?id=";
				echo substr($page_name,9);
				echo "\">";
				echo substr($page_name,9);
				echo "</a></li>\n";
			}
        }
    }
}
closedir($handle);
?>
</body>
</html>