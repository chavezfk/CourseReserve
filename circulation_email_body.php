<?php

$loanTimeLabels = array(
    '2hr' => '2 hours',
    '4hr' => '4 hours',
    '12hr' => '12 hours / overnight',
    '2 weeks' => '2 weeks');
    
?>
<style>
    th, td {border: 1px solid black;}
</style>
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
    <th>Loan Time</th>
    <th>Owner</th>
    
    <?php 
        for($c = 0; $c < $_POST['num_rows']; $c++){
            echo "<tr>".
                 "<td style='border: 1px solid black'>". $_POST['barcode'][$c] . "</td>".
                 "<td style='border: 1px solid black'>". $_POST['title'][$c] . "</td>".
                 "<td style='border: 1px solid black'>". $_POST['call_num'][$c] . "</td>".
                 "<td style='border: 1px solid black'>". $_POST['author'][$c] . "</td>".
                 "<td style='border: 1px solid black'>". getLoanTime() . "</td>".
                 "<td style='border: 1px solid black'>". $_POST['owner'][$c] . "</td>".
                 "</tr>";
        }
    ?>
</table>


<h1> Please check the document on google sheets and take the necessary actions to confirm or deny this request.</h1>