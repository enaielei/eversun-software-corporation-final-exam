<?php $title = "Page Two"; $style = "./../styles/style.css"; include_once("./../templates/_pre.php"); ?>
<?php
    $_words = "words";
    $raw_words = "";
    $words = [];
    if(isset($_GET[$_words]) && !empty($_GET[$_words])) {
        $raw_words = $_GET[$_words];
        $words = explode("\n", $raw_words);
        $words = array_map(fn($value) => trim($value), $words);
        // echo(var_dump($words));
    }

    function _getRow($size, $number) {
        $row = null;
        for($x = 0; $x < $size[0]; $x++) {
            $min = ($size[1] * $x) + 1;
            $max = $min + ($size[1] - 1);
            if($number >= $min && $number <= $max) {
                $row = $x;
                break;
            }
        }
        return $row;
    }

    function _getPositions($size, $number) {
        $row = _getRow($size, $number);
        $arr = [];
        $skip = $size[1] + 1;

        $min = ($size[1] * $row) + 1;
        $max = $min + ($size[1] - 1);

        $mc = min(($max - $number) + 1, $size[1]);

        for($i = $row, $c = 0; $i < $mc; $i++, $c++) {
            $nnumber = $number + ($skip * $c);
            if($nnumber <= $size[0] * $size[1])
                array_push($arr, $nnumber);
        }
        return $arr;
    }

    // echo(var_dump(_getPositions([3, 4], 1)));
    // echo(var_dump(_getPositions([3, 4], 5)));

    function getGrid(
        $size=[20, 20],
        $words=[],
        $highlight="<td class='highlight'><span>%s</span></td>",
        $random="<td class='random'><span>%s</span></td>",
        $randoms="abcdefghijklmnopqrstuvwxyz"
    ) {
        if(gettype($size) == "number") $size = [$size, $size];
        $msize = max($size);
        $tsize = $size[0] * $size[1];

        $nwords = [];
        if($words) {
            foreach($words as $word) {
                if(!empty($word) && strlen($word) <= $msize)
                    array_push($nwords, $word);
            }
        }

        $starts = [];
        $positions = [];
        foreach($nwords as $word) {
            $len = strlen($word);
            $start = null;
            $dlen = 0;
            $pos = [];

            while($start == null || array_key_exists($start, $positions) ||
                $len > $dlen || in_array($start, $positions)) {
                $start = rand(1, $tsize);
                $pos = _getPositions($size, $start);
                $dlen = count($pos);
            }

            $starts[$start] = $word;
            $positions = array_unique(array_merge($positions, $pos), SORT_REGULAR);
            // echo("<br>word: $word, start: $start, row: $row, len: $dlen<br>");
        }

        $arr = [];
        $count = 0;
        for($x = 0; $x < $size[0]; $x++) {
            array_push($arr, []);
            for($y = 0; $y < $size[1]; $y++) {
                $count++;

                $r = rand(0, strlen($randoms) - 1);
                $l = $randoms[$r];
                $t = $random;

                foreach($starts as $start => $word) {
                    $pos = _getPositions($size, $start);
                    if(in_array($count, $pos) && strlen($word) > 0) {
                        $l = strtoupper($word[0]);
                        $starts[$start] = substr($word, 1);
                        $t = $highlight;
                    }
                }

                array_push($arr[count($arr) - 1], sprintf($t, $l));
            }
        }
        return $arr;
    }
?>
<a href="./1.php">< Back</a>
<table>
    <tbody>
    <?php
        foreach(getGrid([20, 20], $words) as $row) {
            $ielem = join($row);
            $elem = "<tr>%s</tr>";
            echo(sprintf($elem, $ielem));
        }
    ?>
    </tbody>
</table>
<form action="./2.php" method="get">
    <?php
        if(!empty($raw_words)) {
    ?>
            <input type="hidden" name="words" value="<?= $raw_words ?>" required>
    <?php } ?>
    <button type="submit">Replay</button>
</form>
<?php include_once("./../templates/_post.php") ?>