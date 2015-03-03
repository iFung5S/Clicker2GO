<?php
/*
 * cannot be called by itself but only be included in other php scripts
 * generates html code to list the answers depneding on parameters:
 *
 *
 * $visible = (boolean) if the answers are visible themselves or only A, B, C ..
 * $num_answers = the number of answers
 * $num_to_select = the number of answers the student is required to select
 *   (1-> use radioboxes, more -> use checkboxes)
 * $show_given_answers = to show or not(as checked or selected)
 *    the selected by the user as correct answers
 *    (either recorded or just submitted but not recorded)
 * $given_answer = the given answer (required only if show given or show correct is true)
 * $show_correct_answers = to show or not the correct answers
 * $form_action = the action to be performed with submit button
 * $hidden_post_vars = hidden variables to be passed to next page through POST method
 * $submit_button = the submit_button html <input.. code (only if exists)
 *
 * $question_row = the question row data object as returned by idiorm
 *
 * the generated string is stored in $answers
 */


  if ($num_to_select == 1)
    $input_type = 'radio';
  else
    $input_type = 'checkbox';

  // echo "chosen input type is: $input_type"; // for debugging -> to remove

  //can change answer while wait for count down to see answer
  //disabled at q-answered page
  if ($show_given_answers && !isset($submission_on_time))
  {
    $disabled = 'disabled';
    if ($show_correct_answers)
      $num_answered_correctly = 0;
  }
  else
    $disabled = '';


  // create the form, add the hidden_post_vars add start a list for the answers
  $answers = "<form id='answer_form' action=$form_action method='POST'>
                $hidden_post_vars
                <ul style ='list-style-type:none;word-wrap:break-word'>";


  $numbering_characters="ABCDEF"; // a string of answers numbering


  for ($i=1; $i<=$num_answers; $i++)
  {
    $correct = '';
    $checked = '';
    $chosen_style = '';
    $current_answer = 'answer' . $i;
    $empty_value = "<input name='no_answer' type='hidden' value='' />";
    if ($visible)
    {
      $answer = $question_row->get($current_answer); // get the specific answer
      if ($show_given_answers)
      {
        $is_given_answer = in_array($current_answer, $given_answer);
        if ($is_given_answer)
        {
          $checked = "checked";
          if ($current_time > $endtime)
           { $chosen_style = "style='font-weight:bold;color:red'"; }
        }
        if ($show_correct_answers && in_array($current_answer, $correct_answer))
        {
          $correct = "<script>document.getElementById('$current_answer').style.color='green'</script>";
          if ($is_given_answer)
            $num_answered_correctly++;
        }
      } // if show_given_answers
    } // if visible
    else
      $answer = "";


    $N = $numbering_characters[$i-1];
    $answers = $answers .
                "<li  id='$current_answer' $chosen_style>
                  <input name='answer[]' type='$input_type' value='$current_answer' id='$N' $checked $disabled />
                  <label for=$N>$N. $answer </label>
                </li>$correct";
  }

  // create a form submit button at the end and close the list and form tags
  $answers = $answers . $empty_value . $submit_button . "</ul> </form>";
  
  if (!$visible) { $answers = "<div class='normalTextStyle'>".$answers."</div>"; }
  
?>
