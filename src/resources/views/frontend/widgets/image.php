<?php if($image->link): ?>
    <a href="<?=$image->link;?>" title="<?=$image->title;?>">
<?php endif; ?>
<?php if($image->isResponsive): ?>
    <img data-srcset="<?=$image->srcset?>" src="<?=$image->srcset->lg?>" alt="<?=$image->alt?>">
<?php else: ?>
    <img alt="<?=$image->alt;?>" src="<?=$image->url;?>" />
<?php endif; ?>
<?php if($image->link): ?>
    </a>
<?php endif; ?>