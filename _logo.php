<div class="d-flex justify-content-center py-4">
    <a href="index" class="logo d-flex align-items-center w-auto">
            <?php 
                $query1 = mysqli_query($connect, "SELECT * FROM pn_setting where id=3");
                $row1 = $query1->fetch_object();
                
                echo '<img src="assets/img/'.$row1->isi.'" alt="">'; 
            ?>
        <span class="d-none d-lg-block">
            <?php 
                $query = mysqli_query($connect, "SELECT * FROM pn_setting where id=1");
                $row = $query->fetch_object();
                echo $row->isi; 
            ?>
        </span>
    </a>
</div><!-- End Logo -->