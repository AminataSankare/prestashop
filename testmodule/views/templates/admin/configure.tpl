
<div class="panel">
	<div class="row moduleconfig-header">
		<div class="col-xs-5 text-right">
			<img src="{$module_dir|escape:'html':'UTF-8'}views/img/logo.jpg" />
		</div>
		<div class="col-xs-7 text-left">
			<h2>{l s='Lorem' mod='testmodule'}</h2>
			<h4>{l s='Lorem ipsum dolor' mod='testmodule'}</h4>
		</div>
	</div>

	<hr />

	<div class="moduleconfig-content">
		<div class="row">
			<div class="col-xs-12">
     			<form method="post" name="cronform" id="cronform" action="{$form_url|escape:'htmlall':'UTF-8'|replace:'&amp;':'&'}">
					<div class="form-group">
						<label for="day_before">{l s='Nombre de jour pour envoi mail' mod='testmodule' }</label>
						<input type="number" class="form-control" id="day_before">
					</div>
					<div class="form-group">
						<label for="duration">{l s='Durée offre' mod='testmodule' }</label>
						<input type="number" class="form-control" id="duration">
					</div>
					<button type="submit" class="btn btn-primary">{l s='Valider' mod='testmodule' }</button>
				</form>

				<br />

				<p class="text-center">
					<strong>
						<a href="http://www.prestashop.com" target="_blank" title="Lorem ipsum dolor">
							{l s='Lorem ipsum dolor' mod='testmodule' }
						</a>
					</strong>
				</p>
			</div>
		</div>
	</div>
</div>
