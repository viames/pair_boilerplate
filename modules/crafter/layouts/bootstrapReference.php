<?php
declare(strict_types=1);

/** @var CrafterBootstrapReferencePageState $state */
?>
<div class="row g-3">
	<div class="col-12">
		<div class="card">
			<div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
				<h4 class="card-title mb-0">Tipografia e componenti</h4>
				<a class="btn btn-outline-secondary btn-sm" href="crafter">Torna a Crafter</a>
			</div>
			<div class="card-body" data-crafter-ui-reference>
				<p class="text-body-secondary mb-4">
					Anteprima degli elementi tipografici, dei controlli form e dei componenti Bootstrap condivisi nell'applicazione.
				</p>
				<!-- Separa il riferimento UI in gruppi piu leggibili per mostrare piu componenti Bootstrap. -->
				<div class="row g-4" id="crafter-bootstrap-reference">
					<div class="col-12 col-xl-4">
						<section class="h-100 border rounded-4 bg-body-tertiary p-4 d-flex flex-column gap-4">
							<div>
								<p class="text-uppercase text-body-secondary small fw-semibold mb-3">Tipografia e testo</p>
								<div class="d-flex flex-column gap-3">
									<div>
										<h1 class="mb-1">Titolo livello 1</h1>
										<p class="text-body-secondary small mb-0">Anteprima del tag <code>&lt;h1&gt;</code>.</p>
									</div>
									<div>
										<h2 class="mb-1">Titolo livello 2</h2>
										<p class="text-body-secondary small mb-0">Gerarchia tipografica per sezioni principali.</p>
									</div>
									<div>
										<h3 class="mb-1">Titolo livello 3</h3>
										<p class="text-body-secondary small mb-0">Titolo intermedio per card o pannelli.</p>
									</div>
									<div>
										<h4 class="mb-1">Titolo livello 4</h4>
										<p class="text-body-secondary small mb-0">Titolo compatto per intestazioni secondarie.</p>
									</div>
									<div>
										<h5 class="mb-1">Titolo livello 5</h5>
										<p class="text-body-secondary small mb-0">Titolo di dettaglio.</p>
									</div>
									<div>
										<h6 class="mb-1">Titolo livello 6</h6>
										<p class="text-body-secondary small mb-0">Livello minimo della scala heading.</p>
									</div>
								</div>
							</div>
							<div>
								<p class="mb-2">
									Questo è un paragrafo di esempio con un <a href="#crafter-bootstrap-reference">link standard</a>,
									testo in <strong>grassetto</strong> e testo in <em>corsivo</em>.
								</p>
								<ul class="mb-0 ps-3">
									<li>Primo elemento di lista non ordinata.</li>
									<li>Secondo elemento con contenuto più descrittivo.</li>
									<li>Terzo elemento per verificare spaziatura e marker.</li>
								</ul>
							</div>
							<div>
								<p class="text-uppercase text-body-secondary small fw-semibold mb-2">Lista ordinata</p>
								<ol class="mb-0 ps-3">
									<li>Passaggio iniziale.</li>
									<li>Passaggio intermedio.</li>
									<li>Passaggio conclusivo.</li>
								</ol>
							</div>
						</section>
					</div>
					<div class="col-12 col-xl-4">
						<section class="h-100 border rounded-4 bg-body-tertiary p-4 d-flex flex-column gap-4">
							<div>
								<p class="text-uppercase text-body-secondary small fw-semibold mb-3">Campi testuali</p>
								<div class="row g-3">
									<div class="col-12">
										<label class="form-label" for="crafterDemoText">Input testo</label>
										<input type="text" class="form-control" id="crafterDemoText" value="Contenuto di esempio">
									</div>
									<div class="col-sm-6">
										<label class="form-label" for="crafterDemoEmail">Input readonly</label>
										<input type="email" class="form-control" id="crafterDemoEmail" value="readonly@boilerplate.local" readonly>
									</div>
									<div class="col-sm-6">
										<label class="form-label" for="crafterDemoDate">Input data</label>
										<input type="date" class="form-control" id="crafterDemoDate" value="2026-04-15">
									</div>
									<div class="col-12">
										<label class="form-label" for="crafterDemoTextarea">Textarea</label>
										<textarea class="form-control" id="crafterDemoTextarea" rows="4">Una nota di esempio per verificare padding, bordo e contrasto del testo su più righe.</textarea>
									</div>
									<div class="col-12">
										<label class="form-label" for="crafterDemoSearch">Input group</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="fa-light fa-magnifying-glass" aria-hidden="true"></i>
											</span>
											<input
												type="search"
												class="form-control"
												id="crafterDemoSearch"
												placeholder="Cerca per codice, nome o riferimento"
											>
										</div>
									</div>
									<div class="col-12">
										<label class="form-label" for="crafterDemoDisabled">Input disabilitato</label>
										<input type="text" class="form-control" id="crafterDemoDisabled" value="Controllo non modificabile" disabled>
									</div>
								</div>
							</div>
						</section>
					</div>
					<div class="col-12 col-xl-4">
						<section class="h-100 border rounded-4 bg-body-tertiary p-4 d-flex flex-column gap-4">
							<div>
								<p class="text-uppercase text-body-secondary small fw-semibold mb-3">Selezione e azioni</p>
								<div class="row g-3">
									<div class="col-12">
										<label class="form-label" for="crafterDemoSelect">Select nativa</label>
										<select class="form-select" id="crafterDemoSelect">
											<option>Bozza</option>
											<option selected>Confermata</option>
											<option>Archiviata</option>
										</select>
									</div>
									<div class="col-12">
										<label class="form-label" for="crafterDemoTomSelect">Tom Select singola</label>
										<select class="form-select" id="crafterDemoTomSelect" data-crafter-tomselect>
											<option value="rome">Roma</option>
											<option value="milan" selected>Milano</option>
											<option value="turin">Torino</option>
											<option value="naples">Napoli</option>
										</select>
									</div>
									<div class="col-12">
										<label class="form-label" for="crafterDemoMultiTomSelect">Tom Select multipla</label>
										<select class="form-select" id="crafterDemoMultiTomSelect" data-crafter-tomselect multiple>
											<option value="cash" selected>Cassa</option>
											<option value="bank" selected>Banca</option>
											<option value="vendor">Fornitori</option>
											<option value="customer">Clienti</option>
										</select>
									</div>
									<div class="col-12">
										<label class="form-label" for="crafterDemoDisabledTomSelect">Tom Select disabilitata</label>
										<select class="form-select" id="crafterDemoDisabledTomSelect" data-crafter-tomselect disabled>
											<option value="draft">Da verificare</option>
											<option value="ready" selected>Pronta</option>
										</select>
									</div>
									<div class="col-12">
										<div class="d-flex flex-wrap gap-4">
											<div class="form-check">
												<input class="form-check-input" type="checkbox" value="1" id="crafterDemoCheckbox" checked>
												<label class="form-check-label" for="crafterDemoCheckbox">
													Checkbox selezionata
												</label>
											</div>
											<div class="form-check">
												<input class="form-check-input" type="radio" name="crafterDemoRadio" id="crafterDemoRadio" checked>
												<label class="form-check-label" for="crafterDemoRadio">
													Radio attiva
												</label>
											</div>
											<div class="form-check form-switch">
												<input class="form-check-input" type="checkbox" role="switch" id="crafterDemoSwitch" checked>
												<label class="form-check-label" for="crafterDemoSwitch">
													Switch
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</section>
					</div>
					<div class="col-12 col-xl-6">
						<section class="h-100 border rounded-4 bg-body-tertiary p-4 d-flex flex-column gap-4">
							<div>
								<p class="text-uppercase text-body-secondary small fw-semibold mb-3">Bottoni e badge</p>
								<div class="d-flex flex-column gap-4">
									<div>
										<p class="text-body-secondary small fw-semibold mb-2">Azioni principali</p>
										<div class="d-flex flex-wrap gap-2">
											<button type="button" class="btn btn-primary">Salva</button>
											<button type="button" class="btn btn-secondary">Annulla</button>
											<button type="button" class="btn btn-success">Conferma</button>
											<button type="button" class="btn btn-danger">Elimina</button>
											<button type="button" class="btn btn-warning">Segnala</button>
											<button type="button" class="btn btn-info">Notifica</button>
										</div>
									</div>
									<div>
										<p class="text-body-secondary small fw-semibold mb-2">Varianti secondarie e outlined</p>
										<div class="d-flex flex-wrap gap-2">
											<button type="button" class="btn btn-outline-primary">Consulta</button>
											<button type="button" class="btn btn-outline-secondary">Duplica</button>
											<button type="button" class="btn btn-outline-success">Approva</button>
											<button type="button" class="btn btn-outline-danger">Rimuovi</button>
											<button type="button" class="btn btn-outline-warning">Segnala</button>
											<button type="button" class="btn btn-outline-info">Notifica</button>
											<button type="button" class="btn btn-outline-dark">Chiudi</button>
											<button type="button" class="btn btn-light">Neutra</button>
											<button type="button" class="btn btn-dark">Bloccante</button>
											<button type="button" class="btn btn-link px-2">Azione testuale</button>
										</div>
									</div>
									<div>
										<p class="text-body-secondary small fw-semibold mb-2">Taglie e gruppi</p>
										<div class="d-flex flex-wrap align-items-center gap-2">
											<button type="button" class="btn btn-primary btn-sm">Compatto</button>
											<button type="button" class="btn btn-primary">Standard</button>
											<button type="button" class="btn btn-primary btn-lg">Grande</button>
											<div class="btn-group" role="group" aria-label="Azioni raggruppate di esempio">
												<button type="button" class="btn btn-outline-secondary">Precedente</button>
												<button type="button" class="btn btn-outline-secondary">Successiva</button>
												<button type="button" class="btn btn-outline-secondary">Chiudi</button>
											</div>
										</div>
									</div>
									<div>
										<p class="text-body-secondary small fw-semibold mb-2">Badge di stato</p>
										<div class="d-flex flex-wrap align-items-center gap-2">
											<span class="badge text-bg-primary">Nuova</span>
											<span class="badge text-bg-secondary">Bozza</span>
											<span class="badge text-bg-success">Confermata</span>
											<span class="badge text-bg-danger">Errore</span>
											<span class="badge text-bg-warning">Da verificare</span>
											<span class="badge text-bg-info">In lavorazione</span>
											<span class="badge text-bg-light border border-secondary-subtle text-body">Archiviata</span>
											<span class="badge rounded-pill text-bg-dark">Prioritaria</span>
										</div>
									</div>
								</div>
							</div>
						</section>
					</div>
					<div class="col-12 col-xl-6">
						<section class="h-100 border rounded-4 bg-body-tertiary p-4 d-flex flex-column gap-4">
							<div>
								<p class="text-uppercase text-body-secondary small fw-semibold mb-3">Alert e tooltip</p>
								<div class="d-flex flex-column gap-4">
									<div>
										<p class="text-body-secondary small fw-semibold mb-2">Alert contestuali</p>
										<div class="row g-3">
											<div class="col-12 col-md-6">
												<div class="alert alert-info h-100 mb-0" role="alert">
													<p class="fw-semibold mb-1">Informazione operativa</p>
													<p class="small mb-0">Usa questo tono per note utili che non bloccano il flusso.</p>
												</div>
											</div>
											<div class="col-12 col-md-6">
												<div class="alert alert-warning h-100 mb-0" role="alert">
													<p class="fw-semibold mb-1">Attenzione richiesta</p>
													<p class="small mb-0">Evidenzia controlli mancanti prima di confermare l'azione.</p>
												</div>
											</div>
											<div class="col-12 col-md-6">
												<div class="alert alert-success h-100 mb-0" role="alert">
													<p class="fw-semibold mb-1">Esito positivo</p>
													<p class="small mb-0">Conferma l'elaborazione completata con successo.</p>
												</div>
											</div>
											<div class="col-12 col-md-6">
												<div class="alert alert-danger h-100 mb-0" role="alert">
													<p class="fw-semibold mb-1">Blocco applicativo</p>
													<p class="small mb-0">Riserva questo stato a errori o condizioni che fermano il flusso.</p>
												</div>
											</div>
										</div>
									</div>
									<div>
										<p class="text-body-secondary small fw-semibold mb-2">Tooltip</p>
										<p class="small text-body-secondary mb-3">
											Usa tooltip per spiegazioni brevi che non meritano un alert o testo fisso in pagina.
										</p>
										<div class="d-flex flex-wrap align-items-center gap-2">
											<button
												type="button"
												class="btn btn-outline-secondary"
												data-bs-toggle="tooltip"
												data-bs-placement="top"
												data-bs-title="Apre il dettaglio completo senza cambiare la sezione corrente."
											>
												Dettaglio
											</button>
											<span
												class="badge rounded-pill bg-info bg-opacity-10 text-info-emphasis border border-info-subtle"
												tabindex="0"
												role="button"
												data-bs-toggle="tooltip"
												data-bs-placement="top"
												data-bs-title="Stato temporaneo assegnato durante la revisione operativa."
											>
												In revisione
											</span>
											<button
												type="button"
												class="btn btn-link px-0"
												data-bs-toggle="tooltip"
												data-bs-placement="right"
												data-bs-title="Link utile per mostrare chiarimenti contestuali senza occupare spazio."
											>
												Aiuto contestuale
											</button>
											<span
												class="d-inline-block"
												tabindex="0"
												data-bs-toggle="tooltip"
												data-bs-placement="bottom"
												data-bs-title="Il bottone resta disabilitato finche non completi i campi obbligatori."
											>
												<button type="button" class="btn btn-outline-secondary" disabled>Disabilitato</button>
											</span>
										</div>
									</div>
								</div>
							</div>
						</section>
					</div>
					<div class="col-12 col-xl-6">
						<section class="h-100 border rounded-4 bg-body-tertiary p-4 d-flex flex-column gap-4">
							<div>
								<p class="text-uppercase text-body-secondary small fw-semibold mb-3">Navigazione e liste</p>
								<div class="d-flex flex-column gap-4">
									<div>
										<p class="text-body-secondary small fw-semibold mb-2">Breadcrumb</p>
										<nav aria-label="Breadcrumb dimostrativa">
											<ol class="breadcrumb mb-0">
												<li class="breadcrumb-item"><a href="#crafter-bootstrap-reference">Dashboard</a></li>
												<li class="breadcrumb-item"><a href="#crafter-bootstrap-reference">Tesoreria</a></li>
												<li class="breadcrumb-item active" aria-current="page">Piano di cassa</li>
											</ol>
										</nav>
									</div>
									<div>
										<p class="text-body-secondary small fw-semibold mb-2">Paginazione</p>
										<nav aria-label="Paginazione dimostrativa">
											<ul class="pagination mb-0 flex-wrap">
												<li class="page-item disabled" aria-disabled="true">
													<span class="page-link">Precedente</span>
												</li>
												<li class="page-item active" aria-current="page">
													<span class="page-link">1</span>
												</li>
												<li class="page-item"><a class="page-link" href="#crafter-bootstrap-reference">2</a></li>
												<li class="page-item"><a class="page-link" href="#crafter-bootstrap-reference">3</a></li>
												<li class="page-item">
													<a class="page-link" href="#crafter-bootstrap-reference">Successiva</a>
												</li>
											</ul>
										</nav>
									</div>
									<div>
										<p class="text-body-secondary small fw-semibold mb-2">List group</p>
										<div class="list-group">
											<button type="button" class="list-group-item list-group-item-action active d-flex justify-content-between align-items-center" aria-current="true">
												Riconciliazioni aperte
												<span class="badge text-bg-light">12</span>
											</button>
											<button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
												Documenti da verificare
												<span class="badge text-bg-warning">4</span>
											</button>
											<button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
												Incassi pianificati
												<span class="badge text-bg-info">9</span>
											</button>
											<button type="button" class="list-group-item list-group-item-action disabled" disabled>
												Voce disabilitata
											</button>
										</div>
									</div>
								</div>
							</div>
						</section>
					</div>
					<div class="col-12 col-xl-6">
						<section class="h-100 border rounded-4 bg-body-tertiary p-4 d-flex flex-column gap-4">
							<div>
								<p class="text-uppercase text-body-secondary small fw-semibold mb-3">Schede e pannelli</p>
								<div class="d-flex flex-column gap-4">
									<div>
										<p class="text-body-secondary small fw-semibold mb-2">Tabs</p>
										<ul class="nav nav-tabs" id="crafterDemoTabs" role="tablist">
											<li class="nav-item" role="presentation">
												<button
													class="nav-link active"
													id="crafterDemoSummaryTab"
													data-bs-toggle="tab"
													data-bs-target="#crafterDemoSummaryPane"
													type="button"
													role="tab"
													aria-controls="crafterDemoSummaryPane"
													aria-selected="true"
												>
													Riepilogo
												</button>
											</li>
											<li class="nav-item" role="presentation">
												<button
													class="nav-link"
													id="crafterDemoDetailsTab"
													data-bs-toggle="tab"
													data-bs-target="#crafterDemoDetailsPane"
													type="button"
													role="tab"
													aria-controls="crafterDemoDetailsPane"
													aria-selected="false"
												>
													Dettaglio
												</button>
											</li>
											<li class="nav-item" role="presentation">
												<button
													class="nav-link"
													id="crafterDemoHistoryTab"
													data-bs-toggle="tab"
													data-bs-target="#crafterDemoHistoryPane"
													type="button"
													role="tab"
													aria-controls="crafterDemoHistoryPane"
													aria-selected="false"
												>
													Storico
												</button>
											</li>
										</ul>
										<div class="tab-content border border-top-0 rounded-bottom-4 bg-body-tertiary p-3" id="crafterDemoTabsContent">
											<div
												class="tab-pane fade show active"
												id="crafterDemoSummaryPane"
												role="tabpanel"
												aria-labelledby="crafterDemoSummaryTab"
												tabindex="0"
											>
												<p class="mb-0 small">Usa le tabs per spezzare contenuti paralleli senza aprire nuove pagine.</p>
											</div>
											<div
												class="tab-pane fade"
												id="crafterDemoDetailsPane"
												role="tabpanel"
												aria-labelledby="crafterDemoDetailsTab"
												tabindex="0"
											>
												<p class="mb-0 small">Qui puo vivere il dettaglio operativo con più campi o tabelle secondarie.</p>
											</div>
											<div
												class="tab-pane fade"
												id="crafterDemoHistoryPane"
												role="tabpanel"
												aria-labelledby="crafterDemoHistoryTab"
												tabindex="0"
											>
												<p class="mb-0 small">Questa scheda è adatta a note, audit trail o cronologia cambi.</p>
											</div>
										</div>
									</div>
									<div>
										<p class="text-body-secondary small fw-semibold mb-2">Accordion</p>
										<div class="accordion" id="crafterDemoAccordion">
											<div class="accordion-item">
												<h2 class="accordion-header">
													<button
														class="accordion-button"
														type="button"
														data-bs-toggle="collapse"
														data-bs-target="#crafterDemoAccordionOne"
														aria-expanded="true"
														aria-controls="crafterDemoAccordionOne"
													>
														Filtri attivi
													</button>
												</h2>
												<div
													id="crafterDemoAccordionOne"
													class="accordion-collapse collapse show"
													data-bs-parent="#crafterDemoAccordion"
												>
													<div class="accordion-body small">
														Raggruppa opzioni secondarie quando vuoi alleggerire il primo impatto della pagina.
													</div>
												</div>
											</div>
											<div class="accordion-item">
												<h2 class="accordion-header">
													<button
														class="accordion-button collapsed"
														type="button"
														data-bs-toggle="collapse"
														data-bs-target="#crafterDemoAccordionTwo"
														aria-expanded="false"
														aria-controls="crafterDemoAccordionTwo"
													>
														Parametri avanzati
													</button>
												</h2>
												<div
													id="crafterDemoAccordionTwo"
													class="accordion-collapse collapse"
													data-bs-parent="#crafterDemoAccordion"
												>
													<div class="accordion-body small">
														Apri solo ciò che serve davvero, mantenendo i contenuti meno frequenti in secondo piano.
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</section>
					</div>
					<div class="col-12 col-xl-6">
						<section class="h-100 border rounded-4 bg-body-tertiary p-4 d-flex flex-column gap-4">
							<div>
								<p class="text-uppercase text-body-secondary small fw-semibold mb-3">Dropdown, modal e toast</p>
								<div class="d-flex flex-column gap-4">
									<div>
										<p class="text-body-secondary small fw-semibold mb-2">Dropdown</p>
										<div class="d-flex flex-wrap gap-2">
											<div class="dropdown">
												<button
													class="btn btn-outline-primary dropdown-toggle"
													type="button"
													data-bs-toggle="dropdown"
													aria-expanded="false"
												>
													Azioni rapide
												</button>
												<ul class="dropdown-menu">
													<li><a class="dropdown-item" href="#crafter-bootstrap-reference">Apri dettaglio</a></li>
													<li><a class="dropdown-item" href="#crafter-bootstrap-reference">Duplica</a></li>
													<li><hr class="dropdown-divider"></li>
													<li><a class="dropdown-item text-danger" href="#crafter-bootstrap-reference">Annulla movimento</a></li>
												</ul>
											</div>
											<div class="btn-group">
												<button type="button" class="btn btn-secondary">Esporta</button>
												<button
													type="button"
													class="btn btn-secondary dropdown-toggle dropdown-toggle-split"
													data-bs-toggle="dropdown"
													aria-expanded="false"
												>
													<span class="visually-hidden">Apri menu esportazione</span>
												</button>
												<ul class="dropdown-menu">
													<li><a class="dropdown-item" href="#crafter-bootstrap-reference">PDF</a></li>
													<li><a class="dropdown-item" href="#crafter-bootstrap-reference">Excel</a></li>
													<li><a class="dropdown-item" href="#crafter-bootstrap-reference">CSV</a></li>
												</ul>
											</div>
										</div>
									</div>
									<div>
										<p class="text-body-secondary small fw-semibold mb-2">Modal</p>
										<div class="d-flex flex-wrap gap-2">
											<button
												type="button"
												class="btn btn-primary"
												data-bs-toggle="modal"
												data-bs-target="#crafterDemoModal"
											>
												Apri modal
											</button>
											<button
												type="button"
												class="btn btn-outline-success"
												data-crafter-toast-trigger
												data-crafter-toast-target="#crafterDemoToast"
											>
												Mostra toast
											</button>
										</div>
									</div>
									<div>
										<p class="text-body-secondary small fw-semibold mb-2">Toast</p>
										<p class="small mb-0 text-body-secondary">
											Il bottone sopra mostra un toast dimostrativo senza sporcare il layout con notifiche sempre aperte.
										</p>
									</div>
								</div>
							</div>
						</section>
					</div>
					<div class="col-12 col-xl-6">
						<section class="h-100 border rounded-4 bg-body-tertiary p-4 d-flex flex-column gap-4">
							<div>
								<p class="text-uppercase text-body-secondary small fw-semibold mb-3">Avanzamento e attesa</p>
								<div class="d-flex flex-column gap-4">
									<div>
										<p class="text-body-secondary small fw-semibold mb-2">Progress</p>
										<div class="d-flex flex-column gap-2">
											<div class="progress" role="progressbar" aria-label="Progress semplice" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
												<div class="progress-bar" style="width: 25%">25%</div>
											</div>
											<div class="progress" role="progressbar" aria-label="Progress con stato warning" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100">
												<div class="progress-bar bg-warning text-dark" style="width: 55%">55%</div>
											</div>
											<div class="progress-stacked">
												<div class="progress" role="progressbar" aria-label="Progress verificato" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
													<div class="progress-bar bg-success" style="width: 40%"></div>
												</div>
												<div class="progress" role="progressbar" aria-label="Progress in coda" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100">
													<div class="progress-bar bg-info" style="width: 35%"></div>
												</div>
												<div class="progress" role="progressbar" aria-label="Progress con errore" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
													<div class="progress-bar bg-danger" style="width: 25%"></div>
												</div>
											</div>
										</div>
									</div>
									<div>
										<p class="text-body-secondary small fw-semibold mb-2">Spinner</p>
										<div class="d-flex flex-wrap align-items-center gap-3">
											<div class="spinner-border text-primary" role="status">
												<span class="visually-hidden">Caricamento</span>
											</div>
											<div class="spinner-border spinner-border-sm text-secondary" role="status">
												<span class="visually-hidden">Caricamento</span>
											</div>
											<div class="spinner-grow text-success" role="status">
												<span class="visually-hidden">Caricamento</span>
											</div>
											<button class="btn btn-outline-secondary" type="button" disabled>
												<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
												Elaborazione
											</button>
										</div>
									</div>
								</div>
							</div>
						</section>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Mantiene il toast fuori dal flusso della griglia per simulare una notifica applicativa reale. -->
<div class="toast-container position-fixed top-0 end-0 p-3">
	<div
		id="crafterDemoToast"
		class="toast"
		role="status"
		aria-live="polite"
		aria-atomic="true"
		data-bs-delay="3500"
	>
		<div class="toast-header">
			<span class="badge text-bg-success me-2">OK</span>
			<strong class="me-auto">Operazione completata</strong>
			<small>adesso</small>
			<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Chiudi"></button>
		</div>
		<div class="toast-body">
			Il toast è utile per conferme rapide che non richiedono una modale o un redirect.
		</div>
	</div>
</div>
<div class="modal fade" id="crafterDemoModal" tabindex="-1" aria-labelledby="crafterDemoModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="crafterDemoModalLabel">Conferma dimostrativa</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
			</div>
			<div class="modal-body">
				<p class="mb-2">Usa la modal per bloccare temporaneamente il flusso quando serve una scelta esplicita.</p>
				<p class="mb-0 small text-body-secondary">
					Per testi informativi brevi o conferme non bloccanti preferisci toast, alert o tooltip.
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Chiudi</button>
				<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Conferma</button>
			</div>
		</div>
	</div>
</div>
