<?php
include($_SERVER['DOCUMENT_ROOT'].'/Models/fileModel.php');

class defaultController {

    public function home()
    {
        $errors = [];
        $success = "";
        $model = new fileModel;
        if(isset($_FILES['upload'])){
            $target_prepend = "uploads/";
            $target_name = time().'_'. basename($_FILES["upload"]["name"]);
            $target_file = $target_prepend.$target_name;
            $file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $allowed_types = ['txt', 'doc' , 'docx', 'pdf' , 'png' , 'jpeg', 'jpg' , 'gif'];
            if ($_FILES["upload"]["size"] > 2097152) {
                $errors[] =  "Sorry, your file is too large.Maximum 2 Mb allowed";
            }
            if(!in_array($file_type , $allowed_types)){
                $errors[] = "Uploaded file format is not allowed. Please upload only txt,doc,docx,pdf,jpeg,jpg,png and gif formats";
            }
            if(count($errors) == 0){
                if (move_uploaded_file($_FILES["upload"]["tmp_name"], $target_file)) {
                    try {
                        if($model->insertFile($target_name))
                            $success = "File ". basename( $_FILES["upload"]["name"]). " has been uploaded successfully.";
                        else
                            $errors[] = "Sorry, there was an error uploading your file.";    
                    } catch (\Exception $th) {
                        $errors[] = $th->getMessage();
                    }
                } else {
                    $errors[] = "Sorry, there was an error uploading your file.";
                }
            }
        }

        if(isset($_GET['d'])){

            $return = $this->deleteFile(base64_decode($_GET['d']));
            if($return)
                $success="File deleted";
            else
                $errors[] = "Failed to delete file";
        }
        

        $uploaded_files = $model->getFiles(0);
        $history        = $model->getHistory(0);

        return $this->view('home.php' , ['errors' => $errors , 'success' => $success , 'uploaded_files' => $uploaded_files , 'history' => $history]);
    }

    public function deleteFile($id = 0)
    {
        $model = new fileModel;
        if(is_numeric($id)){
            return $model->deleteFile($id);
        }
        return false;
    }

    private function view(String $view = "" , Array $data = [])
    {
        if(!empty($view)){
            $view = $_SERVER['DOCUMENT_ROOT'].'/views/'.$view;
            if(file_exists($view)){
                if(!empty($data))
                    extract($data);
                ob_start();
                include($view);
                $content = ob_get_contents();
                ob_end_clean();
                return $content;
            }
            throw new Exception('View file does not exist');
        }
        throw new Exception('View file not defined');
    }
}