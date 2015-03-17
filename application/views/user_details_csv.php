<table border="1" align="center">
    <tbody>
           <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Address</th>
           </tr>
        <?php
        for($i=0;$i<count($user_details);$i++) {    
            echo "<tr>";
            echo "<td align='left'>".$user_details[$i]['first_name']."</td>";
            echo "<td align='left'>".$user_details[$i]['last_name']."</td>";
            echo "<td align='left'>".$user_details[$i]['address']."</td>";
            echo "</tr>";
            }
        ?>
    </tbody>
</table>