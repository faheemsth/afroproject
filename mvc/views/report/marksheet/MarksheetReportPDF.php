<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
</head>

<body>
	<div class="mainmarksheetreport">
		<div class="col-sm-12">
			<?= reportheader($siteinfos, $schoolyearsessionobj, true) ?>
		</div>
		<h3> <?= $this->lang->line('marksheetreport_report_for') ?> - <?= $this->lang->line('marksheetreport_marksheet'); ?></h3>
		<?php if ($classesID > 0) { ?>
			<div class="col-sm-12">
				<h5 class="pull-left"><?= $this->lang->line('marksheetreport_class') ?> : <?= isset($classes[$classesID]) ? $classes[$classesID] : '' ?></h5>
				<h5 class="pull-right"><?= $this->lang->line('marksheetreport_section') ?> : <?= isset($sections[$sectionID]) ? $sections[$sectionID] : $this->lang->line('marksheetreport_all_section') ?></h5>
			</div>
		<?php } ?>
		<div class="col-sm-12">
			<div class="marksheetrult">
				<?php if (customCompute($studentlist)) { ?>
					<div id="hide-table" style="overflow-x: auto;">
						<table class="attendance_table">
							<thead>
								<tr height=21>
									<td colspan=5 align="right">Student Information</td>

									<?php foreach ($subjects as $sub) { ?>
										<td colspan=2 align="center"><?= $sub->subject ?></td>
									<?php } ?>

									<td colspan=2>Summary</td>
								</tr>

								<tr>
									<td>SN</td>
									<td>Roll No </td>
									<td>Student Name </td>
									<td> F/Name</td>
									<td>Registration No. </td>
									<?php foreach ($subjects as $sub) { ?>
										<td>Total</td>
										<td>Obtained</td>
									<?php } ?>
									<td>Total</td>
									<td>Obtained</td>
								</tr>
							</thead>
							<tbody>

								<?php foreach ($studentlist as $key => $student) { ?>

									<tr>
										<td><?= $key + 1  ?></td>
										<td><?= $student->srroll ?></td>
										<td><?= $student->srname ?></td>
										<td><?= isset($parents[$student->parentID]) ? $parents[$student->parentID] : ' ' ?></td>
										<td><?= $student->registerNO ?></td>


										<?php $total_sub_mark = 0;
										$total_obtain_mark = 0;
										foreach ($subjects as $sub) {
											$total_sub_mark += $student_totals[$student->srstudentID][$sub->subjectID];
											$total_obtain_mark += isset($marks[$student->srstudentID][$sub->subjectID]) ? array_sum($marks[$student->srstudentID][$sub->subjectID]) : 0;
										?>
											<td><?= $student_totals[$student->srstudentID][$sub->subjectID] ?></td>
											<td><?= isset($marks[$student->srstudentID][$sub->subjectID]) ? array_sum($marks[$student->srstudentID][$sub->subjectID]) : 0 ?></td>
										<?php } ?>

										<td><?= $total_sub_mark ?></td>
										<td><?= $total_obtain_mark ?></td>
									</tr>


								<?php } ?>
							</tbody>
						</table>
					</div>
				<?php } else { ?>
					<div class="callout callout-danger">
						<p><b class="text-info"><?= $this->lang->line('attendanceoverviewreport_data_not_found') ?></b></p>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="col-sm-12 text-center report-footer">
		<?= reportfooter($siteinfos, $schoolyearsessionobj, true) ?>
	</div>
	</div>
</body>

</html>