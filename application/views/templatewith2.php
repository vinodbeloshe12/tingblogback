<?php echo $this->load->view('backend/header'); ?>
<div id="page-content">
    <div class="row">
        <div class="col s9">
            <?php echo $this->load->view('backend/'.$page); ?>
        </div>
        <div class="col s3">
            <?php echo $this->load->view('backend/'.$page2); ?>
        </div>
    </div>
</div>
<?php echo $this->load->view('backend/footer'); ?>