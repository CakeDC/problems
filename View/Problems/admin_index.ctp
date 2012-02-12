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
	<th><?php echo $this->Paginator->sort(__d('problems', 'Type'), 'Problem.model')?></th>
	<?php foreach ($reportTypes as $type => $t) :?>
		<th><?php echo $this->Paginator->sort($t, $type)?></th>
	<?php endforeach; ?>
	<th><?php echo $this->Paginator->sort(__d('problems', 'Total Reports'), 'total_reports')?></th>
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
		<?php foreach ($reportTypes as $type => $t) :?>
			<td><?php echo $problem['Problem'][$type . '_total']; ?></td>
		<?php endforeach; ?>
		<td><?php echo $problem['Problem']['total_reports']; ?></td>
		<td>
			<?php echo $this->Html->link('Review Reports', array('action' => 'review', $model, $foreingKey)); ?> -
			<?php echo $this->Html->link('Accept all', array('action' => 'accept_all', $model, $foreingKey)); ?> -
			<?php echo $this->Html->link('Unaccept all', array('action' => 'unaccept_all', $model, $foreingKey)); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->element('paging'); ?>
</div>