<div class="pagination">
    <!-- First page -->
    <?php if ($page > 1): ?>
        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => 1])); ?>">
            << </a>
                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">
                    < </a>
                    <?php endif; ?>

                    <!-- Not first page -->
                    <?php
                    $x = 4;
                    for ($i = $page - $x; $i <= $page + $x; $i++): ?>
                        <?php if ($i > 0 and $i <= $total_pages): ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" class="<?php if ($i == $page)
                                      echo 'active'; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <!-- Last page -->
                    <?php if ($page < $total_pages): ?>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>"> > </a>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $total_pages])); ?>"> >> </a>
                    <?php endif; ?>
</div>