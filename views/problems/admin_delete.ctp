<h2><?php echo __('Delete Problem'); ?></h2>
<p>	
	<?php __('Be aware that your Problem will be deleted if you confirm!'); ?>
</p>
<?php
	echo $this->Form->create('Problem', array(
		'url' => array(
			'action' => 'delete',
			$problem['Problem']['id'])));
	echo $form->input('confirm', array(
		'label' => __('Confirm', true),
		'type' => 'checkbox',
		'error' => __('You have to confirm.', true)));
	echo $form->submit(__('Continue', true));
	echo $form->end();
?>