<?php require_once("header.php"); ?>
  <form id="test_result" class="appnitro"  method="post" action="cgi-bin/test-results.pl">
    <div class="form_description">
      <h2>Test Results</h2>
      <p>View Autotest Results</p>
    </div>                      

    <ul >
      <li id="li_1" >
        <label class="description" for="element_1">Revision to Compare Against Release</label>

        <div>
        <select class="element select medium" id="element_1" name="revision"> 
          <?php
            // Parse the current's revision number
            $last_trunk_rev = get_revision("/home/$CONFIG[USER]/AutoClassifierTest/current.test-result");

            foreach ( get_test_revisions() as $revision ) {
              $branch = get_branch("/home/$CONFIG[USER]/AutoClassifierTest/test-results/$revision/test-result");
              // If we failed to parse, that test does not exist or probably failed.
              if($revision == "" || $branch == "") {
                continue;
              }
              if($revision == $last_trunk_rev) 
                echo "<option value=\"$revision\" selected=\"selected\">$revision ($branch)</option>\n";
              else
                echo "<option value=\"$revision\">$revision ($branch)</option>\n";
            }
          ?>
        </select>
        </div>
        <p class="guidelines" id="guide_1"><small>Choose the Revision Number to Compare the test-results.</small></p> 
      </li>       

      <li class="buttons">
        <input type="hidden" name="form_id" value="72372" />
        <input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
      </li>

    </ul>
  </form> 
<?php require_once("footer.php"); ?>
