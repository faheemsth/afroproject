<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <div class="profileArea">
        <?php featureheader($siteinfos);?>
        <div class="mainArea">

<table style="padding-bottom: 120px;">
    <tbody>
        <tr>
            <td style="padding: 10px;">Name of the Student: <?=$student->srname?></td>
        </tr>
    </tbody>
    <tbody>
        <tr>
            <td style="padding: 10px;">Address: <?=$student->address?></td>
        </tr>
    </tbody>
    <tbody>
        <tr>
            <td style="padding: 10px;">Program: <?=customCompute($class) ? $class->classes : ''?></td>
             <td style="padding: 10px;">Semester: <?=customCompute($section) ? $section->section : ''?> </td>
        </tr>
    </tbody>
    <tbody>
        <tr>
           
        </tr>
    </tbody>
    <tbody>
        <tr>
            <td style="padding: 10px;">Contact No:<?=$student->phone?></td>
            <td style="padding: 10px;">Student Signature: ______________________</td>
        </tr>
    </tbody>
    
    <tbody>
        <tr>
            <td style="padding: 10px;">Total Pending Invoices <?php echo $unpaidcount->totalcount;?></td>
        </tr>
    </tbody>
</table>

<table style="margin: auto; width: 50%; padding-bottom: 200px;">
    <tbody>
        <tr>
            <td>Approved By: ………………………………</td>
            <td>Date: ……………………………</td>
        </tr>
    </tbody>
</table></div>
    </div>
    <?php featurefooter($siteinfos);?>
</body>
</html>