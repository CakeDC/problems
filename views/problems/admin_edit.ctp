<?php echo $this->Form->create('Problem', array('url' => array('action' => 'edit')));?>
	<fieldset>
 		<legend><?php __('Report a Problem');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('type');
		echo $this->Form->input('description');
		echo $this->Form->input('offensive', array(
			'label' => __('Report as offensive', true)));
		echo $this->Form->hidden('Data.referer', array('value' => @$referer));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>