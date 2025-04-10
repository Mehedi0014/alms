<?php
    require_once ('process/dbh.php');
    $date = filter_var($_POST['date_search'], FILTER_SANITIZE_STRING);
    $year1 = explode('-',$date);
    $year = $year1[0];
    $i = 0;
    $sql="SELECT 
            a.`id`,
            CONCAT(a.`firstName`, ' ', a.`lastName`) AS `name`,
            COALESCE(MAX(CASE WHEN (bb.`type` = 'Sick') THEN bb.`diff` END), 0) AS `Sick`,
            COALESCE(MAX(CASE WHEN (bb.`type` = 'Casual') THEN bb.`diff` END), 0) AS `Casual`,
            COALESCE((MAX(CASE WHEN (bb.`type` = 'Halfday') THEN bb.`diff` END) * 0.5), 0) AS `Halfday`,
            COALESCE(MAX(CASE WHEN (bb.`type` = 'Public') THEN bb.`diff` END), 0) AS `Public`,
            COALESCE(MAX(CASE WHEN (bb.`type` = 'Special') THEN bb.`diff` END), 0) AS `Special`,
            COALESCE(MAX(CASE WHEN (bb.`type` = 'Earned') THEN bb.`diff` END), 0) AS `Earned`
        FROM `employee` a
        LEFT JOIN (
            SELECT b.`id`, SUM(DATEDIFF(b.`end`, b.`start`)+1) AS `diff`, b.`type`, b.`status` FROM `employee_leave` b WHERE YEAR(`start`) = '$year' AND b.`status` = 'Approved' GROUP BY b.`id`,b.`type`
            /*SELECT b.`id`, SUM(DATEDIFF(b.`end`, b.`start`)+1) AS `diff`, b.`type`, b.`status` FROM `employee_leave` b WHERE DATE_FORMAT(`start` , 'Y') = DATE_FORMAT(CURRENT_DATE, 'Y') AND b.`status` = 'Approved' GROUP BY b.`id`,b.`type`*/
        ) bb 
        ON a.`id` = bb.`id` GROUP BY a.`id`";
        $result = $conn->prepare($sql);
        $result->execute();
        $employee = $result->fetch();
        $return_arr = array();
        while ($employee = $result->fetch()) {
            $i++;
            $total_leave = $employee['Sick'] + $employee['Halfday'] + $employee['Casual'] + $employee['Special'] + $employee['Earned'] + $employee['Public'];
            $return_arr[] = array(
                'id' => $i,
                'name' => $employee['name'],
                'sick' => $employee['Sick'],
                'halfday' => $employee['Halfday'],
                'casual' => $employee['Casual'],
                'special' => $employee['Special'],
                'earned' => $employee['Earned'],
                'public' => $employee['Public'],
                'total' => $total_leave 
            );
        //     echo "<tr>";
        //     echo "<td>".$i."</td>";
        //     echo "<td>".$employee['name']."</td>";
        //     echo "<td>".$employee['Sick']."</td>";
        //     echo "<td>".$employee['Halfday']."</td>";
        //     echo "<td>".$employee['Casual']."</td>";
        //     echo "<td>".$employee['Special']."</td>";
        //     echo "<td>".$employee['Earned']."</td>";
        //     echo "<td>".$employee['Public']."</td>";
        //     echo "<td>".$total_leave."</td>";
        // echo "</tr>";
    }
    //var_dump($return_arr);
    echo json_encode($return_arr);
?>

