<?php
include('../config/function.php');

if(isset($_POST['saveCategory']))
{
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $status = isset($_POST['status']) == true ? 1:0;

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
if(isset($_POST['updateCategory']))

{
    $name = validate($_POST['categoryId']);
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $status = isset($_POST['status']) == true ? 1:0;

    $data =[
        'name' =>$name,
        'description' =>$description,
        'status' =>$status,
        
    ];
    $resalt = update('categories', $categoryId, $data);
    if($resalt){
        redirect('categories-edit.php?id=' .$categoryId,'Category updated successfully');
    }else{
        redirect('categories-create.php?id=' .$categoryId,'Something went wrong');
    }
}

if(isset($_POST['saveProduct']))
{
    $categoryId = validate($_POST['category_id']);
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $price = validate($_POST['price']);
    $quantity = validate($_POST['quantity']);
    $status = isset($_POST['status']) == true ? 1:0;

    if($_FILES['image']['size'] > 0)
    {
        $path = "../assest/uploads/products";
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        $filename = time().'.'.$image_ext;

        move_uploaded_file($_FILES['image']['tmp_name'], $path."/".$filename);
        $finalImage = "assets/uploads/products/".$filename;
    }
    else
    {
        $finalImage = '';
    }

    $data =[
        'category_id' =>$categoryId,
        'name' =>$name,
        'description' =>$description,
        'price' =>$price,
        'quantity' =>$quantity,
        'image' =>$finalImage,
        'status' =>$status,
        
    ];

    $resalt = insert('products', $data);
    if($resalt){
        redirect('product.php','Product added successfully');
    }else{
        redirect('product-create.php','Something went wrong');
    }

}

if(isset($_POST['saveCustomer']))
{
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']);
    $status = isset($_POST['status']) ? 1:0;

    if($name !='')
    {
        $emailCheck = mysqli_query($conn,"SELECT * FROM customers WHERE email='$email'");
        if($emailCheck){
            if(mysqli_num_rows($emailCheck) > 0){
                redirect('customers.php','Email already used by another user');
            }
        }
        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
           'status' => $status
        ];
        $resalt = insert('customers', $data);
        if($resalt){
            redirect('customers.php','Customer added successfully');
        }else{
            redirect('customers.php','Something went wrong');
        }
    }
    else
    {
        redirect('customers.php','Name is required');
        return false;
    }
}

?>