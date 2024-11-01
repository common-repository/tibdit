<?php 

$plugurl= plugin_dir_url( __FILE__ );

$bd_help_overview=<<<bd_help_overview
      <p>
            tibit enables your visitors to send you 'pocket change' microdonations or micropayments called 'tibs'.  You may collect tibs
            either as a token of appreciation for content you provide free, as a tiny fee to access some content on your site, or both.
      </p>
      <p>
            Each tib button or link has a subreference, which is used to keep track of what has been tibbed. The plugin, by default, will assign a subreference based on the ID of the page of plugin a [tib] shortcode is used within. In the case of the widget, it will use the subreference specified in the widget's options. To override the shortcode subreference with your own custom subreference, use the SUB parameter, like so: [tib SUB="my_subref"]
      </p>
      <p>
            Users set their own tib value, and every transaction is always for just one tib.  This means 'tibbers' don't have to think twice before 
            deciding whether or not to tib something.   You won't know exactly who is spending how much, but tibs
            average around GBP 0.15 each (or USD 0.25), so the more people you get to tib your site, the more money you collect.  
      </p>
      <p>
            To make tibbing affordable and accessible to everyone, tibit uses bitcoin to transfer the money to you, once you have received a few tibs.  If you do 
            not already have a bitcoin address, getting one is very easy.  Please see the bitcoin tab on the left to learn how to get one for your blog.
      </p>
bd_help_overview;

$bd_help_settings=<<<bd_help_settings
    
      <p>
            Enter your public bitcoin address below, not your bitcoin private key.  A green tick will show when you have entered a valid bitcoin address. See the demo mode tab on the left to learn more about tibit demo mode.
      </p>
      <p>
            You may also opt to turn on the use of 'Assignees', if you wish. This feature is in early stages, so
            may be best avoided if unfamiliar, but the gist is that you may assign your tibs to go to a charity or
            other recipient of your choice. To do so, hit "Show Advanced Settings" on your settings page, and enter the URL of an enabled page.
            For more
            information,
            e-mail us at <a href="mailto:support@tibit.com">support@tibit.com</a> and/or see <a href=https://tibit.com/tib-charity/
                        style="font-family: monospace;" target='_tibit'>this page</a>.
      </p>
      <p>
            When a user tibs your site, this tib is stored in the user's browser to show an acknowledgement and prevent re-tibbing of the same post or page (i.e. the same subreference) for a duration you can select between one and thirty days.
      </p>

bd_help_settings;

$bd_help_bitcoin=<<<bd_help_bitcoin
      <p>
            Getting a bitcoin address is easy - and you don't need to know anything about bitcoin to get started.  You can obtain and configure a bitcoin address 
            quickly, and find out how to spend or convert the bitcoin you have collected later, perhaps once you have collected enough for it to be worthwhile.  
      </p>
      <p>
            You have many options available if you do not already have a bitcoin address you wish to use:
            <ol>
                  <li>
                        If you're in the UK, one very fast and very simple way is to go to <a href="https://www.circle.com" style="font-family: monospace;" target='_tibit'>Circle.com</a> and follow the instructions there.
                  </li>
                  <li>      
                        If you are EU based, then you may want to try <a href=https://cryptopay.me/ style="font-family: monospace;" target='_tibit'>Cryptopay</a>.
                  </li>
                  <li>
                        If neither of these options seem suitable, you may wish to try <a style="font-family:
                        monospace;" href=https://www.coinbase.com/?locale=en>CoinBase</a> or <a style="font-family:
                        monospace;" href=https://blockchain.info/>Blockchain.info</a>.
                  </li>
            </ol>
      </p>
      <p>
            You can check the balance of bitcoin at your address at any time by clicking the button labelled 'view transactions'
      </p>
bd_help_bitcoin;


$bd_help_shortcodes=<<<bd_help_shortcodes
      <p>
            As well as the tibit widget (see the widgets tab on the left) shortcodes are also supported for placing
            tibbing buttons wherever you want on your blog.
      </p>
      <p>
            Use <span style="font-family: monospace;"> [tib] </span> to place a tib button anywhere on your site.
            Attached to this button will be a 'subreference,' an identifier to differentiate the different tib buttons
             on your site. Two buttons with the same subreference will acknowledge one tib for both buttons, and
             share a counter between them.
      </p>

      <p>
             By default, <span style="font-family: monospace;"> [tib] </span> shortcode will pull in the ID of the post
              or page it's found within to set the subref, but you
              may override this and set your own subreference by using a shortcode parameter, like so: <span
              style="font-family: monospace;"> [tib SUB="my_subref"] </span>.
      </p>


bd_help_shortcodes;

// Took this out of help-text because the functionality isn't working. Move it back into the block above when it works!
// <p>
//       You can also override settings for individual shortcodes.  For example<span style="font-family: monospace;"> [tib_post payaddr="bitcoinaddress"] </span> 
//       or<span style="font-family: monospace;"> [tib_site subref="WP_<i>sometext</i>"] </span>.
// </p>


$bd_help_widgets=<<<bd_help_widgets

      <p>
            You can place tib widgets anywhere permitted by your WordPress theme.  Widgets let you specify a title, intro blurb, and subref.  
      </p>
      <p>
            We recommended you always set subref to <span style="font-family: monospace;"> WP_<i>sometext</i> </span>, where <i>sometext</i> is specific to the widget. This ensures that each widget gets its own counter.
      </p>
bd_help_widgets;


$bd_help_demomode=<<<bd_help_demomode

      <p><img src='$plugurl/resources/images/testmode-icon-24px.png' style='width: 1.3em; vertical-align: middle'></p>
      <p>
            You can use tibit demo mode to check the plugin on your site with no risk.
      </p>
      <p>
            Bitcoin addresses that start with <span style="font-family: monospace;">'m'</span> or <span style="font-family: monospace;">'n'</span> are 'testnet' 
            addresses that can be used readily with no actual money or value involved.  tibit will detect a testnet address and trigger demo mode, which allows
            anyone to experiment with tibbing at no risk.  They can purchase a bundle of demo mode tibs by magic and then spend them on any tibbable site with a
            testnet bitcoin address configured.  tibit demo mode is indicated by yellow bordering and/or beaker icons
             (as above) on each button.
      </p>

      <p>
            Conversely, bitcoin addresses that start with a <span style="font-family: monospace;">'1'</span> are production, or 'mainnet' addresses, and users need
            to have purchased a bundle of real tibs in order to tib you if you have configured a bitcoin 'mainnet' addresses. The bitcoin testnet and mainnet are 
            completely separate, there is no risk of spending real tibs/bitcoins on the testnet, or vice versa.
      </p>
      <p>
            You can generate you own testnet bitcoin address in just a few seconds at<br><a style="font-family: monospace;" 
            href="https://www.bitaddress.org/bitaddress.org-v2.9.3-SHA1-7d47ab312789b7b3c1792e4abdb8f2d95b726d64.html?testnet=true">bitaddress testnet edition</a>.
      </p>

bd_help_demomode;
?>

