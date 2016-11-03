<ul>
    <?php
        foreach($bus as $k => $v):
    ?>
            <li><a href="/routes/index/short?id=<?= $k; ?>&pos=<?= $_GET['pos']?>"><?= $v['distance'] ?> (Necesitas tomar: <?= count($v['route']) ?>)</a></li>
    <?php endforeach; ?>
</ul>