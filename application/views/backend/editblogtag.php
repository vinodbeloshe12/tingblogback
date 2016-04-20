<div class="row">
<div class="col s12">
<h4 class="pad-left-15 capitalize">Edit Blog Tag</h4>
</div>
</div>
<div class="row">
<form class='col s12' method='post' action='<?php echo site_url("site/editblogtagsubmit");?>' enctype= 'multipart/form-data'>
<input type="hidden" id="normal-field" class="form-control" name="id" value="<?php echo set_value('id',$before->id);?>" style="display:none;">
<div class="row">
           <div class="input-field col s12 m8">
               <?php echo form_dropdown('relatedtag', $relatedtag, set_value('relatedtag',$before->tag)); ?>
                <label>Related Tags
           </div>
       </div>

       <div class="row" style="display:none">
                   <div class="input-field col s12 m8">
                       <?php echo form_dropdown('blog', $blog, set_value('blog',$before->blog)); ?>
                        <label> Blog</label>
                   </div>
               </div>


<div class="row">
<div class="col s6">
<button type="submit" class="btn btn-primary waves-effect waves-light  blue darken-4">Save</button>
<a href='<?php echo site_url("site/viewblogtag?id=").$this->input->get("blogid");?>' class='btn btn-secondary waves-effect waves-light red'>Cancel</a>
</div>
</div>
</form>
</div>
