<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Opzioni</h4>
			</div>
			<div class="card-body">
				<form action="options/save" method="post" class="form-horizontal">
					<fieldset><?php 
			
							$currentGroup = NULL;
						
							foreach ($this->groupedOptions as $groupName=>$group) {
			
								?><h3><?php print $groupName ?></h3>
								<hr>
								<?php 
													
								foreach ($group as $o) {
									
									?><div class="form-group">
										<label class="col-md-3 control-label"><?php print $o->label ?><br><small><?php print $o->name ?></small></label>
										<div class="col-md-9"><?php $this->form->printControl($o->name)  ?></div>
									</div><?php
									
								}
													
							}
				
							?><hr>
							<div class="form-group">
								<div class="col-md-push-3 col-md-9">
									<button type="submit" class="btn btn-primary"><?php $this->_('SAVE') ?></button>
								</div>
							</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>