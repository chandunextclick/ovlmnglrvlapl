

        <table style="border-collapse: collapse;">
        <thead>
            <tr style="text-align: center;">
                <th style="padding-left: 2px; padding-right: 2px;border: 1px solid black;">Employee Code</th>
                <th style="padding-left: 2px; padding-right: 2px;border: 1px solid black;">Employee Name</th>
                <th style="padding-left: 2px; padding-right: 2px;border: 1px solid black;">Logdate</th>
                <th style="padding-left: 2px; padding-right: 2px;border: 1px solid black;">Total Working Hours</th>
                <th style="padding-left: 2px; padding-right: 2px;border: 1px solid black;">Break Time</th>
            </tr>
        </thead> 
        <tbody>
        <?php  
            
            foreach($essllog as $value) 
            {  
                
        ?>
            <tr style="border: 1px solid black; text-align: center;">
                <td style="padding-left: 2px; padding-right: 2px;border: 1px solid black;"><?= $value->empcode ?></td>
                <td style="padding-left: 2px; padding-right: 2px;border: 1px solid black;"><?= $value->name ?></td>
                <td style="padding-left: 2px; padding-right: 2px;border: 1px solid black;"><?= $value->logdate ?></td>
                <td style="padding-left: 2px; padding-right: 2px;border: 1px solid black;"><?= $value->total_working_time ?></td>
                <td style="padding-left: 2px; padding-right: 2px;border: 1px solid black;"><?= $value->total_break_time ?></td>
            </tr>

            <?php
						
            }

            ?>
        </tbody>   
    </table>
    














