<div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=1">
                        << <a href="?page=<?php echo $page - 1; ?>">
                            < </a>
                            <?php endif; ?>
                            <?php $x = 5;
                            for ($i = $page - 5; $i <= $page + 5; $i++): ?>
                                <?php if ($i > 0 and $i < $total_pages): ?>
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