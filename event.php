<?php 
    // Include configuration file 
    include_once 'config.php'; 
    
    $status = $statusMsg = ''; 
    if(!empty($_SESSION['status_response'])){ 
        $status_response = $_SESSION['status_response']; 
        $status = $status_response['status']; 
        $statusMsg = $status_response['status_msg']; 
    } 
    $postData = ''; 
    if(!empty($_SESSION['postData'])){ 
        $postData = $_SESSION['postData']; 
        unset($_SESSION['postData']); 
    } 
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        if (!empty($statusMsg)) {
            echo $status." - Status Msg: ".$statusMsg;
        }
    ?>
    <form action="addEvent.php" method="POST">
        <input type="text" name="title" placeholder="title" value="<?php echo !empty($postData['title'])?$postData['title']:''; ?>" required><br>
        <input type="text" name="description" placeholder="description" value="<?php echo !empty($postData['description'])?$postData['description']:''; ?>" required><br>
        <input type="text" name="location" placeholder="location" value="<?php echo !empty($postData['location'])?$postData['location']:''; ?>" required><br>
        <input type="date" name="date" placeholder="date" value="<?php echo !empty($postData['date'])?$postData['date']:''; ?>" required><br>
        <input type="time" name="time_from" placeholder="time_from" value="<?php echo !empty($postData['time_from'])?$postData['time_from']:''; ?>" required><br>
        <input type="time" name="time_to" placeholder="time_to" value="<?php echo !empty($postData['time_to'])?$postData['time_to']:''; ?>" required><br>
        <input type="submit" name="submit" value="Add Event"><br>

    </form>
</body>
</html>