<?php defined('SYSPATH') or die('No direct script access.');?>
<ul class="pagination">
<?php if ($first_page !== FALSE): ?>
<li><a href="<?php echo HTML::chars($page->url($first_page)) ?>"><?php echo __('First') ?></a></li>
<?php endif ?>

<?php if ($previous_page !== FALSE): ?>
<li><a href="<?php echo HTML::chars($page->url($previous_page)) ?>"><?php echo __('Previous') ?></a></li>
<?php endif ?>

<?php for ($i = 1; $i <= $total_pages; $i++): ?>

<?php if ($i == $current_page): ?>
        <li class="active"><a href="#"><?php echo $i ?></a></li>
    <?php else: ?>
        <li><a href="<?php echo HTML::chars($page->url($i)) ?>"><?php echo $i ?></a></li>
    <?php endif ?>

<?php endfor ?>

<?php if ($next_page !== FALSE): ?>
    <li><a href="<?php echo HTML::chars($page->url($next_page)) ?>"><?php echo __('Next') ?></a></li>
<?php endif ?>

<?php if ($last_page !== FALSE): ?>
    <li><a href="<?php echo HTML::chars($page->url($last_page)) ?>"><?php echo __('Last') ?></a></li>
<?php endif ?>
</ul>