<?php
$montant_sum = 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="assets/css/paper.min.css"/>
    <link rel="stylesheet" href="assets/css/print.css"/>
</head>
<body>
<header>
    <div class="row">
        <div class="row_left">
			<?php echo $company_name ?>
        </div>
        <div class="row_right">
			<?php echo date('d/m/Y') ?>
        </div>
    </div>

    <br><br>

    <h2 class="title"><?php echo $title ?></h2>
	<?php if (isset($client_name)) { ?>
        <div style="font-size:18px;vertical-align:bottom;"><strong>Client: </strong><?= $client_name ?></div>
	<?php } ?>
    <br><br><br>
</header>
<table border="1">
    <tr>
        <th style="text-align:center;">Mois<br><br>Annee</th>
		<?php for ($i = 1; $i < 13; $i++): ?>
            <th style="text-align:center;"><?php echo $i ?></th>
		<?php endfor; ?>
    </tr>

	<?php foreach ($result['echeances'] as $year => $echeances): ?>
        <tr>
            <td><?php echo $year ?></td>

			<?php foreach ($echeances as $month => $echeance_items): ?>
                <td>
					<?php
					foreach ($echeance_items as $item):
						if (count($echeance_items) > 1 && $item['montant'] == '--') continue;
						?>
                        <div style="background-color:<?php echo $item['color'] ?>;"><?php echo $item['montant'] ?></div>
					<?php endforeach; ?>
                </td>
			<?php endforeach; ?>
        </tr>
	<?php endforeach; ?>
</table>

<script type="text/php">
    if (isset($pdf)) {
        $x = $pdf->get_width() - 85;
        $y = $pdf->get_height()-35;
        $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
        $font = null;
        $size = 10;
        $color = array(0,0,0);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
    }
</script>
</body>
</html>
