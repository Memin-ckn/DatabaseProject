<div class="pagination">
    <!-- First page -->
    <?php if ($page > 1): ?>
        <a href="?page=1"> << </a>
        <a href="?page=<?php echo $page - 1; ?>"> < </a>
    <?php endif; ?>
    <!-- Not first page -->
    <?php
        $x = 4;
        for ($i = $page - $x; $i <= $page + $x; $i++): ?>
            <?php if ($i > 0 and $i <= $total_pages): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php if ($i == $page)
                        echo 'active'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>"> ></a>
            <a href="?page=<?php echo $total_pages; ?>">>></a>
        <?php endif; ?>
        
</div>