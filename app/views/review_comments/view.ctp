<li class="comment" id="comment-<?php echo $reviewComment['ReviewComment']['id']?>">
	<div class="data">
		<p class="meta date"><?php echo sprintf(__l('posted %s'), $this->Html->cDateTimeHighlight($reviewComment['ReviewComment']['created'])); ?></p>
		<?php echo $this->Html->link('#', '#comment-' . $reviewComment['ReviewComment']['id']);?>
		<cite>
            <span class="author">
                <?php echo $this->Html->link($this->Html->cText($reviewComment['User']['username']), array('controller' => 'users', 'action' => 'view', $reviewComment['User']['username']), array('title' => $reviewComment['User']['username'], 'escape' => false));?>
            </span>
        </cite>
        <?php echo __l('said');?>
		<blockquote>
			<p><?php echo $this->Html->cText($reviewComment['ReviewComment']['comment']);?></p>
		</blockquote>
		
	</div>
</li>