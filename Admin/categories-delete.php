<?php

require '../config/function.php';

                    $paraResultId = checkParamId('id');
                    if(is_numeric($paraResultId)){

                        $categoryId = validate($paraResultId);
                        
                        $category = getById('categories',$adminId);
                        if($admin['status'] == 200) 
                    {
                        $response = delete('categories', $categoryId);
                        if($response)
                        {
                            redirect('categories.php','Category Deleted Successfully');
                        }
                        else
                        {
                            redirect('categories.php','Something went wrong');
                        }
                    }
                    else
                    {
                        redirect('categories.php',$category['message']); 
                    }
                        //echo $adminId;

                    }else{
                        redirect('categories.php','Something went wrong');
                    }

?>