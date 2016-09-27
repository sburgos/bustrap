<ul>
    <?php
        foreach($bus as $b):
    ?>
            <li><a href="/routes?id=<?= $b['id']; ?>&ida=<?= $b['ida']?>"><?= $b['name'] ?> (<?= ($b['ida'])?'ida':'vuelta' ?>)</a></li>
    <?php endforeach; ?>
</ul>