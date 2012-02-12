<div class="problems view">
<h2><?php echo __d('problems', 'Problem');?></h2>
<dl><?php $i = 0; $class = ' class="altrow"';?>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('problems', 'Reported Object'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php
			$model = $problem['Problem']['model'];
			$foreingKey = $problem['Problem']['foreign_key'];
			echo $this->Html->link(
				$problem['Problem']['object_title'],
				array(
					'action' => 'view_object',
					$model,
					$foreingKey
				));
		?>
		&nbsp;
	</dd>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('problems', 'Object Type'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo Inflector::humanize(Inflector::underscore($problem['Problem']['model'])); ?>
		&nbsp;
	</dd>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('problems', 'Reported by'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $this->Html->link($problem['User']['username'], array('controller' => 'users', 'action' => 'view', $problem['User']['id'])); ?>
		&nbsp;
	</dd>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('problems', 'Description'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $problem['Problem']['description']; ?>
		&nbsp;
	</dd>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('problems', 'Offensive'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php 
			echo $problem['Problem']['offensive'] ? __d('problems', 'Yes') : __d('problems', 'No');
		?>
		&nbsp;
	</dd>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('problems', 'Request To Edit'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php 
			echo $problem['Problem']['request_to_edit'] ? __d('problems', 'Yes') : __d('problems', 'No');
		?>
		&nbsp;
	</dd>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('problems', 'Reported'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $problem['Problem']['created']; ?>
		&nbsp;
	</dd>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('problems', 'Accepted'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php 
			echo $problem['Problem']['accepted'] ? __d('problems', 'Yes') : __d('problems', 'No');
		?>
		&nbsp;
	</dd>
</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__d('problems', 'Accept'), array('action' => 'accept', $problem['Problem']['id'])); ?></li>
		<li><?php echo $this->Html->link(__d('problems', 'Unaccept'), array('action' => 'unaccept', $problem['Problem']['id'])); ?><li>
		<li><?php echo $this->Html->link(__d('problems', 'Delete Problem'), array('action' => 'delete', $problem['Problem']['id']), null, sprintf(__d('problems', 'Are you sure you want to delete # %s?'), $problem['Problem']['id'])); ?> </li>
	</ul>
</div>
