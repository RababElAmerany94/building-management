$(document).ready(function(){

// Get type of input
$.fn.getType = function(){ return this[0].tagName == "INPUT" ? this[0].type.toLowerCase() : this[0].tagName.toLowerCase(); }

// Date picker for range selection
var dates=function(options)
{

	var from  = options[0],
	    to    = options[1], start = "", end = "",datefmt='yy-mm-dd';

	if(2 in options)
	{
		start  = ("start"      in options[2] && (options[2].start !== null) ? options[2].start.split(" ")[0] : ""   );
		end    = ("end"        in options[2] && (options[2].start !== null) ? options[2].end.split(" ")[0]   : ""   );
		datefmt = ("dateFormat" in options[2] && (options[2].dateFormat !== null) ? options[2].dateFormat : datefmt );
	};


	var from_opts = {
        	dateFormat: datefmt,
       	 	changeMonth: true,
        	changeYear: true,
        	numberOfMonths: 1,
        	yearRange: '1800:2050',
        	onSelect: function (selectedDate) {
            	if (selectedDate) {
                	to.datepicker("option", "minDate", selectedDate);
            	}
        }
       }, to_opts = {
        	dateFormat: datefmt,
        	changeMonth: true,
        	changeYear: true,
        	numberOfMonths: 1,
        	yearRange: '1800:2050',
       		onSelect: function (selectedDate) {
            	if (selectedDate) {
                	from.datepicker("option", "maxDate", selectedDate);
            }
        }
      };

	if(start.length && end.length)
	{
		from_opts.maxDate = new Date(end);
		from_opts.minDate = new Date(start);
		to_opts.maxDate= new Date(end);
		to_opts.minDate= 0;
	}
	
    from.datepicker("destroy");
    to.datepicker("destroy");

    from.datepicker(from_opts).addClass("datepicker_range");
    to.datepicker(to_opts).addClass("datepicker_range");

}

// mysqldate to js date - fmt example : 2015-06-26 09:00:00
var mysqltimestamp2javascript=function(str)
{
	var t = str.split(/[- :]/);
	return new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
}

// Datetimepicker for range selection
var times=function(options)
{

	var startDateTextBox =  options[0],
	    endDateTextBox   =  options[1], istart = "", iend = "",datefmt='yy-mm-dd',timefmt='HH:mm:ss';

	if(2 in options)
	{
		istart  = ("start"      in options[2] && (options[2].start !== null) ? options[2].start : ""   );
		iend    = ("end"        in options[2] && (options[2].start !== null) ? options[2].end   : ""   );
		datefmt = ("dateFormat" in options[2] && (options[2].dateFormat !== null) ? options[2].dateFormat : datefmt );
		timefmt = ("timeFormat" in options[2] && (options[2].timeFormat !== null) ? options[2].timeFormat : timefmt );
	}

	

	var start_opts = {
        			dateFormat: datefmt,
				timeFormat: timefmt,
        			changeMonth: true,
        			changeYear: true,
        			numberOfMonths: 1,
				yearRange: '1800:2050',
				onSelect: function (selectedDateTime)
				{
					var start = $(this).datetimepicker('getDate');
					endDateTextBox.datetimepicker('option', 'minDate', start);
					endDateTextBox.datetimepicker('option', 'minDateTime', new Date(start.getTime()));
				}
	},end_opts = {
        			dateFormat: datefmt,
				timeFormat: timefmt,
        			changeMonth: true,
        			changeYear: true,
        			numberOfMonths: 1,
        			yearRange: '1800:2050',
				onSelect: function (selectedDateTime)
				{
					var end = $(this).datetimepicker('getDate');
					startDateTextBox.datetimepicker('option', 'maxDate', end);
					startDateTextBox.datetimepicker('option', 'maxDateTime', new Date(end.getTime()) );
				}
	};
	
	if(istart.length && iend.length)
	{
		istart = mysqltimestamp2javascript(istart);
		iend   = mysqltimestamp2javascript(iend);

		start_opts.maxDate = new Date(iend),
        	start_opts.minDate = new Date(istart),
		start_opts.minDateTime = new Date(istart.getTime()),
		start_opts.maxDateTime = new Date(iend.getTime());

		end_opts.maxDate = new Date(iend),
        	end_opts.minDate = 0,
		end_opts.minDateTime = 0,
		end_opts.maxDateTime = new Date(iend.getTime());	
	}


	startDateTextBox.datepicker("destroy");
	endDateTextBox.datepicker("destroy");
	startDateTextBox.datetimepicker("destroy");
	endDateTextBox.datetimepicker("destroy");

	startDateTextBox.datetimepicker(start_opts).addClass('datetimepicker_range');
	endDateTextBox.datetimepicker(end_opts).addClass('datetimepicker_range');


}

		// Change in first radion search selection
		$(".search_type_"+unique_hash).on("change",function()
		{ 
			// Set hidden input of search type
			$(".main_search_type_"+unique_hash).val(this.value);

			// if basic
			if(this.value == "basic")
			{ 
				// show basic division
				$("#basic_search_"+unique_hash).show(); 

				// hide advanced division
				$("#advanced_search_"+unique_hash).hide();
			}

			// if advanced
			if(this.value == "advanced")
			{ 
				// hide basic division
				$("#basic_search_"+unique_hash).hide(); 

				// show advanced division
				$("#advanced_search_"+unique_hash).show();
			}
		});
                
                //-----------------------------------------------------------------------------------------------------------------------------------
                //
                var trigger =false;
                $('.allcelldata_'+unique_hash).each(function() {
                                   if(readCookie($(this)[0]['name']+'_'+unique_hash)!==null)
                                   {  
                                       if($(".search_type_"+unique_hash).val()==='basic')
                                       {
                                            //click to change value from basic to advanced
                                            $(".search_type_"+unique_hash).click();
                          
                                       }

                                    $('#'+$(this)[0]['name']).val(readCookie($(this)[0]['name']+'_'+unique_hash).split('_')[0]);

                                   $('[name ='+$(this)[0]['name']+']').val(readCookie($(this)[0]['name']+'_'+unique_hash).split('_').slice(1).join('_'));
                                   $('[name ='+$(this)[0]['name']+']').show();
                                      trigger =true;
                                  }
                        });
               
                        if(trigger)
                        $('#filtering_form').trigger('submit');
     
		// Unique id - unchanged
		var adv_id = "#advanced_search_"+unique_hash;


		// Change in first dropdown
		$(adv_id+" .option_selection_common").on("change",function()
		{
			// extract value from data attr - which gives field name
			var attr = $(this).attr("data");

			// if dropdown selected is none
			if(this.value == "none")
			{
				// hide userinput field table cells all inputs
				$(adv_id+" .cell_of_"+attr+" input").hide();
			}else
			{
				// show userinput field table cells all inputs
				$(adv_id+" .cell_of_"+attr+" input").show();
			}

			// if option clicked one is between
			if(this.value == "BETWEEN")
			{	
				// Hide single input division
				$(adv_id+" ."+attr+"_main").hide(); 

				// show double input division
				$(adv_id+" ."+attr+"_sub").show(); 
				
				// show all inputs of double input division
				$(adv_id+" ."+attr+"_sub").show().children().find('input').show();

			
				// If has class time_inputs
				if($(this).hasClass("time_inputs"))
				{
				     // temp array
				     var dopts=[],topts=[];

				     // class with datepicker_fieldname_from exists 
				     if($(adv_id+" .datepicker_"+attr+"_from").length)
				     {
					// if not activated before
					if(!($(adv_id+" .timepicker_"+attr+"_from.datepicker_range").length))
					{
					 	dopts=[
					       		$(adv_id+" .datepicker_"+attr+"_from"),
					       		$(adv_id+" .datepicker_"+attr+"_to"),
					       		( (attr in field_property) ? field_property[attr] : {} )
					      	      ];
						dates(dopts);
					}
				     }

				     // class with timepicker_fieldname_from exists
				     if( $(adv_id+" .timepicker_"+attr+"_from").length)
				     {
					// if not activated before
					if(!($(adv_id+" .timepicker_"+attr+"_from.datetimepicker_range").length))
					{ 
						topts=[
					       		$(adv_id+" .timepicker_"+attr+"_from"),
					       		$(adv_id+" .timepicker_"+attr+"_to"),
					       		( (attr in field_property) ? field_property[attr] : {} )
					      	      ];
						times(topts);
					}
				      }
				}

			}else
			{
				// show single input division
				$(adv_id+" ."+attr+"_main").show(); 

				// hide double input division
				$(adv_id+" ."+attr+"_sub").hide(); 

				// If has class time_inputs
				if($(this).hasClass("time_inputs"))
				{
				     // Setup basic options
				     var dateopts ={
						dateFormat: 'yy-mm-dd',
						changeMonth: true,
        					changeYear: true,
        					numberOfMonths: 1,
						yearRange: '1800:2050'
					},timeopts={
						dateFormat: 'yy-mm-dd',
						timeFormat: 'HH:mm:ss',
						changeMonth: true,
        					changeYear: true,
        					numberOfMonths: 1,
						yearRange: '1800:2050'
					}, other_lists = ["IN","NOT IN","LIKE","NOT LIKE"]; 
			
					// if field_property exists
					if(attr in field_property)	
					{
						var obj = field_property[attr]; 

						if("start" in obj && obj["start"] !== null && "end" in obj && obj["end"] !== null)
						{
							dateopts.minDate = new Date( obj["start"].split(" ")[0]);
							dateopts.maxDate = new Date( obj["end"].split(" ")[0]);
						
							if(obj["start"].split(" ").length == 2 && obj["end"].split(" ").length ==2)
							{
							  var istart = mysqltimestamp2javascript(obj["start"]);
        						  timeopts.minDate = new Date(istart);
							  timeopts.minDateTime = new Date(istart.getTime());	

							  var iend   = mysqltimestamp2javascript(obj["end"]);	
							  timeopts.maxDate = new Date(iend);
							  timeopts.maxDateTime = new Date(iend.getTime());
							}
						}
						if("dateFormat" in obj && obj.dateFormat !== null)
						{
							dateopts.dateFormat = obj.dateFormat;
							timeopts.dateFormat = obj.dateFormat;
						}
						if("timeFormat" in obj && obj.timeFormat !== null)
						{
							timeopts.timeFormat = obj.timeFormat;
						}
					}


					// If input field exists with class
					if( $(adv_id+" .datepicker_"+attr).length)
					{

						// If IN or NOT IN is selected
						if($.inArray(this.value,other_lists) !== -1)
						{
							$(adv_id+" .datepicker_"+attr).datepicker("destroy");
							$(adv_id+" .datepicker_"+attr).removeClass("datepicker");
							$(adv_id+" .datepicker_"+attr).attr("readonly", false);
						}else
						{
						  // If picker not activated before
						  if(!($(adv_id+" .timepicker_"+attr+".datepicker").length))
						  {
							// Readonly
							$(adv_id+" .datepicker_"+attr).attr("readonly", true);

							// Activate datepicker
					  		$(adv_id+" .datepicker_"+attr).datepicker(dateopts).addClass('datepicker');
						  }
						}
					}

					// If input field exists with class
					if( $(adv_id+" .timepicker_"+attr).length)
					{

						// If IN or NOT IN is selected
						if($.inArray(this.value,other_lists) !== -1)
						{
							$(adv_id+" .timepicker_"+attr).datepicker("destroy");
							$(adv_id+" .timepicker_"+attr).datetimepicker("destroy");
							$(adv_id+" .timepicker_"+attr).removeClass("datetimepicker");
							$(adv_id+" .timepicker_"+attr).attr("readonly", false); 
						}else
						{
						  // If picker not activated before
						  if(!($(adv_id+" .timepicker_"+attr+".datetimepicker").length))
						  {
							// Readonly
							$(adv_id+" .timepicker_"+attr).attr("readonly", true);

							// activate datetimepicker and addclass datetimepicker
					  		$(adv_id+" .timepicker_"+attr).datetimepicker(timeopts).addClass('datetimepicker');
						  }
						}
					}
				}
			}
		});


		// Put off event which is initialised from flexigrid.js
		$('form[data="'+unique_hash+'"]').find('.search_clear').off("click");
	
		// On click
		$('form[data="'+unique_hash+'"]').find('.search_clear').on("click",function()
		{
                  
                  //erase all cookies with advanced search data
                $('.allcelldata_'+unique_hash).each(function() {
                                   if(readCookie($(this)[0]['name']+'_'+unique_hash)!==null)
                                   {  
                                      
                                   
                                      eraseCookie($(this)[0]['name']+'_'+unique_hash);
                                 
                                 
                                  }
                        });
               
			// Reset all input and select from advanced_search_* div
			$("#advanced_search_"+unique_hash).children().find('input,select').each(function(){
  				if($(this).getType() =="select"){
					// Reset index
					$(this).prop('selectedIndex',0); 

					// fire change event to hide user input box
					$(this).change(); 
				}else{
					$(this).val('');
				}
			});

			// No change as flexigrid.js
			$('form[data="'+unique_hash+'"]').find('.crud_page').val('1');
			$('form[data="'+unique_hash+'"]').find('.search_text').val('');

			// Copy value from radio input
			var tmp = $(".main_search_type_"+unique_hash).val();

			// make it basic for easy clearing
			$(".main_search_type_"+unique_hash).val('basic');

			// Submit form
			$('form[data="'+unique_hash+'"]').trigger('submit');

			// Again copy value saved in tmp back to hidden input
			$(".main_search_type_"+unique_hash).val(tmp);
		});

});
