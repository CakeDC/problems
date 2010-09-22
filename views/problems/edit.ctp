<?php echo $this->Form->create('Problem', array('url' => array('action' => 'edit')));?>
	<fieldset>
 		<legend><?php __d('problems', 'Report a Problem');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('type');
		echo $this->Form->input('description');
		echo $this->Form->input('offensive', array(
			'label' => __d('problems', 'Report as offensive', true)));
		echo $this->Form->hidden('Data.referer', array('value' => @$referer));
	?>
	</fieldset>
<?php echo $this->Form->end(__d('problems', 'Submit', true));?>
