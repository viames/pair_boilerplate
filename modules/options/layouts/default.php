<?php

/**
 * @version	$Id$
 * @author	Viames Marino
 */

?><div class="col-lg-12">
	<form action="options/save" method="post" class="form-horizontal">
		<fieldset><?php 

			$currentGroup = NULL;
			
			foreach ($this->groupedOptions as $groupName=>$group) {

				?><div class="ibox">
				<div class="ibox-title"><h5><?php print $groupName ?></h5></div>
				<div class="ibox-content"><?php 
										
				foreach ($group as $o) {
			
					?><div class="form-group row">
						<label class="col-md-3 control-label"><?php print $o->label ?><br><small><?php print $o->name ?></small></label>
						<div class="col-md-9"><?php print $this->form->renderControl($o->name)  ?></div>
					</div><?php
						
				}
										
				?></div>
				</div><?php

			}
	
			?><hr>
			<div class="form-group">
				<div class="col-md-push-3 col-md-9">
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php $this->_('SAVE') ?></button>
				</div>
			</div>
		</fieldset>
	</form>
</div>