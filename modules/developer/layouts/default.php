<?php

/**
 * @version	$Id$
 * @author	Viames Marino
 */

?><div class="row">
	<div class="col-12">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title"><?php $this->_('DEVELOPER') ?></h4>
			</div>
			<div class="panel-body">
				<div class="flex-box-container">
					<div>
						<p>Crea una nuova classe o un nuovo modulo da una tabella della base di dati</em></p>
						<a href="developer/newClass" class="btn btn-primary"><i class="fal fa-play"></i> Inizia</a>
					</div>
					<div>
						<p>Crea una nuova tabella della base di dati da una classe Pair\ActiveRecord</p>
						<a href="developer/newTable" class="btn btn-primary"><i class="fal fa-play"></i> Inizia</a>
					</div>
					<div><?php
			
					if ($this->development) {
						?><p>Azzera i dati ed i file di importazione XLS, fatture e lettere di compensazione</p>
						<a href="developer/cleanData" class="btn btn-warning confirm-action"><i class="fal fa-play"></i> Esegui</a><?php
					}
					
					?></div>
				</div>
			</div>
		</div>
	</div>
</div>