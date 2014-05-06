<input type="hidden" id="currentCat" name="currentCat" value="<?php echo $currentCat->cat_ID ; ?>" />
<ul id="oc_container" class="module">
    <?php
    if (count($modules) > 0) {
        $cls = (!is_admin()) ? ' class="mx-input"' : '';
        $z = 0;
        $id = $currentPost->ID;
        foreach ($newModules as $mod) {
            
            $z++;
            $mid = $z - 1;
            ?>
            <li id='module_<?php echo $id; ?>_<?php echo $z; ?>' rel='<?php echo $id; ?>'>

                <input <?php echo $cls; ?> type="hidden" id="modid_module_<?php echo $id; ?>_<?php echo $z; ?>" name="modules[oc_container][]" value="<?php echo $mod['widget']; ?>" />
                <input <?php echo $cls; ?> id="modset_module_<?php echo $id; ?>_<?php echo $z; ?>" type="hidden" name="modules_settings[oc_container][]" value="<?php echo $mod['olddata']; ?>" />
                <h3>
                    <nobr class="title">
                        <?php echo $mod['post']->post_title." (".$mod['type'].")"; ?>
                    </nobr>
                    <nobr class="ctl">
                        <span class="handle"></span>
                        <img class='deleteModuleRenovatio' src="<?php echo $base_theme_url; ?>/images/delete.png" rel='#module_<?php echo $id; ?>_<?php echo $z; ?>' />&nbsp;
                        <img class="insertModuleRenovatio" id="modset_module_<?php echo $id; ?>_<?php echo $z; ?>_icon" rel="<?php echo $mod['widget']; ?>" data="<?php echo $id; ?>|<?php echo $mid; ?>" datafield="modset_module_<?php echo $id; ?>_<?php echo $z; ?>" src="<?php echo $base_theme_url; ?>/images/settings.png" />
                    </nobr>
                </h3>
                <?php echo $mod['post']->post_content; ?>
                <div class="clear"></div>
            
            </li>    
        <?php } ?>
<?php } ?>
</ul>
<a id="btnAddModuleRenovatio" class="button button-primary button-large" rel="oc_container"">Add Module</a>
