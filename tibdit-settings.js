// version 13

// jQuery(document).ready(function($){
//     $('.my-color-field').wpColorPicker();
// });

function bd_PAD_change( f, plugurl)
{
  bd_base54_clean( f);
  valid = "132mn";

  if( f.value === "")
  {
    PAD_field_status.innerHTML="&emsp;&#10068;";
    PAD_field_status.style.color='orange';
    submit.disabled = false;
  }
  else if( valid.indexOf(f.value.substr(0, 1)) == -1)
  {
    PAD_field_status.innerHTML="&emsp;&cross;";
    PAD_field_status.style.color='red';
    submit.disabled = true;
  }
  else if( f.value.length < 26)  // too short
  {
    PAD_field_status.innerHTML="&emsp;&quest;";
    PAD_field_status.style.color='blue';
    submit.disabled = true;
  }
  else if (check_address(f.value))  
  {
    PAD_field_status.innerHTML="&emsp;&check;&nbsp;";
    PAD_field_status.style.color='green';
    submit.disabled = false;
  }
  else
  {
    PAD_field_status.innerHTML="&emsp;&cross;"; // long enough but invalid
    PAD_field_status.style.color='orange';
    submit.disabled = true;
  }

  //blockchain.disabled = submit.disabled;


  if( valid.indexOf(f.value.substr(0, 1)) >= 2 )
  {
    PAD_field_status.innerHTML=
      PAD_field_status.innerHTML.concat("&ensp;<img src='" + plugurl + "/resources/images/testmode-icon-24px.png' style='width: 1em; vertical-align: middle'>");

      // document.getElementById('blockchain').onclick="{window.open('https://www.biteasy.com/testnet/addresses/" + f.value + "')}";
      // document.getElementById('blockchain').onclick=="{window.open('http://tibdit.com')}";

  }
  // else
  // {
  //   document.getElementById('blockchain').onclick="{window.open('https://www.biteasy.com/addresses/" + f.value + "')}";
  // }
}

function biteasy_blockchain()
{
  valid = "132mn";
  if ( valid.indexOf(document.getElementById('PAD').value.substr(0, 1)) >= 2 )
  {
    window.open("https://www.biteasy.com/testnet/addresses/" + document.getElementById("PAD").value);
  }
  else if (valid.indexOf(document.getElementById('PAD').value.substr(0, 1)) != -1) 
  {
    window.open("https://www.biteasy.com/addresses/" + document.getElementById('PAD').value);
  };
}

function bd_base54_clean( f) 
{
  ss = f.selectionStart;
  se = f.selectionEnd;
  f.value = f.value.replace(/[^A-HJ-NP-Za-km-z1-9]/g,"");
  f.setSelectionRange(ss,se);
}


function bd_plugin_lowercase_tib( f)
{
  ss = f.selectionStart;
  se = f.selectionEnd;
  f.value = f.value.replace( /([Tt][iI][bB])([^ACE-Zace-z][\w]*|$)/g, function(tibword) { return tibword.toLowerCase(); } );
  f.setSelectionRange(ss,se);
}


// function hidetooltips()
// {
//   document.styleSheets[0].cssRules[0].cssText = 
//     ".tooltip { display: none; }";
// }

