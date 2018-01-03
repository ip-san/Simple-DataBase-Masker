<table>
    <thead>
    <th>
        <?=__('元データ')?>
    </th>
    <th>
        <span>→</span>
    </th>
    <th>
        <?=__('置き換え後イメージ')?>
    </th>
    </thead>
    <tbody>
        <?php foreach ($datas as $row): ?>
            <tr>
                <td>
                    <?=$row['before_value']?>
                </td>
                <td>
                    <span>→</span>
                </td>
                <td>
                    <?=$row['after_value']?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>  
</table>