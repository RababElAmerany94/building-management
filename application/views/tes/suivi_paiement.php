<!-- First Section one Column -->
<div class="x_panel">
    <div class="row">
        <div class="col-xs-12 row">
			<?= form_open('SuiviPaiement/generate_report') ?>

            <div class="form-group col-md-4 col-xs-12">
                <label>Client: </label>

                <select id="client-select" class="chosen-select form-control clients" name="id_client">
                    <option value="0" selected>Tous les Clients</option>
					<?php foreach ($clients as $client) { ?>
                        <option value="<?php echo $client['Id_Client']; ?>"><?php echo $client['Id_Client'] . ' - ' . $client['Nom'] . ' ' . $client['Prenom'] ?></option>;
					<?php } ?>
                </select>
            </div>

            <div class="form-group col-md-4 col-xs-12 ventes">
                <label>Vente: </label>

                <select id="vente-select" class="chosen-select form-control" name="id_vente">
                    <option value="0" selected>Tous les ventes du client</option>
                </select>
            </div>

            <div class="form-group col-xs-12 col-md-4">
				<?= form_submit(array('name' => 'submit', 'id' => 'generate_report', 'disabled' => 'disabled', 'value' => 'Générer PDF', 'class' => 'btn btn-primary', 'style' => 'margin-top:25px;')) ?>
            </div>

			<?= form_close() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 row echeances">
            <table class="table table-bordered table-hover" style="margin-top:25px;" id="echeances-table">
            </table>
        </div>
        <div class="col-xs-12 row">
	<span style="color: #FF0000;">&block; Non Pay&eacute;e</span>
	<span style="color: #AAAAAA;">&block; Prochaine</span>
	<span style="color: #00FF00;">&block; Pay&eacute;e</span>
    </div>
</div>
<!-- /First Section one Column -->
