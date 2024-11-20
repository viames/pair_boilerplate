<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><?php print $this->_('UPLOAD_NEW_MODULE') ?></h5>
			</div>
			<div class="ibox-content">
				<div class="row">
					<div class="col-sm-6 b-r">
						<h3 class="m-t-none m-b"><?php print $this->_('NEW_MODULE') ?></h3>
						<p><?php print $this->_('UPLOAD_NEW_MODULE_DESCRIPTION') ?></p>
					</div>
					<div class="col-sm-6">
						<form role="form" action="modules/add" method="post" enctype="multipart/form-data">
							<label><?php print $this->_('SELECT') ?></label> 
							<?php $this->form->printControl('package') ?>
							<div class="hr-line-dashed"></div>
							<div class="form-group">
								<button type="submit" class="btn btn-primary"><?php $this->_('INSERT')?></button>
								<a href="modules/default" class="btn btn-default cancel"><?php $this->_('CANCEL')?></a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>