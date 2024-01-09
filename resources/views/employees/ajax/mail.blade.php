<style>

table, th, td {
  border: 1px solid black;
  padding-left:2px;
  padding-right:2px;
  text-align:center;
}

</style>

        <table>
        <thead>
            <tr>
                <th>Employee Code</th>
                <th>Employee Name</th>
                <th>Logdate</th>
                <th>Total Working Hours</th>
                <th>Break Time</th>
            </tr>
        </thead> 
        <tbody>
        <?php  
            
            foreach($essllog as $value) 
            {  
                
        ?>
            <tr>
                <td><?= $value->empcode ?></td>
                <td><?= $value->name ?></td>
                <td><?= $value->logdate ?></td>
                <td><?= $value->total_working_time ?></td>
                <td><?= $value->total_break_time ?></td>
            </tr>

            <?php
						
            }

            ?>
        </tbody>   
    </table>
    














