<?php include_once __DIR__ . '/header-dashboard.php'; ?>
<?php if (count($proyectos) > 0): ?>
    <ul class="listado-proyectos">
        <?php foreach ($proyectos as $proyecto): ?>
            <li class="proyecto">
                <a href="/proyecto?id=<?php echo $proyecto->url ?>"><?php echo $proyecto->proyecto ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p class="no-proyectos">No Hay Proyectos Aun. <a href="/crear-proyecto">Crea tu Primer Proyecto</a></p>
<?php endif ?>
<?php include_once __DIR__ . '/footer-dashboard.php'; ?>
