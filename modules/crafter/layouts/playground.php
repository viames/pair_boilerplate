<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Playground</h4>
			</div>
			<div class="card-body">
				<h3>Choise:</h3>
				<ul>
					<li><a href="crafter/playground/AppException">Throw AppException</a></li>
					<li><a href="crafter/playground/PairException">Throw PairException</a></li>
					<li><a href="crafter/playground/PairExceptionWithCode">Throw PairException with error code</a></li>
					<li><a href="crafter/playground/CriticalException">Throw CriticalException</a></li>
					<li><a href="crafter/playground/FailureQuery">Run a SQL query on table with a missing column</a></li>
				</ul>
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