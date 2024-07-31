<?php

include('../config/function.php');

if (!isset($_SESSION['productItems'])) {
    $_SESSION['productItems'] = [];
}
if (!isset($_SESSION['productItemIds'])) {
    $_SESSION['productItemIds'] = [];
}

if (isset($_POST['addItem'])) 
{
    $productId = validate($_POST['product_id']);
    $quantity = validate($_POST['quantity']);

    $checkProduct = mysqli_query($conn, "SELECT * FROM products WHERE id='$productId' LIMIT 1");
    if ($checkProduct){
        if(mysqli_num_rows($checkProduct) > 0){
            $row = mysqli_fetch_assoc($checkProduct);
            if($row['quantity'] < $quantity){
                redirect('order-create.php','Only ' . $row['quantity'] . ' quantity available');
            }
            $productData = [
                'product_id' => $row['id'],
                'name' => $row['name'],
                'image' => $row['image'],
                'price' => $row['price'],
                'quantity' => $quantity,
            ];

            if(!in_array($row['id'], $_SESSION['productItemIds'])){
                array_push($_SESSION['productItemIds'], $row['id']);
                array_push($_SESSION['productItems'], $productData);
            } else {
                foreach ($_SESSION['productItems'] as $key => $prodSessionItem) {
                    if($prodSessionItem['product_id'] == $row['id']){
                        $newQuantity = $prodSessionItem['quantity'] + $quantity;
                        $productData = [
                            'product_id' => $row['id'],
                            'name' => $row['name'],
                            'image' => $row['image'],
                            'price' => $row['price'],  
                            'quantity' => $newQuantity,
                        ];
                        $_SESSION['productItems'][$key] = $productData;
                    }
                }
            }
            redirect('order-create.php','Item Added: ' . $row['name']);
        } else {
            redirect('order-create.php','No Such product found!');
        }
    } else {
        redirect('order-create.php','Something went wrong');
    }
}

if(isset($_POST['productIncDec'])) 
{
    $productId = validate($_POST['product_id']);
    $quantity = validate($_POST['quantity']);

    $flag = false;
    foreach($_SESSION['productItems'] as $key=> $item)
    {
        if($item['product_id'] == $productId){ 
            $flag = true;
            $_SESSION['productItems'][$key]['quantity'] = $quantity;
        }
    }
    if($flag){
        jsonResponse(200, 'success','Quantity Updated');
    } else {
        jsonResponse(500, 'error','Something went wrong. Please try again');
    }
}

if(isset($_POST['proceedToPlaceBtn']))
{
    $phone = validate($_POST['cphone']);
    $payment_mode = validate($_POST['payment_mode']);

    //checking for customer
    $checkCustomer = mysqli_query($conn,"SELECT * FROM customers WHERE phone = '$phone' LIMIT 1");
    if($checkCustomer){
        if(mysqli_num_rows($checkCustomer)>0) {
            $_SESSION['invoice_no'] =  "INV-".rand(111111,999999);
            $_SESSION['cphone'] = $phone;
            $_SESSION['payment_mode'] = $payment_mode;

            jsonResponse(200, 'warning', 'Customer found');
        } else {
            $_SESSION['cphone'] = $phone;
            jsonResponse(404, 'warning', 'Customer not found');
        }
    } else {
        jsonResponse(500, 'error', 'Something went wrong');
    }
}

if(isset($_POST['saveCustomerBtn']))
{
    $name = validate($_POST['name']);
    $phone = validate($_POST['phone']);
    $email = validate($_POST['email']);

    if($name !='' && $phone !=''){
            $data = [
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
            ];
            $resalt = insert('customers', $data);
            if($resalt){
                jsonResponse(200,'success','Customer added successfully');
            } else{
                jsonResponse(500, 'error','Something went wrong');
            }
    }else{
        jsonResponse(422, 'error', 'Please fill required fields');
    }
}

if(isset($_POST['saveOrder']))
{
    $phone = validate($_SESSION['cphone']);
    $payment_mode = validate($_SESSION['payment_mode']);
    $invoice_no = validate($_SESSION['invoice_no']);   
    $order_placed_by_id = $_SESSION['loggedInUser']['uder_id'];

    $checkCustomer = mysqli_query($conn, "SELECT * FROM customers WHERE phone = '$phone' LIMIT 1");
    if($checkCustomer){
        jsonResponse(500, 'error','Something went wrong!');
}
 if (mysqli_num_rows($checkCustomer) > 0)
 {
    $customerData = mysqli_fetch_assoc($checkCustomer);

    if(!isset($_SESSION['productItems'])){
        jsonResponse(404, 'warning','No products found in cart');
    }
    
    $totalAmount = 0;
    foreach($sessiomProducts as $amItem){
     $totalAmount += $amItem['price'] * $amItem['quantity'];
    }
    $data = [
        'customer_Id' => $customerData['id'],
        'invoice_no' => $invoice_no,
        'tracking_no' => rand(11111,99999),
        'total_amount' => $totalAmount,
        'order_date' => date('Y-m-d '),
        'payment_mode' => $payment_mode,
        'order_status' => $order_status,
        'order_placed_by_id' => $order_placed_by_id
    ];
    $resalt = insert('orders', $data);
    $lastOrderId = mysqli_insert_id($conn);

    foreach($sessiomProducts as $prodItem){
        $productId = $prodItem['product_id'];
        $price = $prodItem['price'];
        $quantity = $prodItem['quantity'];

        //Inserting order items
        $dataOrderItem = [
            'order_id' => $lastOrderId,
            'product_id' => $productId,
            'price' => $price,
            'quantity' => $quantity,
        ];
        $orderItemQuery = insert('order_Item', $dataOrderItem);

        //checking for the books quantity and decreasing quantity and making total quantity
        $checkProductQuantityQuery = mysqli_query($conn,"SELECT * FROM products WHERE id='$productId'");
        $productQtyData = mysqli_fetch_assoc($checkProductQuantityQuery);
        $totalProductQuantity = $productQtyData['quantity'] - $quantity;

        $dataUpdate = [
            'quantity' => $totalProductQuantity,
        ];
        $updateProductQty = update('products',$productId,$dataUpdate);
    }
    unset($_SESSION['productItemsIds']);
    unset($_SESSION['productItems']);
    unset($_SESSION['cphone']);
    unset($_SESSION['payment_mode']);
    unset($_SESSION['invoice_no']);

    jsonResponse(200, 'success', 'order Placed Success');
 }
 else
 {
    jsonResponse(404, 'warning', 'No Customer found');
 }
}
?>
