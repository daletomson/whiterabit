<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploaded Documents</title>

    <style>
        .upload-form{
            display: flex;
            width: 480px;
            justify-content: space-between;
            align-items: center;
            margin-left: 30%;
            margin-bottom: 10px;
        }
        .upload-btn {
            width: 100px;
            background: #5fbbff;
            border: unset;
            color: #ffffff;
            padding: 10px;
            border-radius: 5px;
        }
        .errors{
            list-style: none;
            color:red;
        }
        .green{
            color:green;
        }
        .equal-box {
            display: flex;
            justify-content: space-evenly;
            margin-top: 30px;
        }
        table {
            border-collapse: collapse;
        }
        tr {
            border-bottom: 1px solid #cecece;
        }
        td, th {
            padding: 10px;
        }
    </style>
</head>
<body>
    <div>
        <form action="/home" method="post" class='upload-form' enctype="multipart/form-data">
            <label for="">Upload New File</label>
            <input type="file" name="upload" id="upload">
            <button type="submit" class="upload-btn"> Upload </button>
        </form>
        <form action="/home" method="post" class="upload-form">
            <label for="">Search File</label>
            <input type="text" name="file-name" style="padding: 7px;">
            <button type="submit" class="upload-btn"> Search </button>
        </form>
        <?php 
            if(!empty($errors)){
        ?>
            <ul class="errors"> 
            <?php
                foreach($errors as $error){
            ?>
                <li><?=  $error; ?></li>        
            <?php
                }
            ?>
        <?php
            } else if(!empty($success)){
        ?>
            <p class="green"><?=  $success; ?></p>
        <?php
            }
        ?>
    </div>

    <div class="equal-box">
        <?php if(!empty($uploaded_files)){ ?>
            <div>
                <h2>Uploaded Files</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Sl.No</th>
                            <th>File name</th>
                            <th>Uploaded On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $i=1;
                            foreach($uploaded_files as $file){
                            $file_split = explode('_' , $file['file']);
                        ?>
                            <tr>
                                <td><?= $i;  ?></td>
                                <td><?= $file_split[1];  ?></td>
                                <td><?= date('d-m-Y h:i A',$file['created_time']);  ?></td>
                                <td><a href="/home/?d=<?php echo base64_encode($file['id']) ?>">Delete</a></td>
                            </tr>
                        <?php
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    
        <?php if(!empty($uploaded_files)){ ?>
            <div>
                <h2>Uploaded Files History</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Sl.No</th>
                            <th>File name</th>
                            <th>Action Type</th>
                            <th>Happened On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $i=1;
                            $action_type = [1=>'Uploaded' , 2=> 'Deleted'];
                            foreach($history as $file){
                            $file_split = explode('_' , $file['file']);
                        ?>
                            <tr>
                                <td><?= $i;  ?></td>
                                <td><?= $file_split[1];  ?></td>
                                <td><?=  $action_type[$file['action_type']] ?></td>
                                <td><?= date('d-m-Y h:i A',$file['action_time']);  ?></td>
                            </tr>
                        <?php
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
</body>
</html>