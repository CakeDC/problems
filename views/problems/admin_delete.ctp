<h2><?php echo __d('problems', 'Delete Problem'); ?></h2>
<p>	
	<?php __d('problems', 'Be aware that your Problem will be deleted if you confirm!'); ?>
</p>
<?php
	echo $this->Form->create('Problem', array(
		'url' => array(
			'action' => 'delete',
			$problem['Problem']['id'])));

			echo $this->Form->input('confirm', array(
		'label' => __d('problems', 'Confirm', true),
		'type' => 'checkbox',
		'error' => __d('problems', 'You have to confirm.', true)));
	echo $this->Form->submit(__d('problems', 'Continue', true));
	echo $this->Form->end();

?>