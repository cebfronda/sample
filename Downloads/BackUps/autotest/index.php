<?php require_once("header.php"); ?>
<!-- start checkboxTree configuration -->
    <script type="text/javascript" src="checkboxtree/library/jquery-1.4.4.js"></script>
    <script type="text/javascript" src="checkboxtree/library/jquery-ui-1.8.12.custom/js/jquery-ui-1.8.12.custom.min.js"></script>
    <link rel="stylesheet" type="text/css"
          href="checkboxtree/library/jquery-ui-1.8.12.custom/css/smoothness/jquery-ui-1.8.12.custom.css"/>

    <script type="text/javascript" src="checkboxtree/jquery.checkboxtree.js"></script>
    <link rel="stylesheet" type="text/css" href="checkboxtree/jquery.checkboxtree.css"/>
    <!-- end checkboxTree configuration -->

<!-- testing --> 


<script type="text/javascript">

        var checkboxHandlerObj = {
            populate: function() {
                var tree = '';
                $.getJSON('cgi-bin/get_samples.pl', function(data) {
                    tree = tree + '<ul id="tree_samples">';
                    $.each(data, function(file, lines) {
                        // every parent element should be contained in a <li>
                        if ( typeof(lines) == 'object' ) {
                            var id = 'sites_'+file;
                            tree = tree + '<li> <input id="'+id+'" class="parent checkbox cbox" type="checkbox" value="1" checked="checked"/>'+
                                '<label for="'+id+'">'+file+'</label>';
                            // insert another <ul> for the child that is a directory.. (doesn't need to specify id)
                            tree = tree + '<ul>';
                            $.each(lines, function(file2, lines2) {
                                // every child element should be contained in a <li>
                                if ( typeof(lines2) == 'object' ) {
                                    var id = 'sites_'+file+'/'+file2;
                                    var up_id = 'sites_'+file;
                                    tree = tree + '<li> <input id="'+id+'" class="parent checkbox parent-'+up_id+' cbox" type="checkbox" value="1" checked="checked"/>';
                                    tree = tree + '<label class="choice " for="'+id+'">'+file2+'</label>';

                                    $.each(lines2, function(file3, lines3) {
                                        // only handle files up to 2 directories under root
                                        if ( typeof(lines3) == 'object' ) {
                                            return true;
                                        }
                                        tree = tree + '<ul>';
                                        // grandchild elements.. should contain starting and closing <li> tags
                                        var id = 'sites_'+file+'/'+file2+'/'+file3;
                                        var up_id = 'sites_'+file+'/'+file2;
                                        var up_up_id = 'sites_'+file;
                                        tree = tree + '<li> <input id="'+id+'" name="'+id+'" class="parent-'+up_id+' parent-'+up_up_id+' checkbox cbox" type="checkbox" value="1" checked="checked"/>'+ '<label class="choice " for="'+id+'">'+file3+' ('+lines3+' sites)</label> </li>';
                                        tree = tree + '</ul>';
                                    }); //end foreach of grandchild level element
                                } else {
                                    var id = 'sites_'+file+'/'+file2;
                                    var up_id = 'sites_'+file;
                                     // files that exists in one directory under root (should have opening and closing <li> tags)
                                    tree = tree + '<li> <input id="'+id+'" name="'+id+'" class="parent-'+up_id+' checkbox cbox" type="checkbox" value="1" checked="checked"/>'+
                                        '<label class="choice " for="'+id+'">'+file2+' ('+lines2+' sites)</label> </li>';
                                }
                            }); //end foreach of child level element
                            tree = tree + '</ul>';
                        } else {
                            // file that exists in root samples dir
                            var id = 'sites_'+file;
                            tree = tree + '<li> <input id="'+id+'" name="'+id+'" class="checkbox cbox" type="checkbox" value="1" checked="checked"/>'+
                                    '<label for="'+id+'">'+file+' ('+lines+' sites)</label> </li>';
                        }
                    });  // end foreach parent level element
                    tree = tree + '</ul>';
                    $('div#sample').append(tree);
                    $('#tree_samples').checkboxTree();
                    checkboxHandlerObj.init();
                 });
    },

        init: function() {
            $('#sample input:checkbox[class~="parent"]').click(checkboxHandlerObj.parentClicked);
            $('#sample input:checkbox[class^="parent-"]').click(checkboxHandlerObj.childClicked);
        },
        
        parentClicked: function() {
            if ( $(this).attr('checked') ) {
                $('#sample input:checkbox[class~="parent-' + $(this).attr('id') + '"]').attr('checked', 'checked');
            } else {
                $('#sample input:checkbox[class~="parent-' + $(this).attr('id') + '"]').removeAttr('checked');
            }
        },
        
        childClicked: function() {
            var temp = $(this).attr('class').split('-');
            var parentId = temp[1];
    
            if ( $(this).attr('checked') ) {
                $('#' + parentId).attr('checked', 'checked');
            } else {
                var atLeastOneEnabled = false;
                $('#sample input:checkbox[class~="' + $(this).attr('class') + '"]').each( function() {
                    if ( $(this).attr('checked') ) {
                        atLeastOneEnabled = true;
                    }
                } );
                if ( !atLeastOneEnabled ) {
                    $('#' + parentId).removeAttr('checked');
                }
            }
        }
    }

    $(document).ready(function() {
        $('#revision-latest').click(function(){
            if ( $('#branch').val() == '' ) {
                alert('Please Specify Branch!');
                return true;
            }
            $.ajax({
                type: "GET",
                url: "cgi-bin/get_latest.pl",
                dataType: "JSON", 
                data: {branch : $('#branch').val()},
                success: function(data) {
                    $('#revision').val(parseInt(data));
                    $('#use-latest').attr('checked', true);
                    $('#use-latest').val(1);
                }
            });
        });

        $('#select-all').click(function() {
            $('.cbox').attr('checked', true);
        });
        
        $('#deselect-all').click(function() {
            $('.cbox').attr('checked', false);
        });
        checkboxHandlerObj.populate();
    });
</script>

		<form id="form" class="appnitro"  method="post" action="cgi-bin/autotest.pl">
        <div class="form_description">
			<h2>Autotest Launcher</h2>
		</div>						
			
        <label class="description" for="branch">Branch </label>
		<div>
		<select class="select medium" id="branch" name="branch"> 
			<option value="" selected="selected"></option>
			<?php
				$cmd = "svn ls " . $CONFIG["SVN_PATH"] . "branches";
				$branches = `$cmd`;
				echo '<option value="trunk" >trunk </option>' ."\n";
				foreach(split("/\n", $branches) as $branch) {
					if($branch =="") {
						continue;
					}
					echo "<option value=\"$branch\" >$branch</option> \n";
				}
			?>

		</select>
		</div> 
        <br/>
        
        <label class="description" for="revision">Revision </label>
		<div>
			<input id="revision" name="revision" class="text medium" type="text" maxlength="255" value=""/> 
            <input id="revision-latest" name="revision-latest" class="button" type="button" value="Use Latest"/>
            <input id="use-latest" name="use-latest" type="checkbox" value="0" class="hidden"/>
        </div> 
        <p class="guidelines" id="guide_1"><small>Make sure that AutoClassifier has a higher revision than other projects like AVTools and TOASTER.</small></p> 
        <br/>
        
        <label class="description" for="element_2">Reason/Notes <i> (no spaces) </i></label>
        <div>
			<input id="reason" name="reason" class="text medium" type="text" maxlength="255" value=""/> 
		</div> 
        <br/>
		<label class="description" for="element_5">Options </label>
		<span>
			<input id="svn_update" name="svn_update" class="checkbox" type="checkbox" value="1" checked="checked"/> <label for="svn_update">Update Repos</label> <br/>
            <input id="build_repos" name="build_repos" class="checkbox" type="checkbox" value="1" checked="checked"/> <label for="build_repos">Build Repos</label> <br/>
        </span> 
        <br/>
        <br/>

		<label class="description" for="element_7">Samples&nbsp; &nbsp; &nbsp; &nbsp; 
            <input type="button" class="sample-button" id="select-all" value="select all"/>
            <input type="button" class="sample-button" id="deselect-all" value="deselect all"/>
        </label>      
        <div id="sample">
            <!-- make checkboxes for samples on a heirarchical level.. -->
        </div> 
        <br/>
        <br/>

		<div>
		<label class="description" for="element_3">Launch after</label>
		<span>
			<input id="time_value" name="after_time_value" class="text " size="2" type="text" maxlength="2" value="0"/> - 
		</span>
		<select class="select small" id="after_time" name ="after_time">
			<option value="minutes" selected="selected">minute(s)</option>
			<option value="hours">hour(s)</option>
			<option value="days">day(s)</option>
		</select>
		</div>
        <br/>
        <div class="buttons">
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
        </div>
		</form>	

<?php require_once('footer.php' ); ?>
