
countPos = 0;
countEdu = 0;

// http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
$(document).ready(function(){
    window.console && console.log('Document ready called');

    $('#addPos').click(function(event){
        // http://api.jquery.com/event.preventdefault/
        event.preventDefault();
        if ( countPos >= 9 ) {
            alert("Maximum of nine position entries exceeded");
            return;
        }
        countPos++;
        window.console && console.log("Adding position "+countPos);
        $('#pos').append(
            '<div id="position'+countPos+'"> \
            <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
            <input type="button" value="-" onclick="$(\'#position'+countPos+'\').remove();return false;"><br>\
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>');
    });

    $('#addEdu').click(function(event){
        event.preventDefault();
        if ( countEdu >= 9 ) {
            alert("Maximum of nine education entries exceeded");
            return;
        }
        countEdu++;
        window.console && console.log("Adding education "+countEdu);

        $('#edu').append(
            '<div id="edu'+countEdu+'"> \
            <p>Year: <input type="text" name="eduyear'+countEdu+'" value="" /> \
            <input type="button" value="-" onclick="$(\'#edu'+countEdu+'\').remove();return false;"><br>\
            <p>School: <input type="text" size="80" name="eduschool'+countEdu+'" class="school" value="" />\
            </p></div>'
        );

        // $('.school').autocomplete({
        //     source: "school.php"
        // });

    });

    // $('.school').autocomplete({
    //   source: "school.php"
    // });

});



function doValidate() {
  console.log('Validating...');
  try {
    fn = document.getElementById('fn').value;
    ln = document.getElementById('ln').value;
    sum = document.getElementById('sum').value;
    hl = document.getElementById('hl').value;
    em = document.getElementById('email').value;
    if (fn == null || fn == "" || ln=='' || ln==null || sum=='' || sum==null || em=='' || em==null || hl=='' || hl==null) {
      alert("All fields must be filled out");
      return false;
    }
    else if (em.indexOf('@') == -1){
      alert("Invalid email address");
      return false;
    }
    return true;
  } catch(e) {
    return false;
  }
  return false;
}
