<?php
	$this->set_css($this->default_theme_path.'/flexigrid/css/flexigrid.css');
	$this->set_js_lib($this->default_javascript_path.'/'.grocery_CRUD::JQUERY);

	if (isset($dialog_forms) && $dialog_forms) {
        $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
        $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
        $this->set_js_lib($this->default_javascript_path.'/common/lazyload-min.js');
    }

    $this->set_js_lib($this->default_javascript_path.'/common/list.js');

	$this->set_js($this->default_theme_path.'/flexigrid/js/cookies.js');
	$this->set_js($this->default_theme_path.'/flexigrid/js/flexigrid.js');

    $this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.form.min.js');

	$this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.numeric.min.js');
	$this->set_js($this->default_theme_path.'/flexigrid/js/jquery.printElement.min.js');

	/** Fancybox */
	$this->set_css($this->default_css_path.'/jquery_plugins/fancybox/jquery.fancybox.css');
	$this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.fancybox-1.3.4.js');
	$this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.easing-1.3.pack.js');

	/** Jquery UI */
	$this->load_js_jqueryui();

	/** MultiSearch UI */
	$this->set_js($this->default_theme_path.'/flexigrid/js/multisearch.js');

?>
<script type='text/javascript'>
	var base_url = '<?php echo base_url();?>';

	var subject = '<?php echo addslashes($subject); ?>';
	var ajax_list_info_url = '<?php echo $ajax_list_info_url; ?>';
	var unique_hash = '<?php echo $unique_hash; ?>';
	var export_url = '<?php echo $export_url; ?>';

	var message_alert_delete = "<?php echo $this->l('alert_delete'); ?>";

</script>
<div id='list-report-error' class='report-div error'></div>
<div id='list-report-success' class='report-div success report-list' <?php if($success_message !== null){?>style="display:block"<?php }?>><?php
if($success_message !== null){?>
	<p><?php echo $success_message; ?></p>
<?php }
?></div>
<div class="flexigrid" style='width: 100%;' data-unique-hash="<?php echo $unique_hash; ?>">
	<div id="hidden-operations" class="hidden-operations"></div>
	<div class="mDiv">
		<div class="ftitle">
			&nbsp;
		</div>
		<div title="<?php echo $this->l('minimize_maximize');?>" class="ptogtitle">
			<span></span>
		</div>
	</div>
	<div id='main-table-box' class="main-table-box">

	<?php if(!$unset_add || !$unset_export || !$unset_print){?>
	<div class="tDiv">
		<?php if(!$unset_add){?>
		<div class="tDiv2">
        	<a href='<?php echo $add_url?>' title='<?php echo $this->l('list_add'); ?> <?php echo $subject?>' class='add-anchor add_button'>
			<div class="fbutton">
				<div>
					<span class="add"><?php echo $this->l('list_add'); ?> <?php echo $subject?></span>
				</div>
			</div>
            </a>
			<div class="btnseparator">
			</div>
		</div>
		<?php }?>
		<div class="tDiv3">
			<?php if(!$unset_export) { ?>
        	<a class="export-anchor" href="<?php echo $export_url; ?>" download>
				<div class="fbutton">
					<div>
						<span class="export"><?php echo $this->l('list_export');?></span>
					</div>
				</div>
            </a>
			<div class="btnseparator"></div>
			<?php } ?>
			<?php if(!$unset_print) { ?>
        	<a class="print-anchor" data-url="<?php echo $print_url; ?>">
				<div class="fbutton">
					<div>
						<span class="print"><?php echo $this->l('list_print');?></span>
					</div>
				</div>
            </a>
			<div class="btnseparator"></div>
			<?php }?>
		</div>
		<div class='clear'></div>
	</div>
	<?php }?>

	<div id='ajax_list' class="ajax_list">
		<?php echo $list_view?>
	</div>
<?php
	if(!$multisearch){
?>
<!-- Original -->

	<?php echo form_open( $ajax_list_url, 'method="post" id="filtering_form" class="filtering_form" autocomplete = "off" data-ajax-list-info-url="'.$ajax_list_info_url.'"'); ?>


	<div class="sDiv quickSearchBox" id='quickSearchBox'>
		<div class="sDiv2">
			<?php echo $this->l('list_search');?>: <input type="text" class="qsbsearch_fieldox search_text" name="search_text" size="30" id='search_text'>
			<select name="search_field" id="search_field">
				<option value=""><?php echo $this->l('list_search_all');?></option>
				<?php foreach($columns as $column){?>
				<option value="<?php echo $column->field_name?>"><?php echo $column->display_as?>&nbsp;&nbsp;</option>
				<?php }?>
			</select>
            <input type="button" value="<?php echo $this->l('list_search');?>" class="crud_search" id='crud_search'>
		</div>
        <div class='search-div-clear-button'>
        	<input type="button" value="<?php echo $this->l('list_clear_filtering');?>" id='search_clear' class="search_clear">
        </div>
	</div>

<!-- Original Ends-->
<?php
	}else{
?>


<!--- Template modification starts--->
	<?php echo form_open( $ajax_list_url, 'method="post" id="filtering_form" data="'.$unique_hash.'" class="filtering_form" autocomplete = "off" data-ajax-list-info-url="'.$ajax_list_info_url.'"'); ?>


	<div class="sDiv quickSearchBox" id='quickSearchBox'>

<!-------------------------- Edit-1 starts -------------------------------------------------------------------------------------------------------------->
		<table>
		   <tr>
			<td>
				Recherche Simple &nbsp;&nbsp;
			</td>
			<td>
			   <input class="search_type_<?php echo $unique_hash; ?>" type="radio" name="radio_search_type" value="basic" checked="checked"/>
                        </td>
                        <td>
                            &nbsp;&nbsp; - &nbsp;&nbsp;
                        </td>
			<td>
				Recherche Avancée &nbsp;&nbsp;
			</td>
			<td>
				<input class="search_type_<?php echo $unique_hash; ?>" type="radio" name="radio_search_type" value="advanced"/>
			</td>
		   </tr>
		</table>
		<input type="hidden" class="main_search_type_<?php echo $unique_hash; ?>" name="search_type" />
<!--------------------------- Edit-1 ends -------------------------------------------------------------------------------------------------------------->

		<div class="sDiv2">

		 <div id="basic_search_<?php echo $unique_hash; ?>">
			<?php echo $this->l('list_search');?>: <input type="text" class="qsbsearch_fieldox search_text" name="search_text" size="30" id='search_text'>
			<select name="search_field" id="search_field">
				<option value=""><?php echo $this->l('list_search_all');?></option>
				<?php foreach($columns_for_search as $column){?>
				<option value="<?php echo $column->field_name?>"><?php echo $column->display_as?>&nbsp;&nbsp;</option>
				<?php }?>
			</select>

			 <input  type="button" value="<?php echo $this->l('list_search');?>" class="crud_search" id='crud_search'>
		</div>	
	
<!-------------------------- Edit-2 starts ------------------------------------------------------------------------------------------------------------->
		<div style="display:none;" id="advanced_search_<?php echo $unique_hash; ?>">
			<table>
			  <?php $c=0;  foreach($columns_for_search as $column){
					if(!array_key_exists($column->field_name,$option_type) )continue;
					$class = $other_class = ""; $extra_attr = null;
					if( $option_type[$column->field_name] =="numeric_opts")
					{
						$type  = "numeric"; // Input type to be used
						$other_class = $class = "numeric";
					}
                                       else
					{
						$type = "text"; // Input type to be used
						if($option_type[$column->field_name] == "date_and_time")
						{
							// picker to be used
							$class = "time_inputs"; 
							if(in_array($types[$column->field_name]->type,array('timestamp','datetime')))
							{
							  // datetimepicker to be used
							  $other_class="timepicker_".$column->field_name;
							}else
							{
							  // datepicker to be used
							  $other_class="datepicker_".$column->field_name;
							}
							
							// disable user editing, only allow picker
							 $extra_attr = 'readonly="readonly"';
							 $script = "<script>$(document).ready(function(){ $('#ADV_data_".$column->field_name."').change(function(){ if($.inArray($(this).val(), ['IN','NOT IN','LIKE','NOT LIKE']) != -1){ $('.".$column->field_name."_main').find('input').attr('readonly', false); }else { $('.".$column->field_name."_main').find('input').attr('readonly', 'readonly');}})});</script>";

						}
					}
			?>
			  <tr>
				<!-- Field Name -->
			 	<td><?php echo $column->display_as;?></td>
				
				<!-- Field Operation dropdown -->
			 	<td>
					<select class="option_selection_common <?php echo $class;?>" data="<?php echo $column->field_name;?>" name="ADV_operation_<?php echo $column->field_name;?>"  id="ADV_data_<?php echo $column->field_name;?>">
						<?php echo $options[$option_type[$column->field_name]];?>
					</select>
				</td>
				<!-- User Input field -->
				<td class="cell_of_<?php echo $column->field_name;?>">

					<!-- Single Input -->
					<div class="<?php echo $column->field_name;?>_main">
                                         <!-- allcell data to test-->
					  <input style="display:none;" <?php echo (is_null($extra_attr)?"":$extra_attr); ?> class="<?php echo $other_class;?>   allcelldata_<?php echo $unique_hash; ?>"  name="ADV_data_<?php echo $column->field_name?>" type="<?php echo $type; ?>" />
                                        <!-- end test-->
					</div>

				      <?php if($other_class !=""){?>
					<!-- Double Input -->
					<div style="display:none;" class="<?php echo $column->field_name;?>_sub">
					 <table>
						<tr>
							<td>
							  <input style="display:none;" class="<?php echo $other_class?>_from" <?php echo (is_null($extra_attr)?"":$extra_attr); ?>  name="ADV_data_<?php echo $column->field_name?>_from"/>
						        </td>
							<td>
							   <input style="display:none;" class="<?php echo $other_class?>_to" <?php echo (is_null($extra_attr)?"":$extra_attr); ?>  name="ADV_data_<?php echo $column->field_name?>_to"/>
							</td>
						</tr>
					 </table>			
					</div>
					<?php } ?>
					<?php echo isset($script)?$script:"";?>
				</td>
				<!-- Logic dropdown with next field -->
				<td>
					<?php if(++$c < count($columns)){ ?>  
						<select  name="ADV_logic_<?php echo $column->field_name;?>"><?php echo $options["logic"];?></select>
					<?php }else { ?>
						&nbsp;&nbsp;
					<?php }?>
				</td>
				<td>
					<?php if(array_key_exists($column->field_name, $field_property) && array_key_exists("help",$field_property[$column->field_name])){ echo $field_property[$column->field_name]["help"]; }else {?>
				&nbsp; &nbsp; <?php };?>
				</td>
			  </tr>
				<?php  }?>
			</table>
			 <input  type="button" value="<?php echo $this->l('list_search');?>" class="crud_search" id='crud_search'>
		</div>
<!------------------------------------------------------ Edit-2 ends ------------------------------------------------------------------------------>
           
		</div> <!-- end of class sDiv2 -->

<!-------------------------- Edit-3 starts ------------------------------------------------------------------------------------------------------------->

<script>

var field_property = <?php echo json_encode($field_property);?>;
var unique_hash = "<?php echo $unique_hash; ?>";

</script>
<!------------------------------------------------------ Edit-3 ends ------------------------------------------------------------------------------>


        <div class='search-div-clear-button'>
        	<input type="button" value="<?php echo $this->l('list_clear_filtering');?>" id='search_clear' class="search_clear">
        </div>

	</div>
<!--- Template modification ends--->

<?php
	} // end of else
?>

	<div class="pDiv">
		<div class="pDiv2">
			<div class="pGroup">
				<span class="pcontrol">
					<?php list($show_lang_string, $entries_lang_string) = explode('{paging}', $this->l('list_show_entries')); ?>
					<?php echo $show_lang_string; ?>
					<select name="per_page" id='per_page' class="per_page">
						<?php foreach($paging_options as $option){?>
							<option value="<?php echo $option; ?>" <?php if($option == $default_per_page){?>selected="selected"<?php }?>><?php echo $option; ?>&nbsp;&nbsp;</option>
						<?php }?>
					</select>
					<?php echo $entries_lang_string; ?>
					<input type='hidden' name='order_by[0]' id='hidden-sorting' class='hidden-sorting' value='<?php if(!empty($order_by[0])){?><?php echo $order_by[0]?><?php }?>' />
					<input type='hidden' name='order_by[1]' id='hidden-ordering' class='hidden-ordering'  value='<?php if(!empty($order_by[1])){?><?php echo $order_by[1]?><?php }?>'/>
				</span>
			</div>
			<div class="btnseparator">
			</div>
			<div class="pGroup">
				<div class="pFirst pButton first-button">
					<span></span>
				</div>
				<div class="pPrev pButton prev-button">
					<span></span>
				</div>
			</div>
			<div class="btnseparator">
			</div>
			<div class="pGroup">
				<span class="pcontrol"><?php echo $this->l('list_page'); ?> <input name='page' type="text" value="1" size="4" id='crud_page' class="crud_page">
				<?php echo $this->l('list_paging_of'); ?>
				<span id='last-page-number' class="last-page-number"><?php echo ceil($total_results / $default_per_page)?></span></span>
			</div>
			<div class="btnseparator">
			</div>
			<div class="pGroup">
				<div class="pNext pButton next-button" >
					<span></span>
				</div>
				<div class="pLast pButton last-button">
					<span></span>
				</div>
			</div>
			<div class="btnseparator">
			</div>
			<div class="pGroup">
				<div class="pReload pButton ajax_refresh_and_loading" id='ajax_refresh_and_loading'>
					<span></span>
				</div>
			</div>
			<div class="btnseparator">
			</div>
			<div class="pGroup">
				<span class="pPageStat">
					<?php $paging_starts_from = "<span id='page-starts-from' class='page-starts-from'>1</span>"; ?>
					<?php $paging_ends_to = "<span id='page-ends-to' class='page-ends-to'>". ($total_results < $default_per_page ? $total_results : $default_per_page) ."</span>"; ?>
					<?php $paging_total_results = "<span id='total_items' class='total_items'>$total_results</span>"?>
					<?php echo str_replace( array('{start}','{end}','{results}'),
											array($paging_starts_from, $paging_ends_to, $paging_total_results),
											$this->l('list_displaying')
										   ); ?>
				</span>
			</div>
		</div>
		<div style="clear: both;">
		</div>
	</div>
	<?php echo form_close(); ?>
	</div>
</div>