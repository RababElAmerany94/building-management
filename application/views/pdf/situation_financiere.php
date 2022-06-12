<?php
$montant_sum = 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Situation financière</title>
    <link rel="stylesheet" href="assets/css/paper.min.css"/>
    <link rel="stylesheet" href="assets/css/print.css"/>
</head>
<body>
<header>
    <h2 class="title">Situation financière</h2>
	<?php if (isset($client_name)) { ?>
        <div style="font-size:18px;vertical-align:bottom;"><strong>Client: </strong><?= $client_name ?></div>
	<?php } ?>
    <br><br><br>
</header>
<table border="1">
    <tr>
        <td style="text-align: left"><b>Num d'Ordre:</b> <?php echo $situation['Num_Ordre'] ?></td>
        <td style="text-align: left"><b>Adresse:</b> <?php echo $situation['Adresse'] ?></td>
    </tr>
    <tr>
        <td style="text-align: left"><b>Projet:</b> <?php echo $situation['Projet'] ?></td>
        <td style="text-align: left"><b>Type:</b> <?php echo $situation['Type'] ?></td>
    </tr>
    <tr>
        <td style="text-align: left"><b>Num Bien:</b> <?php echo $situation['Num_Bien'] ?></td>
        <td style="text-align: left"><b>Num Titre Fonctier:</b> <?php echo $situation['Num_Titre_Foncier'] ?></td>
    </tr>
    <tr>
        <td style="text-align: left"><b>Prix:</b> <?php echo $situation['Prix'] ?></td>
        <td style="text-align: left"><b>Avance:</b> <?php echo $situation['Avance'] ?> <span
                    style="margin-left:10px;"><b>Reste:</b> <?php echo $situation['Reste'] ?></span></td>
    </tr>
</table>
<br>
<hr>
<br>
<h1>Echeances</h1>
<table class="table table-bordered table-hover">
    <thead>
    <tr>
        <th>Date Paiement Echeance</th>
        <th>Montant Paiement</th>
        <th>Partie Versante</th>
        <th>Payée?</th>
    </tr>
    </thead>
    <tbody>
	<?php for ($i = 0; $i < count($echeances); $i++) {
		$echeance = $echeances[$i];
		?>
        <tr>
            <td><?php echo $echeance['Date_echeance'] ?></td>
            <td><?php echo $echeance['Montant'] ?></td>
            <td><?php echo $echeance['Partie_Versante'] ?></td>
            <td><?php echo $echeance['Payée'] ?></td>
        </tr>
	<?php } ?>
    </tbody>
</table>
</body>
</html>
