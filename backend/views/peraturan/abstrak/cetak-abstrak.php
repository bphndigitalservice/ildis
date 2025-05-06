<html>

<body>

    <p><b><?= $model->subjek ?></b></p>
    <p><b><?= $model->tahun ?></b></p>
    <p><b><?= $model->singkatan ?></b></p>
    <p><b><?= $model->judul ?></b></p>


    <table style="width:100%">
        <tr>
            <td rowspan="6" style="width:20%" valign="top">&nbsp;ABSTRAK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
        </tr>
        <tr>
            <td style="width:3%" valign="top">-</td>
            <td style="width:90%"><?= $model->isi_abstrak_1 ?></td>
        </tr>
        <tr>
            <td valign="top">-</td>
            <td> <?= $model->isi_abstrak_2 ?></td>
        </tr>
        <tr>
            <td valign="top">-</td>
            <td> <?= $model->isi_abstrak_3 ?> </td>
        </tr>
    </table>
    <br>
    
     <table style="width:100%">
        <tr>
            <td rowspan="6" style="width:20%" valign="top">&nbsp;CATATAN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
        </tr>
        <tr>
            <td style="width:3%" valign="top">-</td>
            <td style="width:90%"><?= $model->catatan_1 ?></td>
        </tr>
        <tr>
            <td valign="top">-</td>
            <td> <?= $model->catatan_2 ?></td>
        </tr>
        <tr>
            <td valign="top">-</td>
            <td> <?= $model->catatan_3 ?> </td>
        </tr>
        <tr>
            <td valign="top">-</td>
            <td> <?= $model->catatan_4 ?></td>
        </tr>
        <tr>
            <td valign="top">-</td>
            <td> <?= $model->catatan_5 ?> </td>
        </tr>

    </table>

</body>

</html>
