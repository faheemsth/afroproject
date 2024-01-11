
<div class="row">
    <div class="col-md-6">
        Teacher: <?= $this->session->userdata('name') ?>
    </div>

    <div class="col-md-6">
        Date: <?= date('d-m-Y') ?>
    </div>

    <div class="col-md-4">
        Class: <?= $class->classes ?>
    </div>

    <div class="col-md-4">
        Section: <?= $section->section ?>
    </div>

    <div class="col-md-6">
        Subject: <?= $subject->subject ?>
    </div>
</div>

<div class="row">
    <div class="col-12">
    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
    <thead>
        <th>Student Name</th>
        <th>Status</th>
    </thead>
    <tbody>
        <?php foreach($attendance as $key => $att) {
                $id = trim($key, 'attendance');
            ?>
            <tr>
                <td><?= $students[$id] ?></td>
                <td><?= $att ?></td>
            </tr>
            <?php } ?>
    </tbody>
    </table>
    </div>
</div>