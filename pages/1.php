<?php $title = "Page One"; $style = "./../styles/style.css"; include_once("./../templates/_pre.php"); ?>
<form action="./2.php" method="get">
    <div>
        <label for="words">Words</label><br>
        <textarea name="words" id="words" cols="40" rows="5" required></textarea>
    </div>
    <button type="submit">Submit</button>
</form>
<?php include_once("./../templates/_post.php") ?>