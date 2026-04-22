<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5><?php BoilerplateLayout::printText('UPLOAD_NEW_MODULE'); ?></h5>
			</div>
			<div class="ibox-content">
				<div class="row">
					<div class="col-sm-6 b-r">
						<h3 class="m-t-none m-b"><?php BoilerplateLayout::printText('NEW_MODULE'); ?></h3>
						<p><?php BoilerplateLayout::printText('UPLOAD_NEW_MODULE_DESCRIPTION'); ?></p>
					</div>
					<div class="col-sm-6">
						<form role="form" action="modules/add" method="post" enctype="multipart/form-data">
							<label><?php BoilerplateLayout::printText('SELECT'); ?></label>
							<?php $state->form->printControl('package') ?>
							<div class="hr-line-dashed"></div>
							<div class="form-group">
								<button type="submit" class="btn btn-primary"><?php BoilerplateLayout::printText('INSERT'); ?></button>
								<a href="modules/default" class="btn btn-default cancel"><?php BoilerplateLayout::printText('CANCEL'); ?></a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
