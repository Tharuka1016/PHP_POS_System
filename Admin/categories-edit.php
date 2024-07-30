<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
        <div class="card mt-4 shadow-sm">
            <div class="card-header">
                <h4 class="mb-0">Edit Category
                    <a href="categories.php" class="btn btn-primary float-end">Back</a>
                </h4>

                </div>
                <div class="card-body">

                    <?php alertMessage(); ?>

               <form action="code1.php" method="post">
                  <?php
                     $parmValue = checkParamId('id');
                     if(!is_numeric($parmValue)){
                        echo '<h5>'.$parmValue.'</h5>';
                        return false;
                     }

                     $category = getById('categories',$parmValue);
                     if($category['status'] == 200)
                     {
                     ?>
                     <input type="hidden" name="categoryId" value="<?php $category['data']['id'];?>">                 <div class="row">
                 <div class="col-md-12 mb-3">

                    <label for="">Name *</label>
                    <input type="text" name="name" value="<?= $category['data']['name'];?>" required class="form-control" />
                 </div>
                 <div class="row">
                 <div class="col-md-12 mb-3">
                    <label for="">Description*</label>
                    <textarea name ="description" class="form-control" rows="3"><?= $category['data']['description'];?></textarea>
                 </div>
                 <div class="col-md-6">
                    <label>Status (UnChecked=Visible, Checked=Hidden)</label>
                    <br/>

                    <input type="checkbox" name="status"<?= $category['data']['status'] == true ? 'checked':'';?>  style="width:30px;height:30px";>

                 <div class="col-md-6 mb-3 text-end">
                    <br/>
                    <button type="submit" name="UpdateCategory" class="btn btn-primary">Save</button>

                 
                 </div>
               </div>
               <?php
                     }
                     else
                     {
                           echo '<h5>'.$category['message'].'</h5>';
                     }
                     ?>

               </form>
                    
            </div>
        </div>

</div>

<?php include('includes/footer.php'); ?>
                 
