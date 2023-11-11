<?php $width_td = 300; ?>

<nav class="navbar navbar-expand-lg navbar-light bg-light main-nav"><h2>Cloudlog - Admin/Dev Tools : <?php echo $page_title; ?></h2></nav>

<div class="admin_menu_show_lang">
    Show Language : 
    <?php foreach ($language_full_array['langs'] as $lang) {  
        echo "<span class=\"admin_menu_lang\">".$lang."</span>";
    } ?>
</div>

<table class="table table-sm table-striped table-hover" style="width:inherit;">
    <thead>
        <tr class="titles">
            <th>Files</th>
            <?php
            echo "<th class=\"lang_ref\">".$language_full_array['language_ref']." (reference)</th>";
            foreach ($language_full_array['langs'] as $lang) {  
            	echo "<th class=\"lang\" data-lang=\"".$lang."\" title=\"".$lang."\" style=\"display:none;\"><i class=\"far fa-times-circle\" style=\"margin:0px 3px;\"></i><span class=\"lang_txt\">".$lang."</span></th>";
            } ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($language_full_array['english'] as $filename => $filecontent) { ?>
        <tr class="file_menu" data-file="<?php echo $filename; ?>">
            <td class="file_name" style="width:<?php echo $width_td; ?>px;" data-file="<?php echo $filename; ?>"><span style="margin:0px 10px;"><i class="fas fa-caret-right"></i></span><?php echo $filename; ?></td>
            <td class="file_ref" style="width:<?php echo $width_td; ?>px;" data-file="<?php echo $filename; ?>" data-lang="<?php echo $language_full_array['language_ref']; ?>" ></td>
            <?php foreach ($language_full_array['langs'] as $lang) {
                $_fileexist_for_lang = (in_array($lang, $language_full_array['files'][$filename]['langs']))?"<i class='fas fa-check' style='color:#00AA00;'></i>":"<i class='far fa-times-circle' style='color:#DD0000;'></i>";
                echo "<td class=\"file\" style=\"width:".$width_td."px;text-align:center;display:none;\" data-file=\"".$filename."\" data-lang=\"".$lang."\" >".$_fileexist_for_lang."<i class=\"admin_show_file far fa-eye float-right\"></td>"; 
            } ?>
        </tr>
        <?php foreach ($filecontent as $tag => $value) { ?>
        <tr class="tags" data-file="<?php echo $filename; ?>" data-tag="<?php echo $tag; ?>" style="display:none;">           
            <td class="tag_name" data-file="<?php echo $filename; ?>" data-tag="<?php echo $tag; ?>"><?php echo $tag; ?></td>
            <td class="tag_ref" data-file="<?php echo $filename; ?>" data-lang="<?php echo $language_full_array['language_ref']; ?>" data-tag="<?php echo $tag; ?>"><?php echo $value; ?></td>
            <?php foreach ($language_full_array['langs'] as $lang) {  
                if (array_key_exists($lang, $language_full_array['files'][$filename]['translate'][$tag])) {
                    $_tag_value = $language_full_array['files'][$filename]['translate'][$tag][$lang];
                    $_tag_notexist = "0";
                } else {
                    $_tag_value = "-";
                    $_tag_notexist = "1";
                }
                echo "<td class=\"tag\" style=\"width:".$width_td."px;display:none;\" data-file=\"".$filename."\" data-lang=\"".$lang."\" data-tag=\"".$tag."\" data-tagnotexist=\"".$_tag_notexist."\" >".stripslashes($_tag_value)."</td>"; 
            } ?>
        </tr>
        <?php } ?>
        <?php } ?>
    </tbody>
</table>


<script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrapdialog/js/bootstrap-dialog.min.js"></script>

<script language="javascript">
    var json_tags = <?php echo json_encode($language_full_array); ?>;

    function file_elapse_zone(_this) {
        var _fn = _this.attr('data-file');
        if (_this.find('i').hasClass('fa-caret-down')) {
            $('.tags[data-file="'+_fn+'"]').hide();
            _this.find('i').removeClass('fa-caret-down').addClass('fa-caret-right');
        } else {
            $('.tags[data-file="'+_fn+'"]').show();
            _this.find('i').removeClass('fa-caret-right').addClass('fa-caret-down');
        }
    }
    function lang_showhide_td(_fn,_showhide) {
        if (_showhide==1) {
            $('.lang[data-lang="'+_fn+'"]').show();
            $('.file[data-lang="'+_fn+'"]').show();
            $('.tag[data-lang="'+_fn+'"]').show();
        } else {
            $('.lang[data-lang="'+_fn+'"]').hide();
            $('.file[data-lang="'+_fn+'"]').hide();
            $('.tag[data-lang="'+_fn+'"]').hide();
        }
    }
    function change_tag(_this) {
        var _html = "";
        _html += "<b>Language :</b> <span class='adim_field' data-type='lang'>"+_this.attr('data-lang')+"</span><br/>";
        _html += "<b>File :</b> <span class='adim_field' data-type='file'>"+_this.attr('data-file')+"</span><br/>";
        _html += "<b>Tag :</b> <span class='adim_field' data-type='tag'>"+_this.attr('data-tag')+"</span> &nbsp;";
        _html += ((_this.attr('data-tagnotexist')=="1")?"<span style='color:var(--red);' class='adim_field' data-type='tagnotexist'>[NOT EXIST]</span>":"")+"<br/>";
        _html += "<b>Value (actual) :</b> <span class='adim_field' data-type='value_old'>"+_this.html()+"</span><br/>";
        _html += "<b>Value (update) :</b><br/><textarea class='adim_field' data-type='value_new' rows='4' style='width:100%;''>"+_this.html()+"</textarea><br/>";

        _html += "<br/><button type='submit' class='btn btn-primary btn-save'><i class='fas fa-save'></i> Save</button>&nbsp;&nbsp;";
        _html += "<a href='javascript:admin_close();' class='btn btn-danger'><i class='far fa-times-circle'></i> Cancel</a>&nbsp;&nbsp;";
        _html += "<span class='adim_result'></span>";
        BootstrapDialog.show({
            title: "", //Update tag's value",
            cssClass: 'language-dialog',
            size: BootstrapDialog.SIZE_WIDE,
            nl2br: false,
            message: _html,
            onshown: function(dialog) {
                $('.modal-dialog .btn-save').off('click').on('click',function() { admin_update(); });
            },
        });
    }
    function show_file_content(_this) {
        var _html = "";
        var _file_name = _this.closest('.file').attr('data-lang')+'/'+_this.closest('.file').attr('data-file');
        $.ajax({
            url: '<?php echo base_url(); ?>index.php/admin_check/language_show_file', type:'POST', dataType:'json',
            data: { admin_file_name: _file_name },
            error: function() { console.log('[Cloudlog][ERROR] ajax show_file_content() function return error.'); },
            success: function(_html) {
                BootstrapDialog.show({
                    title: _file_name,
                    cssClass: 'language-dialog',
                    size: BootstrapDialog.SIZE_WIDE,
                    nl2br: false,
                    message: _html,
                    onshown: function(dialog) { },
                });
            }
        });

    }
    function admin_close() {
        $('.modal-dialog .bootstrap-dialog-close-button .close').click();
    }
    function admin_update() {
        if ($('.bootstrap-dialog-message .adim_field[data-type="value_old"]').html() != $('.bootstrap-dialog-message .adim_field[data-type="value_new"]').val()) {
            var _language_lang = $('.bootstrap-dialog-message .adim_field[data-type="lang"]').html(); 
            var _language_file = $('.bootstrap-dialog-message .adim_field[data-type="file"]').html();
            var _language_tag = $('.bootstrap-dialog-message .adim_field[data-type="tag"]').html();
            var _language_value = $('.bootstrap-dialog-message .adim_field[data-type="value_new"]').val();
            var _language_iscreate = $('.bootstrap-dialog-message .adim_field[data-type="tagnotexist"]').length;
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/admin_check/language_update', type:'POST', dataType:'json',
                data: { 
                    admin_language_lang: _language_lang, 
                    admin_language_file: _language_file,
                    admin_language_tag: _language_tag,
                    admin_language_value: _language_value,
                    admin_language_iscreate: _language_iscreate
                },
                error: function() { console.log('[Cloudlog][ERROR] ajax admin_update() function return error.'); },
                success: function(res) {
                    if (typeof res.ok !== "undefined") {
                        $('.tag[data-file="'+_language_file+'"][data-lang="'+_language_lang+'"][data-tag="'+_language_tag+'"]').css('color','var(--success)').attr('data-tagnotexist',0);
                        $('.tag[data-file="'+_language_file+'"][data-lang="'+_language_lang+'"][data-tag="'+_language_tag+'"]').empty().html(_language_value);
                        admin_close();
                    } else {
                        if (typeof res.error !== "undefined") { $('.bootstrap-dialog-message .adim_result').html(res.error); }
                            else { $('.bootstrap-dialog-message .adim_result').html('ERROR'); }
                    }
                }
            });
        } else {
            $('.bootstrap-dialog-message .adim_result').html('No change for this new tag value !');
        }
    }
    $(document).ready( function (){
        $('.file_menu').off('click').on('click',function() { file_elapse_zone($(this)); });
        $('.lang').off('click').on('click',function() { lang_showhide_td($(this).find('.lang_txt').html(),0); });
        $('.admin_menu_lang').off('click').on('click',function() { lang_showhide_td($(this).html(),1); });
        $('.tag').off('click').on('click',function() { change_tag($(this)); });
        $('.admin_show_file').off('click').on('click',function() { show_file_content($(this)); });
    });
</script>
<style>
    .admin_menu_show_lang { margin:10px; }
    .admin_menu_lang { margin:0px 10px; cursor:pointer; }
    .table { margin:10px; }
    .file_menu { background:var(--dark)!important; }
    .file_name { cursor:pointer; }
    .lang { cursor:pointer; }
    .tag { cursor:pointer; }
    td { border-right:1px solid var(--light); width:<?php echo $width_td; ?>!important; word-break:break-word!important;}
    .close { float:left!important; }
    .admin_show_file { index:500; cursor:pointer;margin:2px; }
</style>