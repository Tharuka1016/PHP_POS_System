<?php

require '../config/function.php';

                    $paraResultId = checkParamId('id');
                    if(is_numeric($paraResultId)){

                        $adminId = validate($paraResultId);
                        
                        $admin = getById('admins',$adminId);
                        if($admin['status'] == 200) 
                    {
                        $adminDeleteRes  = delete('admins', $adminId);
                        if($adminDeleteRes)
                        {
                            redirect('admin.php','Admin Deleted Successfully');
                        }
                        else
                        {
                            redirect('admin.php','Something went wrong');
                        }
                    }
                    else
                    {
                        redirect('admin.php',$admin['message']); 
                    }
                        //echo $adminId;

                    }else{
                        redirect('admin.php','Something went wrong');
                    }

?>