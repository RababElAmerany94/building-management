<!-- First Section one Column -->
<div class="x_p anel">
    <div class="row">
        <div class="col-xs-12 row">
			<?= form_open('SituationFinanciere/generate_report') ?>

            <div class="form-group col-md-4 col-xs-12">
                <label>Client: </label>

                <select id="client-select" class="chosen-select form-control clients" name="id_client">
                    <option value="0" selected disabled>Tous les Clients</option>
					<?php foreach ($clients as $client) { ?>
                        <option value="<?php echo $client['Id_Client']; ?>"><?php echo $client['Id_Client'] . ' - ' . $client['Nom'] . ' ' . $client['Prenom'] ?></option>;
					<?php } ?>
                </select>
            </div>

            <div class="form-group col-md-4 col-xs-12 biens">
                <label>Bien: </label>

                <select id="bien-select" class="chosen-select form-control" name="id_bien">
                    <option value="0" selected disabled>Tous les biens</option>
					<?php foreach ($biens as $bien) { ?>
                        <option value="<?php echo $bien['Id_Bien']; ?>"><?php echo $bien['Nom_Projet'] . ' - ' . $bien['Num_Bien'] ?></option>;
					<?php } ?>
                </select>
            </div>

            <div class="form-group col-xs-12 col-md-4">
				<?= form_submit(['name' => 'submit', 'id' => 'generate_report', 'disabled' => 'disabled', 'value' => 'Générer PDF', 'class' => 'btn btn-primary', 'style' => 'margin-top:25px;']) ?>
            </div>
			
			<?= form_close() ?>
        </div>
    </div>

    <div id="situation" style="display:none">
        <div class="row">
            <div class="col-xs-12 row">
                <table class="table table-bordered table-hover" style="margin-top:25px;" id="situation-table">
                </table>
            </div>
        </div>
    
        <div class="row">
            <div class="col-xs-12">
                <h1>Echeances</h1>
            </div>
            
            <div class="col-xs-12echeances">
                <table class="table table-bordered table-hover" style="margin-top:25px;" id="echeances-table">
                    <thead>
                    <tr>
                        <th>Date Paiement Echeance</th>
                        <th>Montant Paiement</th>
                        <th>Partie Versante</th>
                        <th>Payée?</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /First Section one Column -->
