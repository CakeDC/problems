<?php echo $this->Form->create('Problem', array('url' => array($objectType, $foreignKey)));?>
	<fieldset>
 		<legend><?php echo __d('problems', 'Report a Problem');?></legend>
	<?php
		echo $this->Form->hidden('Data.referer', array('value' => @$referer));
		echo $this->Form->input('type', array('label' => __d('problems', 'Type of problem')));
		echo $this->Form->input('description', array('label' => __d('problems', 'Description')));
		echo $this->Form->input('offensive', array(
			'label' => __d('problems', 'Report as offensive')));
	?>
	</fieldset>
<?php echo $this->Form->end(__d('problems', 'Submit')); ?>