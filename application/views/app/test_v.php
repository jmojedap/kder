<style>
	.day{
		width: 15px;
		height: 15px;
		display: block;
		border: 1px solid #666;
		text-align: center;
		margin-bottom: 1px;
		font-size: 0.5em;
		float: left;
	}
</style>

<?php
	$week_day = 1;
	$date_1 = '2019-01-29';
	$date = '2019-01-29';
?>

<div class="calendar">
	<?php foreach ( $days->result() as $period ) { ?>
		<?php if ( $week_day != $period->week_day ) { ?>
			<br>
		<?php } ?>
		<div class="day" title="<?php echo $period->id ?>">
			<?php echo $period->day ?>
		</div>

		<?php
			$week_day = $period->week_day;
		?>
	<?php } ?>
</div>

<table class="table">
	<?php for ( $i=0; $i < 7; $i++ ) { ?>
		<tr>
			<?php for ( $j=0; $j < 21; $j+=7 ) { ?>
				<?php $sum = $i + $j ?>
				<td>
					<?php echo $date; ?>
				</td>
				<?php
					$mktime = strtotime(date("Y-m-d", strtotime($date)) . " +{$sum} days");
					$date = date('Y-m-d', $mktime);
				?>		
			<?php } ?>
		</tr>
		<?php
			$mktime = strtotime(date("Y-m-d", strtotime($date_1)) . " +{$i} days");
			$date = date('Y-m-d', $mktime);
		?>
	<?php } ?>
</table>

<a v-bind:href="`<?php echo base_url("destino") ?>` + elemento" class="clase">
	contenido
</a>
