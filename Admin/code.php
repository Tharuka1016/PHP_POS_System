<?php

include('../config/function.php');

if (isset($_POST['saveAdmin'])) {

    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $is_ban = validate($_POST['is_ban']) == true? 1:0;

    if($name !='' && $email!='' && $password !=''){

        $emailCheck = mysqli_query($conn, "SELECT * FROM admins WHERE email='$email'");
        if($emailCheck)
        {
            if(mysqli_num_rows($emailCheck) > 0){
                redirect('admin-create.php','Email already used by another user');
            }
            
                $bycrypt_password = password_hash($password, PASSWORD_BCRYPT);
                $data =[
                    'name' =>$name,
                    'password' =>$password,
                    'email' =>$email,
                    'phone' =>$phone,
                    'is_ban' =>$is_ban,
                ];
                $resalt = insert('admins', $data);
                if($resalt){
                    redirect('admin.php','Admin added successfully');
                }else{
                    redirect('admins-create.php','Something went wrong');
                }

            }
        }

    }else{
        redirect('admin-create.php','Please fill required fields');
    }
if(isset($_POST['updateAdmin'])){
    $adminId = validate($_POST['adminId']);

    $adminData = getById('admins',$adminId);
    if(!$adminData['status'] != 200){
        redirect('admins-edit.php?id='.$adminId,'Please fill required fields');

    }

    
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password'],);
    $phone = validate($_POST['phone']);
    $is_ban = validate($_POST['is_ban']) == true? 1:0; 

    $EmailCheckQuery = "SELECT * FROM admins WHERE email = '$email' AND id!='$adminId'";
    $checkResult = mysqli_query($conn, $EmailCheckQuery);
    if($checkResult){
        if(mysqli_num_rows($checkResult) > 0){
            redirect('admins-edit.php?id='.$adminId,'Email already used by another user');
        }
    }

    if($password != ''){
        $hashedPassword =password_hash($password, PASSWORD_BCRYPT);
    }else{
        $hashedPassword = $adminData['data']['password'];
    }

    if($name != '' && $email != ''  )
    {
        
            $data =[
                'name' =>$name,
                'password' =>$hashedPassword,
                'email' =>$email,
                'phone' =>$phone,
                'is_ban' =>$is_ban
            ];

            $resalt = update('admins', $adminId, $data);
            if($resalt){
                redirect('admins-edit.php?id='.$adminId,'Admin updated successfully');
            }else
            {
                redirect('admins-edit.php?id='.$adminId,'Something went wrong');
            }

        
    }
    else
    {
    redirect('admins-create.php','Please fill required fields');
    }

}

    if(isset($_POST['saveCategory']))
    {
        $name = validate($_post['name']);
        $description = validate($_post['description']);
        $status = isset($_post['status']) == true ? 1:0;
    
        $data =[
            'name' =>$name,
            'description' =>$description,
            'status' =>$status,
            
        ];
        $resalt = insert('categories', $data);
        if($resalt){
            redirect('categories.php','Category added successfully');
        }else{
            redirect('categories-create.php','Something went wrong');
        }
    
    
    
    }
    


?>