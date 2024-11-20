<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Playground</h4>
			</div>
			<div class="card-body">
				<h3>Risultati:</h3>
				<div class="crafter-container">
					<?php foreach ($this->results as $res) { ?>
						<div class="crafter-item">
							<pre><?php var_dump($res) ?></pre>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>