<div class="row">
<div class="col s12">
<h4 class="pad-left-15 capitalize">Create blog</h4>
</div>
<form class='col s12' method='post' action='<?php echo site_url("site/createblogsubmit");?>' enctype= 'multipart/form-data'>
<div class="row">
<div class="input-field col s6">
<label for="name">name</label>
<input type="text" id="name" name="name" value='<?php echo set_value('name');?>'>
</div>
</div>
<div class="row">
<div class="file-field input-field col s12 m6">
<div class="btn blue darken-4">
<span>Image</span>
<input type="file" name="image" multiple>
</div>
<div class="file-path-wrapper">
<input class="file-path validate" type="text" placeholder="Upload one or more files" value='<?php echo set_value('image');?>'>
</div>
</div>
<span style=" display: block;
padding-top: 30px;"></span>
</div>
<div class="row">
<div class="input-field col s6">
<label for="video">video</label>
<input type="text" id="video" name="video" value='<?php echo set_value('video');?>'>
</div>
</div>
<div class="row">
<div class="col s12 m6">
<label>description</label>
<textarea name="description" id="some-textarea" placeholder="Enter text ..."><?php echo set_value( 'description');?></textarea>
</div>
</div>
<div class="row">
<div class="col s12 m6">
<button type="submit" class="btn btn-primary waves-effect waves-light blue darken-4">Save</button>
<a href="<?php echo site_url("site/viewblog"); ?>" class="btn btn-secondary waves-effect waves-light red">Cancel</a>
</div>
</div>
</form>
</div>
