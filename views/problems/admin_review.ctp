<div class="problems index">
<h2><?php echo __d('problems', 'Problems');?></h2>
<p>
	<?php
		echo $this->Paginator->counter(array(
		'format' => __d('problems', 'Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%')
		));
	?>
</p>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo __d('problems', 'Reported object'); ?></th>
	<th><?php echo $this->Paginator->sort(__d('problems', 'Object Type'), 'Problem.model')?></th>
	<th><?php echo $this->Paginator->sort(__d('problems', 'Report Type'), 'Problem.type')?></th>
	<th><?php echo $this->Paginator->sort(__d('problems', 'Accepted'), 'Problem.accepted')?></th>
	<th><?php echo __d('problems', 'Description')?></th>
	<th><?php echo __d('problems', 'Actions')?></th>
</tr>
<?php foreach ($problems as $problem) :?>
	<tr>
		<td>
			<?php
				$model = $problem['Problem']['model'];
				$foreingKey = $problem['Problem']['foreign_key'];
				echo $this->Html->link(
					$this->Text->truncate($problem['Problem']['object_title'], 25),
					array(
						'action' => 'view_object',
						$model,
						$foreingKey
					));
			?>
		</td>
		<td><?php echo Inflector::humanize(Inflector::underscore($problem['Problem']['model'])); ?></td>
		<td><?php echo $reportTypes[$problem['Problem']['type']]; ?></td>
		<td><?php echo $problem['Problem']['accepted'] ? __d('problems', 'Yes') : __d('problems', 'No'); ?></td>
		<td><?php echo $this->Text->truncate($problem['Problem']['description'], 100); ?></td>
		<td>
			<?php echo $this->Html->link(__d('problems', 'View Report'), array('action' => 'view', $problem['Problem']['id'])); ?> -
			<?php echo $this->Html->link(__d('problems', 'Accept'), array('action' => 'accept', $problem['Problem']['id'])); ?> -
			<?php echo $this->Html->link(__d('problems', 'Unaccept'), array('action' => 'unaccept', $problem['Problem']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->element('paging'); ?>
</div>