<?php

$loanTimeLabels = array(
    '2hr' => '2 hours',
    '4hr' => '4 hours',
    '12hr' => '12 hours / overnight',
    '2 weeks' => '2 weeks');
    
?>

<h2>Reservation by <?php echo $_POST['first']. " " . $_POST['last'] ?></h2>
    
<h3>Class info: </h3>
<dl>
    <dt>Number:</dt>
    <dd><?php echo $_POST['course_num'] ?></dd>
    
    <dt>Name: <dt>
    <dd><?php echo $_POST['course_name'] ?></dd>

</dl>

<h3>Contact Info:</h3>

<dl>

    <dt>Email</dt>
    <dd><?php echo $_POST['email'] ?></dd>

    <dt>Phone</dt>
    <dd><?php echo $_POST['phone'] ?></dd>
</dl>


<h3>Items requested:</h3>

<table>
    <th>Barcode</th>
    <th>Title</th>
    <th>Call <?php echo chr(35) ?></th>
    <th>Author </th>
    <th>Owner</th>
    
    <?php 
        function getLoanTime(){
            $names = array('2hr','4hr','12hr','2 weeks');
            foreach($names as $c){
                if(isset($_POST[$c])) {
                    return $c;
                }
            }
        }
        for($c = 0; $c < $_POST['num_rows']; $c++){
            echo "<tr>"
            echo "<td>". $_POST['barcode'][$c] . "</td>",
            echo "<td>". $_POST['title'][$c] . "</td>",
            echo "<td>". $_POST['call_num'][$c] . "</td>",
            echo "<td>". $_POST['author'][$c] . "</td>",
            echo "<td>". getLoanTime() . "</td>",
            echo "<td>". $_POST['owner'][$c] . "</td>",
            echo "</tr>"
        }
    ?>
</table>


<h1> Please check the document on google sheets and take the necessary actions to confirm or deny this request.</h1>